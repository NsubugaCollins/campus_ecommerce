@extends('layouts.admin')

@section('title', 'All Messages Log')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="text-white fw-bold mb-0">Global Message Log</h4>
        <p class="text-muted small">Viewing all system messages across all conversations.</p>
    </div>
    <div class="btn-group shadow-sm">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary px-3">Conversations</a>
        <a href="{{ route('admin.messages.all') }}" class="btn btn-primary px-3 active">All Messages</a>
    </div>
</div>

<div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background: transparent;">
                <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <tr>
                        <th class="px-4 py-3 border-0 text-secondary">Time</th>
                        <th class="px-4 py-3 border-0 text-secondary">Sender</th>
                        <th class="px-4 py-3 border-0 text-secondary">Receiver</th>
                        <th class="px-4 py-3 border-0 text-secondary">Message Content</th>
                        <th class="px-4 py-3 border-0 text-secondary text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                        <td class="px-4 py-3 border-0 align-middle small text-muted">
                            {{ $msg->created_at->format('M d, H:i') }}
                            <div style="font-size: 0.65rem;">{{ $msg->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-4 py-3 border-0 align-middle">
                            <span class="text-white fw-bold">{{ $msg->sender->name }}</span>
                            @if($msg->sender->role === 'admin')
                                <span class="badge bg-danger ms-1" style="font-size: 0.5rem;">ADMIN</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-muted small">
                            {{ $msg->receiver->name }}
                        </td>
                        <td class="px-4 py-3 border-0 align-middle">
                            <div class="text-white-50 small" style="max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                @if($msg->media_url)
                                    <span class="text-primary fw-bold me-1">[{{ strtoupper($msg->media_type) }}]</span>
                                @endif
                                {{ $msg->message ?: '(Media Only)' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-end">
                            <a href="{{ route('admin.messages.show', $msg->sender_id == Auth::id() ? $msg->receiver_id : $msg->sender_id) }}" class="btn btn-sm btn-link text-primary p-0">View Chat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No messages found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $messages->links('pagination::bootstrap-5') }}
</div>
@endsection
