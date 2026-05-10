<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function index()
    {
        // Get unique users who have sent or received messages from/to admins
        $userIds = Message::where('receiver_id', Auth::id())
                          ->orWhere('sender_id', Auth::id())
                          ->pluck('sender_id', 'receiver_id')
                          ->flatten()
                          ->unique()
                          ->filter(fn($id) => $id != Auth::id());

        $users = User::whereIn('id', $userIds)->withCount(['receivedMessages' => function ($query) {
            $query->where('receiver_id', Auth::id())->where('is_read', false);
        }])->get();

        return view('admin.messages.index', compact('users'));
    }

    public function show(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->update(['is_read' => true]);

        return view('admin.messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message'     => 'nullable|string|max:5000',
            'reply_to_id' => 'nullable|exists:messages,id',
            'file'        => 'nullable|file|max:102400|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml,audio/mpeg,audio/ogg,audio/wav,audio/aac,audio/flac,video/mp4,video/mpeg,video/quicktime,video/x-msvideo,video/webm,video/x-matroska',
        ]);

        // Must have at least a message or a file
        if (empty($request->message) && !$request->hasFile('file')) {
            return back()->withErrors(['message' => 'Please type a message or attach a file.']);
        }

        $mediaUrl  = null;
        $mediaType = null;
        $mediaName = null;

        if ($request->hasFile('file')) {
            $file      = $request->file('file');
            $mime      = $file->getMimeType();
            $mediaName = $file->getClientOriginalName();

            if (str_starts_with($mime, 'image/')) {
                $mediaType    = 'image';
                $resourceType = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $mediaType    = 'video';
                $resourceType = 'video';
            } else {
                $mediaType    = 'audio';
                $resourceType = 'raw';
            }

            $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
            $result     = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder'        => 'campus_mall/messages',
                'resource_type' => $resourceType,
                'public_id'     => 'msg_' . Str::uuid(),
            ]);

            $mediaUrl = $result['secure_url'];
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'message'     => $request->message ?? '',
            'reply_to_id' => $request->reply_to_id,
            'media_url'   => $mediaUrl,
            'media_type'  => $mediaType,
            'media_name'  => $mediaName,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
