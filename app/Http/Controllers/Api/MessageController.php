<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsMessages;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use FormatsMessages;

    public function index(Request $request)
    {
        $admin = User::where('role', 'admin')->first();
        if (! $admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $messages = Message::where(function ($query) use ($admin, $request) {
            $query->where('sender_id', $request->user()->id)
                ->where('receiver_id', $admin->id);
        })->orWhere(function ($query) use ($admin, $request) {
            $query->where('sender_id', $admin->id)
                ->where('receiver_id', $request->user()->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json([
            'admin' => ['id' => $admin->id, 'name' => $admin->name],
            'messages' => $messages->map(fn ($m) => $this->formatMessage($m))->values(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:5000',
            'reply_to_id' => 'nullable|exists:messages,id',
            'file' => 'nullable|file|max:102400',
        ]);

        if (empty($request->message) && ! $request->hasFile('file')) {
            return response()->json(['message' => 'Message or file required'], 422);
        }

        $admin = User::where('role', 'admin')->first();
        if (! $admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $media = ['media_url' => null, 'media_type' => null, 'media_name' => null];
        if ($request->hasFile('file')) {
            try {
                $media = $this->uploadMessageMedia($request->file('file'));
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $admin->id,
            'message' => $request->message ?? '',
            'reply_to_id' => $request->reply_to_id,
            ...$media,
        ]);

        return response()->json([
            'message' => $this->formatMessage($message),
        ], 201);
    }

    public function destroy(Request $request, Message $message)
    {
        if ($message->sender_id !== $request->user()->id && $message->receiver_id !== $request->user()->id) {
            abort(403);
        }
        $message->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function react(Request $request, Message $message)
    {
        $request->validate(['reaction' => 'required|string|max:10']);
        if ($message->sender_id !== $request->user()->id && $message->receiver_id !== $request->user()->id) {
            abort(403);
        }
        $message->update(['reaction' => $request->reaction]);

        return response()->json(['message' => $this->formatMessage($message)]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['message_ids' => 'required|array']);
        Message::whereIn('id', $request->message_ids)
            ->where(function ($q) use ($request) {
                $q->where('sender_id', $request->user()->id)
                    ->orWhere('receiver_id', $request->user()->id);
            })
            ->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
