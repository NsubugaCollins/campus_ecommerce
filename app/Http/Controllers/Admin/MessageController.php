<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'message' => 'required|string|max:1000',
            'reply_to_id' => 'nullable|exists:messages,id',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'reply_to_id' => $request->reply_to_id,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
