<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * MTN Mobile Money – Collections API wrapper.
 *
 * Handles token caching, Request-to-Pay initiation, and status polling
 * for both the sandbox and production MTN MoMo environments.
 *
 * Reference: https://momodeveloper.mtn.com/api-documentation/collection/
 */
class MtnMomoService
{
    private string $baseUrl;
    private string $subscriptionKey;
    private string $apiUser;
    private string $apiKey;
    private string $environment;
    private string $currency;

    /** In-process token cache (lives for the request lifecycle). */
    private ?string $cachedToken = null;
    private ?int    $tokenExpiresAt = null;

    public function __construct()
    {
        $this->baseUrl         = rtrim(config('mtn.base_url'), '/');
        $this->subscriptionKey = config('mtn.subscription_key');
        $this->apiUser         = config('mtn.api_user');
        $this->apiKey          = config('mtn.api_key');
        $this->environment     = config('mtn.environment', 'sandbox');
        $this->currency        = config('mtn.currency', 'UGX');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Public API
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Initiate a Request-to-Pay (USSD push) to a subscriber.
     *
     * @param  string  $externalId   Your unique reference (e.g. 'SUB-MM-XXXXXXXXXX')
     * @param  string  $phone        E.164 or local Ugandan format (e.g. 256771234567)
     * @param  int     $amount       Amount in UGX (whole number)
     * @param  string  $payerMessage Short message shown on the USSD prompt
     * @param  string  $payeeNote    Internal note recorded by MTN
     * @return string                The MTN Reference ID (UUID) to poll with
     *
     * @throws RuntimeException on HTTP failure
     */
    public function requestToPay(
        string $externalId,
        string $phone,
        int    $amount,
        string $payerMessage = 'Campus Mall Premium Subscription',
        string $payeeNote    = 'Campus Mall subscription payment'
    ): string {
        $token       = $this->getAccessToken();
        $referenceId = (string) Str::uuid();
        $msisdn      = $this->normalizeMsisdn($phone);

        $payload = [
            'amount'     => (string) $amount,
            'currency'   => $this->currency,
            'externalId' => $externalId,
            'payer'      => [
                'partyIdType' => 'MSISDN',
                'partyId'     => $msisdn,
            ],
            'payerMessage' => $payerMessage,
            'payeeNote'    => $payeeNote,
        ];

        Log::info('[MTN MoMo] Sending request-to-pay', [
            'referenceId' => $referenceId,
            'msisdn'      => $msisdn,
            'amount'      => $amount,
        ]);

        $response = $this->client($token)
            ->withHeaders(['X-Reference-Id' => $referenceId])
            ->post("{$this->baseUrl}/collection/v1_0/requesttopay", $payload);

        if ($response->status() !== 202) {
            Log::error('[MTN MoMo] Request-to-pay failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException(
                'MTN MoMo request-to-pay failed: HTTP ' . $response->status()
            );
        }

        return $referenceId;
    }

    /**
     * Poll for the transaction status of a previously initiated request-to-pay.
     *
     * @param  string $referenceId  The UUID returned by requestToPay()
     * @return array{
     *   status: string,
     *   financialTransactionId?: string,
     *   reason?: array
     * }
     *
     * Possible status values:
     *   PENDING   – USSD prompt sent, waiting for user PIN
     *   SUCCESSFUL – Payment confirmed
     *   FAILED    – User rejected / timeout / insufficient funds
     */
    public function getTransactionStatus(string $referenceId): array
    {
        $token    = $this->getAccessToken();
        $response = $this->client($token)
            ->get("{$this->baseUrl}/collection/v1_0/requesttopay/{$referenceId}");

        if ($response->failed()) {
            Log::error('[MTN MoMo] Status check failed', [
                'referenceId' => $referenceId,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
            throw new RuntimeException(
                'MTN MoMo status check failed: HTTP ' . $response->status()
            );
        }

        $data = $response->json();

        Log::info('[MTN MoMo] Status response', [
            'referenceId' => $referenceId,
            'status'      => $data['status'] ?? 'unknown',
        ]);

        return $data;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Internal helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Obtain a Collections API access token, using a short-lived in-process cache.
     */
    private function getAccessToken(): string
    {
        // Re-use if still valid (with a 60-second safety margin)
        if ($this->cachedToken && $this->tokenExpiresAt && time() < ($this->tokenExpiresAt - 60)) {
            return $this->cachedToken;
        }

        $response = Http::withBasicAuth($this->apiUser, $this->apiKey)
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            ])
            ->post("{$this->baseUrl}/collection/token/");

        if ($response->failed()) {
            Log::error('[MTN MoMo] Token fetch failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('Could not obtain MTN MoMo access token: HTTP ' . $response->status());
        }

        $json = $response->json();

        $this->cachedToken    = $json['access_token'];
        $this->tokenExpiresAt = time() + (int) ($json['expires_in'] ?? 3600);

        return $this->cachedToken;
    }

    /**
     * Build a pre-configured HTTP client with the shared MTN headers.
     */
    private function client(string $token): PendingRequest
    {
        return Http::withToken($token)
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'X-Target-Environment'      => $this->environment,
                'Content-Type'              => 'application/json',
            ]);
    }

    /**
     * Normalise a Ugandan phone number to the MSISDN format required by MTN
     * (e.g. 256771234567 — no leading +).
     */
    private function normalizeMsisdn(string $phone): string
    {
        // Strip non-digits
        $digits = preg_replace('/\D/', '', $phone);

        // Local format: 07xxxxxxxx → 25607xxxxxxxx
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '256' . substr($digits, 1);
        }

        // +256 format: strip leading +
        if (str_starts_with($digits, '256') && strlen($digits) === 12) {
            return $digits;
        }

        // Return as-is and let MTN reject if invalid
        return $digits;
    }
}
