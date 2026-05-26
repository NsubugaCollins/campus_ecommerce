<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Message;

trait FormatsMessages
{
    protected function formatMessage(Message $message): array
    {
        $message->loadMissing(['sender:id,name', 'receiver:id,name']);

        $mediaUrl = $message->media_url;
        if ($mediaUrl && ! str_starts_with($mediaUrl, 'http')) {
            $mediaUrl = url($mediaUrl);
        }

        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'sender_name' => $message->sender?->name,
            'receiver_name' => $message->receiver?->name,
            'message' => $message->message,
            'is_read' => (bool) $message->is_read,
            'reply_to_id' => $message->reply_to_id,
            'reaction' => $message->reaction,
            'media_url' => $mediaUrl,
            'media_type' => $message->media_type,
            'media_name' => $message->media_name,
            'created_at' => $message->created_at?->toIso8601String(),
        ];
    }

    protected function uploadMessageMedia($file): array
    {
        $mime = $file->getMimeType();
        $mediaName = $file->getClientOriginalName();

        if (str_starts_with($mime, 'image/')) {
            $mediaType = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $mediaType = 'video';
        } else {
            $mediaType = 'audio';
        }

        if (! config('services.cloudinary.url')) {
            throw new \RuntimeException('File uploads require Cloudinary configuration.');
        }

        $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'campus_mall/messages',
            'resource_type' => 'auto',
        ]);

        return [
            'media_url' => (string) $result['secure_url'],
            'media_type' => $mediaType,
            'media_name' => $mediaName,
        ];
    }
}
