@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="background: rgba(30, 30, 30, 0.8); backdrop-filter: blur(20px); border-radius: 1.5rem;">
                <div class="card-header bg-primary text-white p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">
                        {{ substr($admin->name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $admin->name }}</h5>
                        <small class="text-white-50">Support Administrator</small>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div id="chat-messages" class="p-4" style="height: 450px; overflow-y: auto; background: rgba(0, 0, 0, 0.2);">
                        @forelse($messages as $message)
                            <div class="d-flex {{ $message->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-4">
                                <div class="max-w-75 position-relative">
                                    @if($message->replyTo)
                                        <div class="small text-muted mb-1 px-2 py-1 rounded" style="background: rgba(255,255,255,0.05); border-left: 3px solid #0d6efd; font-size: 0.75rem;">
                                            <div class="fw-bold">{{ $message->replyTo->sender_id === Auth::id() ? 'You' : $admin->name }}</div>
                                            <div class="text-truncate" style="max-width: 200px;">{{ $message->replyTo->message }}</div>
                                        </div>
                                    @endif
                                    <div class="p-3 message-bubble {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-dark text-light border border-secondary' }}"
                                         data-id="{{ $message->id }}" data-text="{{ $message->message }}" data-sender="{{ $message->sender_id === Auth::id() ? 'You' : $admin->name }}" style="cursor: context-menu; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

                                        {{-- Media display --}}
                                        @if($message->media_url)
                                            @php $proxyUrl = route('image.proxy', ['url' => $message->media_url]); @endphp
                                            @if($message->media_type === 'image')
                                                <a href="{{ $proxyUrl }}" target="_blank">
                                                    <img src="{{ $proxyUrl }}" alt="{{ $message->media_name }}"
                                                         style="max-width: 260px; max-height: 260px; border-radius: 0.6rem; display: block; margin-bottom: {{ $message->message ? '8px' : '0' }};">
                                                </a>
                                            @elseif($message->media_type === 'video')
                                                <video controls style="max-width: 260px; border-radius: 0.6rem; display: block; margin-bottom: {{ $message->message ? '8px' : '0' }};">
                                                    <source src="{{ $proxyUrl }}">
                                                    Your browser does not support video.
                                                </video>
                                            @elseif($message->media_type === 'audio')
                                                <div class="d-flex align-items-center gap-2 mb-{{ $message->message ? '2' : '0' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="opacity-75"><path d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13s1.12-2 2.5-2 2.5.895 2.5 2zm9-2c0 1.105-1.12 2-2.5 2S10 12.105 10 11s1.12-2 2.5-2 2.5.895 2.5 2zM6 13V3l9-2v10"/></svg>
                                                    <audio controls style="max-width: 220px; height: 36px;">
                                                        <source src="{{ $proxyUrl }}">
                                                    </audio>
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Text --}}
                                        @if($message->message)
                                            {{ $message->message }}
                                        @endif
                                    </div>
                                    @if($message->reaction)
                                        <div class="position-absolute shadow-sm" style="bottom: 10px; {{ $message->sender_id === Auth::id() ? 'left: -15px;' : 'right: -15px;' }} background: #2b2b2b; border-radius: 50%; padding: 2px 5px; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1);">
                                            {{ $message->reaction }}
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-1 {{ $message->sender_id === Auth::id() ? 'text-end' : 'text-start' }}" style="font-size: 0.75rem;">
                                        {{ $message->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                </div>
                                <h5 class="text-white-50">No messages yet</h5>
                                <p class="text-muted small">Send a message to start a conversation with the admin.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-4 border-top border-secondary">
                        {{-- Reply Banner --}}
                        <div id="replyBanner" class="d-none align-items-center justify-content-between bg-dark border border-secondary rounded-top p-2" style="border-bottom: none !important; margin-bottom: -5px; padding-bottom: 10px !important;">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" class="me-2"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
                                <div>
                                    <div class="small fw-bold text-white" id="replyToName"></div>
                                    <div class="small text-muted text-truncate" id="replyToText" style="max-width: 300px;"></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm text-muted p-0" id="cancelReply">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>

                        {{-- File Preview Strip --}}
                        <div id="filePreviewStrip" class="d-none mb-2 p-2 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex align-items-center gap-2">
                                <div id="filePreviewIcon" class="text-primary" style="font-size: 1.4rem;"></div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div id="filePreviewName" class="text-white small text-truncate"></div>
                                    <div id="filePreviewSize" class="text-muted" style="font-size: 0.7rem;"></div>
                                </div>
                                <button type="button" class="btn btn-sm text-muted p-0" id="clearFile">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                            <div id="imagePreviewWrap" class="d-none mt-2">
                                <img id="imagePreviewThumb" src="" alt="preview" style="max-height: 120px; border-radius: 0.5rem; max-width: 100%;">
                            </div>
                        </div>

                        <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
                            @csrf
                            <input type="hidden" name="reply_to_id" id="replyToIdInput" value="">
                            <input type="file" name="file" id="fileInput" accept="image/*,audio/*,video/*" class="d-none">

                            <div class="input-group shadow-sm">
                                {{-- Paperclip / Attach button --}}
                                <button type="button" class="btn btn-outline-secondary px-3" id="attachBtn" style="border-radius: 0.8rem 0 0 0.8rem; border-right: none;" title="Attach media">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                </button>
                                <input type="text" name="message" id="messageInput" class="form-control bg-dark border-secondary text-white py-3 px-4"
                                       placeholder="Type your message here..." maxlength="5000" style="border-radius: 0; border-left: none;">
                                <button class="btn btn-primary px-4" type="submit" id="sendBtn" style="border-radius: 0 0.8rem 0.8rem 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Context Menu -->
<div id="contextMenu" class="dropdown-menu dropdown-menu-dark shadow" style="display: none; position: absolute; z-index: 1050; min-width: 150px;">
    <a class="dropdown-item" href="#" id="menuReply">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
        Reply
    </a>
    <a class="dropdown-item" href="#" id="menuReact">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
        React
    </a>
    <div class="dropdown-divider border-secondary"></div>
    <a class="dropdown-item" href="#" id="menuSelect">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        Select Message
    </a>
    <a class="dropdown-item text-danger" href="#" id="menuDelete">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        Delete Message
    </a>
</div>

<!-- Chat Background Context Menu -->
<div id="chatContextMenu" class="dropdown-menu dropdown-menu-dark shadow" style="display: none; position: absolute; z-index: 1050; min-width: 180px;">
    <a class="dropdown-item text-danger" href="#" id="menuDeleteThread">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        Delete Entire Thread
    </a>
</div>

<!-- Floating Action Button for Bulk Delete -->
<div id="bulkDeleteContainer" style="display: none; position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); z-index: 1050;">
    <div class="d-flex bg-dark p-2 rounded-pill shadow-lg border border-secondary align-items-center">
        <span class="text-white mx-3"><span id="selectedCount">0</span> selected</span>
        <button class="btn btn-danger rounded-pill px-3 me-2" id="btnBulkDelete">Delete</button>
        <button class="btn btn-outline-light rounded-pill px-3" id="btnCancelSelect">Cancel</button>
    </div>
</div>

<!-- Emoji Reaction Picker -->
<div id="emojiPicker" class="shadow-lg rounded-pill p-2" style="display: none; position: absolute; background: #2b2b2b; z-index: 1060; border: 1px solid rgba(255,255,255,0.1);">
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="👍">👍</button>
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="❤️">❤️</button>
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="😂">😂</button>
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="😮">😮</button>
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="😢">😢</button>
        <button class="btn btn-sm btn-link text-decoration-none emoji-btn fs-5 p-0" data-emoji="🙏">🙏</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chat-messages');
        chatBox.scrollTop = chatBox.scrollHeight;

        // ── File Attachment Logic ──────────────────────────────────────────
        const attachBtn      = document.getElementById('attachBtn');
        const fileInput      = document.getElementById('fileInput');
        const filePreview    = document.getElementById('filePreviewStrip');
        const filePreviewName = document.getElementById('filePreviewName');
        const filePreviewSize = document.getElementById('filePreviewSize');
        const filePreviewIcon = document.getElementById('filePreviewIcon');
        const imagePreviewWrap = document.getElementById('imagePreviewWrap');
        const imagePreviewThumb = document.getElementById('imagePreviewThumb');
        const clearFileBtn   = document.getElementById('clearFile');
        const messageInput   = document.getElementById('messageInput');

        attachBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;
            const file = this.files[0];
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);

            filePreviewName.textContent = file.name;
            filePreviewSize.textContent = sizeMB + ' MB';

            // Icon by type
            if (file.type.startsWith('image/')) {
                filePreviewIcon.textContent = '🖼️';
                const reader = new FileReader();
                reader.onload = e => { imagePreviewThumb.src = e.target.result; };
                reader.readAsDataURL(file);
                imagePreviewWrap.classList.remove('d-none');
            } else if (file.type.startsWith('video/')) {
                filePreviewIcon.textContent = '🎬';
                imagePreviewWrap.classList.add('d-none');
            } else {
                filePreviewIcon.textContent = '🎵';
                imagePreviewWrap.classList.add('d-none');
            }

            filePreview.classList.remove('d-none');
            messageInput.removeAttribute('required');
        });

        clearFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
            imagePreviewWrap.classList.add('d-none');
            imagePreviewThumb.src = '';
        });

        // ── Context Menu & existing logic ─────────────────────────────────
        const contextMenu    = document.getElementById('contextMenu');
        const menuReply      = document.getElementById('menuReply');
        const menuReact      = document.getElementById('menuReact');
        const menuSelect     = document.getElementById('menuSelect');
        const menuDelete     = document.getElementById('menuDelete');
        const bulkDeleteContainer = document.getElementById('bulkDeleteContainer');
        const selectedCount  = document.getElementById('selectedCount');
        const btnBulkDelete  = document.getElementById('btnBulkDelete');
        const btnCancelSelect = document.getElementById('btnCancelSelect');
        const chatContextMenu = document.getElementById('chatContextMenu');
        const menuDeleteThread = document.getElementById('menuDeleteThread');
        const emojiPicker    = document.getElementById('emojiPicker');
        const replyBanner    = document.getElementById('replyBanner');
        const replyToName    = document.getElementById('replyToName');
        const replyToText    = document.getElementById('replyToText');
        const replyToIdInput = document.getElementById('replyToIdInput');
        const cancelReply    = document.getElementById('cancelReply');

        let targetMessageId = null;
        let targetMessageElement = null;
        let targetMessageText = '';
        let targetMessageSender = '';
        let isSelectionMode = false;
        let selectedMessages = new Set();

        document.querySelectorAll('.message-bubble').forEach(bubble => {
            bubble.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                targetMessageId = this.dataset.id;
                targetMessageElement = this;
                targetMessageText = this.dataset.text;
                targetMessageSender = this.dataset.sender;
                contextMenu.style.display = 'block';
                contextMenu.style.left = e.pageX + 'px';
                contextMenu.style.top = e.pageY + 'px';
                emojiPicker.style.display = 'none';
            });
            bubble.addEventListener('click', function(e) {
                if (isSelectionMode) {
                    const id = this.dataset.id;
                    if (selectedMessages.has(id)) {
                        selectedMessages.delete(id);
                        this.classList.remove('border', 'border-danger', 'border-2', 'bg-opacity-50');
                    } else {
                        selectedMessages.add(id);
                        this.classList.add('border', 'border-danger', 'border-2', 'bg-opacity-50');
                    }
                    updateBulkUI();
                }
            });
        });

        chatBox.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.message-bubble')) return;
            e.preventDefault();
            chatContextMenu.style.display = 'block';
            chatContextMenu.style.left = e.pageX + 'px';
            chatContextMenu.style.top = e.pageY + 'px';
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#contextMenu'))    contextMenu.style.display = 'none';
            if (!e.target.closest('#chatContextMenu')) chatContextMenu.style.display = 'none';
            if (!e.target.closest('#emojiPicker') && !e.target.closest('#menuReact')) emojiPicker.style.display = 'none';
        });

        menuReply.addEventListener('click', function(e) {
            e.preventDefault();
            contextMenu.style.display = 'none';
            replyToIdInput.value = targetMessageId;
            replyToName.textContent = targetMessageSender;
            replyToText.textContent = targetMessageText;
            replyBanner.classList.remove('d-none');
            replyBanner.classList.add('d-flex');
            messageInput.focus();
        });

        cancelReply.addEventListener('click', function() {
            replyToIdInput.value = '';
            replyBanner.classList.add('d-none');
            replyBanner.classList.remove('d-flex');
        });

        menuReact.addEventListener('click', function(e) {
            e.preventDefault();
            contextMenu.style.display = 'none';
            const rect = targetMessageElement.getBoundingClientRect();
            emojiPicker.style.display = 'block';
            emojiPicker.style.left = (rect.left + window.scrollX) + 'px';
            emojiPicker.style.top  = (rect.bottom + window.scrollY + 5) + 'px';
        });

        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const emoji = this.dataset.emoji;
                emojiPicker.style.display = 'none';
                if (targetMessageId) {
                    fetch(`/messages/${targetMessageId}/react`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ reaction: emoji })
                    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
                }
            });
        });

        menuSelect.addEventListener('click', function(e) {
            e.preventDefault();
            contextMenu.style.display = 'none';
            isSelectionMode = true;
            if (targetMessageId) {
                selectedMessages.add(targetMessageId);
                targetMessageElement.classList.add('border', 'border-danger', 'border-2', 'bg-opacity-50');
            }
            updateBulkUI();
        });

        menuDelete.addEventListener('click', function(e) {
            e.preventDefault();
            contextMenu.style.display = 'none';
            if(confirm('Delete this message?')) deleteMessages([targetMessageId]);
        });

        menuDeleteThread.addEventListener('click', function(e) {
            e.preventDefault();
            chatContextMenu.style.display = 'none';
            if(confirm('Are you sure you want to permanently delete this entire conversation?')) {
                fetch('{{ route('messages.thread_destroy') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ user_id: '{{ $admin->id }}' })
                }).then(r => r.json()).then(d => { if(d.success) location.reload(); else alert('Error deleting conversation.'); }).catch(() => alert('Network error.'));
            }
        });

        btnCancelSelect.addEventListener('click', function() {
            isSelectionMode = false;
            selectedMessages.clear();
            document.querySelectorAll('.message-bubble').forEach(b => b.classList.remove('border','border-danger','border-2','bg-opacity-50'));
            updateBulkUI();
        });

        btnBulkDelete.addEventListener('click', function() {
            if(selectedMessages.size > 0 && confirm(`Delete ${selectedMessages.size} selected messages?`)) deleteMessages(Array.from(selectedMessages));
        });

        function updateBulkUI() {
            bulkDeleteContainer.style.display = isSelectionMode ? 'block' : 'none';
            selectedCount.textContent = selectedMessages.size;
        }

        function deleteMessages(ids) {
            fetch('{{ route('messages.bulk_destroy') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ message_ids: ids })
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); else alert('Error deleting messages.'); }).catch(() => alert('Network error.'));
        }
    });
</script>

<style>
    .max-w-75 { max-width: 75%; }
    #chat-messages::-webkit-scrollbar { width: 6px; }
    #chat-messages::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #chat-messages::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    #attachBtn:hover { color: #0d6efd !important; border-color: #0d6efd !important; }
</style>
@endsection
