<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsMessages;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use FormatsMessages;

    public function index()
    {
        $messages = Message::with(['sender:id,name', 'receiver:id,name'])
            ->latest()
            ->paginate(30);

        return response()->json([
            'data' => $messages->getCollection()->map(fn ($m) => $this->formatMessage($m))->values(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
            ],
        ]);
    }

    public function threads(Request $request)
    {
        $senders = Message::where('receiver_id', $request->user()->id)->pluck('sender_id');
        $receivers = Message::where('sender_id', $request->user()->id)->pluck('receiver_id');
        $userIds = $senders->merge($receivers)->unique()->filter(fn ($id) => $id != $request->user()->id);

        $users = User::whereIn('id', $userIds)->get()->map(function ($user) use ($request) {
            $unread = Message::where('sender_id', $user->id)
                ->where('receiver_id', $request->user()->id)
                ->where('is_read', false)
                ->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'unread_count' => $unread,
            ];
        });

        return response()->json(['threads' => $users->values()]);
    }

    public function show(Request $request, User $user)
    {
        $messages = Message::where(function ($query) use ($user, $request) {
            $query->where('sender_id', $request->user()->id)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user, $request) {
            $query->where('sender_id', $user->id)->where('receiver_id', $request->user()->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'messages' => $messages->map(fn ($m) => $this->formatMessage($m))->values(),
        ]);
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'nullable|string|max:5000',
            'reply_to_id' => 'nullable|exists:messages,id',
            'file' => 'nullable|file|max:102400',
        ]);

        if (empty($request->message) && ! $request->hasFile('file')) {
            return response()->json(['message' => 'Message or file required'], 422);
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
            'receiver_id' => $user->id,
            'message' => $request->message ?? '',
            'reply_to_id' => $request->reply_to_id,
            ...$media,
        ]);

        return response()->json(['message' => $this->formatMessage($message)], 201);
    }
}
