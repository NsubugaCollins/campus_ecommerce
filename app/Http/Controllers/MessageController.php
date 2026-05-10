<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->with('error', 'Admin not found.');
        }

        $messages = Message::where(function ($query) use ($admin) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $admin->id);
        })->orWhere(function ($query) use ($admin) {
            $query->where('sender_id', $admin->id)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        Message::where('sender_id', $admin->id)
               ->where('receiver_id', Auth::id())
               ->update(['is_read' => true]);

        return view('messages.index', compact('messages', 'admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message'     => 'nullable|string|max:5000',
            'reply_to_id' => 'nullable|exists:messages,id',
            'file'        => 'nullable|file|max:102400',
        ]);

        if (empty($request->message) && !$request->hasFile('file')) {
            return back()->withErrors(['message' => 'Please type a message or attach a file.']);
        }

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->with('error', 'Admin not found.');
        }

        $mediaUrl  = null;
        $mediaType = null;
        $mediaName = null;

        if ($request->hasFile('file') && config('services.cloudinary.url')) {
            try {
                $file      = $request->file('file');
                $mime      = $file->getMimeType();
                $mediaName = $file->getClientOriginalName();

                // Determine media type and Cloudinary resource_type
                // Note: Cloudinary uses 'video' resource_type for audio files too
                if (str_starts_with($mime, 'image/')) {
                    $mediaType    = 'image';
                    $resourceType = 'image';
                } elseif (str_starts_with($mime, 'video/')) {
                    $mediaType    = 'video';
                    $resourceType = 'video';
                } else {
                    // audio/* — Cloudinary handles audio under 'video' resource_type
                    $mediaType    = 'audio';
                    $resourceType = 'video';
                }

                $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
                $result     = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder'        => 'campus_mall/messages',
                    'resource_type' => $resourceType,
                    'public_id'     => 'msg_' . Str::uuid(),
                ]);

                $mediaUrl = $result['secure_url'];
            } catch (\Exception $e) {
                \Log::error('Message media upload failed: ' . $e->getMessage());
                return back()->withErrors(['file' => 'File upload failed. Please try again.']);
            }
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $admin->id,
            'message'     => $request->message ?? '',
            'reply_to_id' => $request->reply_to_id,
            'media_url'   => $mediaUrl,
            'media_type'  => $mediaType,
            'media_name'  => $mediaName,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function destroy(Message $message)
    {
        if ($message->sender_id === Auth::id() || $message->receiver_id === Auth::id()) {
            $message->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['message_ids' => 'required|array']);

        Message::whereIn('id', $request->message_ids)
            ->where(function($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            })->delete();

        return response()->json(['success' => true]);
    }

    public function threadDestroy(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $otherUserId = $request->user_id;

        Message::where(function($q) use ($otherUserId) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $otherUserId);
        })->orWhere(function($q) use ($otherUserId) {
            $q->where('sender_id', $otherUserId)->where('receiver_id', Auth::id());
        })->delete();

        return response()->json(['success' => true]);
    }

    public function react(Request $request, Message $message)
    {
        $request->validate([
            'reaction' => 'required|string|max:10',
        ]);

        if ($message->sender_id === Auth::id() || $message->receiver_id === Auth::id()) {
            $message->update(['reaction' => $request->reaction]);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
