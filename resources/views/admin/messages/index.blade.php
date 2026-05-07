@extends('layouts.admin')

@section('title', 'Direct Messages')

@section('content')
<div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
        <h5 class="mb-0 text-white fw-bold text-uppercase">Recent Conversations</h5>
        <p class="text-muted small">Manage and respond to user inquiries.</p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background: transparent;">
                <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <tr>
                        <th class="px-4 py-3 border-0 text-secondary">User</th>
                        <th class="px-4 py-3 border-0 text-secondary">Last Message</th>
                        <th class="px-4 py-3 border-0 text-secondary text-center">Unread</th>
                        <th class="px-4 py-3 border-0 text-secondary text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);" class="thread-row" data-user-id="{{ $user->id }}">
                        <td class="px-4 py-3 border-0 align-middle">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-weight: bold;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-muted">
                            @php
                                $lastMessage = \App\Models\Message::where(function($q) use ($user) {
                                    $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
                                })->orWhere(function($q) use ($user) {
                                    $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
                                })->latest()->first();
                            @endphp
                            {{ $lastMessage ? Str::limit($lastMessage->message, 50) : 'No messages' }}
                            <br>
                            <small class="text-white-50" style="font-size: 0.7rem;">{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}</small>
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-center">
                            @if($user->received_messages_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $user->received_messages_count }}</span>
                            @else
                                <span class="text-muted opacity-25">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-end">
                            <a href="{{ route('admin.messages.show', $user->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                View Chat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </div>
                            <h6 class="text-white-50">No active conversations</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Context Menu for Threads -->
<div id="threadContextMenu" class="dropdown-menu dropdown-menu-dark shadow" style="display: none; position: absolute; z-index: 1050;">
    <a class="dropdown-item text-danger" href="#" id="menuDeleteThread">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        Delete Conversation
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const threadContextMenu = document.getElementById('threadContextMenu');
        const menuDeleteThread = document.getElementById('menuDeleteThread');
        
        let targetUserId = null;

        document.querySelectorAll('.thread-row').forEach(row => {
            row.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                targetUserId = this.dataset.userId;
                
                threadContextMenu.style.display = 'block';
                threadContextMenu.style.left = e.pageX + 'px';
                threadContextMenu.style.top = e.pageY + 'px';
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('#threadContextMenu') === null) {
                threadContextMenu.style.display = 'none';
            }
        });

        menuDeleteThread.addEventListener('click', function(e) {
            e.preventDefault();
            threadContextMenu.style.display = 'none';
            if(confirm('Are you sure you want to permanently delete this entire conversation?')) {
                fetch('{{ route('messages.thread_destroy') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ user_id: targetUserId })
                }).then(res => res.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      } else {
                          alert('Error deleting conversation.');
                      }
                  }).catch(() => alert('Network error.'));
            }
        });
    });
</script>
@endsection
