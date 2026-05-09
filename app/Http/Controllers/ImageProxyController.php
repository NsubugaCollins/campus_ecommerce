<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ImageProxyController extends Controller
{
    /**
     * Proxy Cloudinary images through the local server to bypass DNS/network blocks.
     */
    public function show(Request $request)
    {
        $url = $request->query('url');

        // SSRF Protection: Only allow proxying from res.cloudinary.com
        if (!$url || !\Illuminate\Support\Str::startsWith($url, 'https://res.cloudinary.com/')) {
            abort(404);
        }

        // Cache the image for 24 hours to reduce outbound bandwidth and improve latency
        $cacheKey = 'proxy_image_' . md5($url);

        $imageContent = Cache::remember($cacheKey, 86400, function () use ($url) {
            try {
                $response = Http::timeout(10)->get($url);
                if ($response->successful()) {
                    return $response->body();
                }
            } catch (\Exception $e) {
                \Log::error('Image proxy failed for URL: ' . $url . ' Error: ' . $e->getMessage());
            }
            return null;
        });

        if (!$imageContent) {
            abort(404);
        }

        // Detect mime type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);

        return response($imageContent)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
