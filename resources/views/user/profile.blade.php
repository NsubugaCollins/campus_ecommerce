@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark border-secondary shadow-sm overflow-hidden" style="position:relative;">
                <div style="position:absolute;top:0;right:0;width:200px;height:100%;background:linear-gradient(90deg,transparent,rgba(220,20,60,0.12));"></div>
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-4 profile-avatar"
                         style="width:80px;height:80px;font-size:2rem;font-weight:700;box-shadow:0 0 24px rgba(184,115,51,.35);">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div>
                        <h2 class="text-white fw-bold mb-1">{{ $user->name }}</h2>
                        <p class="text-white-50 mb-0 small">{{ $user->email }}</p>
                        <span class="badge mt-1" style="background:linear-gradient(135deg,#DC143C,#B87333);font-size:.7rem;letter-spacing:1px;">
                            {{ ucfirst($user->role ?? 'Customer') }}
                        </span>
                    </div>
                    <div class="ms-auto d-none d-md-flex gap-4 text-center">
                        <div>
                            <div class="text-white fw-bold fs-4">{{ $totalOrders }}</div>
                            <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;">Orders</div>
                        </div>
                        <div class="border-start border-secondary ps-4">
                            <div class="text-warning fw-bold fs-4">{{ $user->points }}</div>
                            <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;">Points</div>
                        </div>
                        <div class="border-start border-secondary ps-4">
                            <div class="text-white fw-bold fs-4">UGX {{ number_format($totalSpent,0) }}</div>
                            <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;">Spent</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs border-secondary mb-4" id="profileTabs">
        <li class="nav-item">
            <button class="nav-link active text-white" data-bs-toggle="tab" data-bs-target="#tab-profile" id="btn-tab-profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Profile
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white-50" data-bs-toggle="tab" data-bs-target="#tab-security" id="btn-tab-security">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Security
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white-50" data-bs-toggle="tab" data-bs-target="#tab-rewards" id="btn-tab-rewards">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Rewards
            </button>
        </li>
    </ul>

    <div class="tab-content" id="profileTabsContent">

        {{-- ===== TAB: Profile ===== --}}
        <div class="tab-pane fade show active" id="tab-profile">
            @if(session('profile_success'))
                <div class="alert alert-success alert-dismissible fade show bg-success border-0 text-white mb-4" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('profile_success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card bg-dark border-secondary shadow-sm">
                        <div class="card-header bg-black border-secondary py-3">
                            <h5 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">Personal Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Full Name</label>
                                        <input type="text" name="name"
                                               class="form-control bg-black border-secondary text-white @error('name') is-invalid @enderror"
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Email Address</label>
                                        <input type="email" name="email"
                                               class="form-control bg-black border-secondary text-white @error('email') is-invalid @enderror"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12 text-end mt-2">
                                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="letter-spacing:1px;">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Account Info Card --}}
                    <div class="card bg-dark border-secondary shadow-sm mb-4">
                        <div class="card-header bg-black border-secondary py-3">
                            <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">Account Info</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush bg-transparent">
                                <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center py-3 px-4">
                                    <span class="text-white-50 small">Member Since</span>
                                    <span class="text-white small fw-bold">{{ $user->created_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center py-3 px-4">
                                    <span class="text-white-50 small">Total Orders</span>
                                    <span class="text-white small fw-bold">{{ $totalOrders }}</span>
                                </li>
                                <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center py-3 px-4">
                                    <span class="text-white-50 small">Completed</span>
                                    <span class="text-white small fw-bold">{{ $completedOrders }}</span>
                                </li>
                                <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center py-3 px-4">
                                    <span class="text-white-50 small">Total Spent</span>
                                    <span class="text-warning small fw-bold">UGX {{ number_format($totalSpent, 2) }}</span>
                                </li>
                                <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center py-3 px-4">
                                    <span class="text-white-50 small">Reward Points</span>
                                    <span class="text-warning small fw-bold">{{ $user->points }} pts</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Quick Nav --}}
                    <div class="card bg-dark border-secondary shadow-sm">
                        <div class="card-body p-0">

                            <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action bg-transparent text-white border-secondary py-3 px-4 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2 text-info"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                My Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TAB: Security ===== --}}
        <div class="tab-pane fade" id="tab-security">
            @if(session('security_success'))
                <div class="alert alert-success alert-dismissible fade show bg-success border-0 text-white mb-4" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('security_success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card bg-dark border-secondary shadow-sm">
                        <div class="card-header bg-black border-secondary py-3">
                            <h5 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">Change Password</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('user.profile.password') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="currentPwd"
                                                   class="form-control bg-black border-secondary text-white @error('current_password') is-invalid @enderror"
                                                   placeholder="Enter current password">
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="currentPwd">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                        @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPwd"
                                                   class="form-control bg-black border-secondary text-white @error('password') is-invalid @enderror"
                                                   placeholder="Min. 8 characters">
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="newPwd">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirmPwd"
                                                   class="form-control bg-black border-secondary text-white"
                                                   placeholder="Repeat new password">
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="confirmPwd">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end mt-2">
                                        <button type="submit" class="btn btn-danger px-5 py-2 fw-bold" style="letter-spacing:1px;">
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-dark border-secondary shadow-sm">
                        <div class="card-header bg-black border-secondary py-3">
                            <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">Password Tips</h6>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                @foreach(['At least 8 characters', 'Mix of uppercase & lowercase', 'Include numbers', 'Include special characters (!@#$...)'] as $tip)
                                <li class="d-flex align-items-start mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#B87333" stroke-width="2" viewBox="0 0 24 24" class="me-2 flex-shrink-0 mt-1"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span class="text-white-50 small">{{ $tip }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TAB: Rewards ===== --}}
        <div class="tab-pane fade" id="tab-rewards">
            <div class="row g-4 mb-4">

                {{-- Points Balance --}}
                <div class="col-md-4">
                    <div class="card border-0 text-white shadow-sm h-100" style="background:linear-gradient(135deg,#DC143C,#B87333);">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3 opacity-75 mx-auto"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <div class="display-4 fw-bold">{{ $user->points }}</div>
                            <div class="small opacity-75 text-uppercase mt-1" style="letter-spacing:2px;">Reward Points</div>
                            <hr style="border-color:rgba(255,255,255,.3);">
                            <p class="small mb-1 opacity-90">Worth <strong>UGX {{ number_format($user->points * 10, 2) }}</strong> in discounts</p>
                        </div>
                    </div>
                </div>

                {{-- Referral Stats --}}
                <div class="col-md-4">
                    <div class="card bg-dark border-secondary shadow-sm h-100">
                        <div class="card-header bg-black border-secondary py-3">
                            <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">Referral Stats</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 text-center">
                                <div class="col-6">
                                    <div class="p-3 bg-black rounded border border-secondary">
                                        <div class="text-white fw-bold fs-3">{{ $referrals->count() }}</div>
                                        <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;">Friends Referred</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-black rounded border border-secondary">
                                        <div class="text-warning fw-bold fs-3">{{ $referralPoints }}</div>
                                        <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;">Points Earned</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-secondary opacity-25">
                            <p class="text-white-50 small mb-0 text-center">
                                <strong class="text-white">100 pts</strong> = UGX 1,000 discount at checkout
                            </p>
                        </div>
                    </div>
                </div>

                {{-- How to Earn --}}
                <div class="col-md-4">
                    <div class="card bg-dark border-secondary shadow-sm h-100">
                        <div class="card-header bg-black border-secondary py-3">
                            <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">How to Earn</h6>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $ways = [
                                    ['label'=>'Refer a Friend',  'pts'=>'+50 pts', 'color'=>'text-primary',  'bg'=>'rgba(13,110,253,.15)'],
                                    ['label'=>'Every UGX 1 Spent',  'pts'=>'+10 pts', 'color'=>'text-success',  'bg'=>'rgba(25,135,84,.15)'],
                                    ['label'=>'Daily Login',      'pts'=>'+5 pts',  'color'=>'text-info',     'bg'=>'rgba(13,202,240,.15)'],
                                ]
                            @endphp
                            @foreach($ways as $w)
                            <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded" style="background:{{ $w['bg'] }}">
                                <span class="text-white small fw-semibold">{{ $w['label'] }}</span>
                                <span class="badge bg-black border border-secondary {{ $w['color'] }} fw-bold">{{ $w['pts'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shareable Referral Link --}}
            <div class="card bg-dark border-secondary shadow-sm mb-4">
                <div class="card-header bg-black border-secondary py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#B87333" stroke-width="2" viewBox="0 0 24 24" class="me-2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Share Your Referral Link
                    </h6>
                    <span class="badge text-bg-warning">Earn 50 pts per signup</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-white-50 small mb-3">
                        Share this link directly — your referral code is already embedded. Anyone who registers through it gets <strong class="text-white">20 bonus points</strong> and you earn <strong class="text-white">50 points</strong>!
                    </p>
                    <div class="row g-3">
                        {{-- Full URL --}}
                        <div class="col-12">
                            <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Shareable Link</label>
                            <div class="input-group">
                                <span class="input-group-text bg-black border-secondary text-white-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                </span>
                                <input type="text" id="shareableLink"
                                       class="form-control bg-black border-secondary text-white"
                                       value="{{ url('/register?ref=' . $user->referral_code) }}"
                                       readonly>
                                <button class="btn btn-primary px-4 fw-bold" type="button" id="copyLinkBtn" onclick="copyLink()">
                                    Copy Link
                                </button>
                            </div>
                        </div>

                        {{-- Code only --}}
                        <div class="col-md-4">
                            <label class="form-label text-white-50 small text-uppercase" style="letter-spacing:1px;">Code Only</label>
                            <div class="input-group">
                                <input type="text" id="refCode"
                                       class="form-control bg-black border-secondary text-white text-center fw-bold"
                                       value="{{ $user->referral_code }}" readonly style="letter-spacing:3px;">
                                <button class="btn btn-outline-secondary" type="button" id="copyCodeBtn" onclick="copyCode()">Copy</button>
                            </div>
                        </div>

                        {{-- Social share buttons --}}
                        <div class="col-md-8 d-flex align-items-end gap-2 flex-wrap">
                            @php $shareUrl = urlencode(url('/register?ref=' . $user->referral_code)); $shareMsg = urlencode('Join Campus Mall using my referral link and get 20 bonus reward points! 🎁'); @endphp
                            <a href="https://wa.me/?text={{ $shareMsg }}%20{{ $shareUrl }}" target="_blank"
                               class="btn btn-sm fw-bold px-3" style="background:#25D366;color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="me-1"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.099.544 4.07 1.497 5.785L0 24l6.374-1.673A11.944 11.944 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.844 0-3.576-.492-5.072-1.352l-.364-.215-3.782.992.998-3.7-.237-.381A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                                WhatsApp
                            </a>
                            <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareMsg }}" target="_blank"
                               class="btn btn-sm fw-bold px-3" style="background:#229ED9;color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="me-1"><path d="M11.944 0A12 12 0 1 0 24 12 12 12 0 0 0 12 0zM5.47 12.19l6.8-2.86c2.84-1.18 3.42-1.39 3.8-1.39.09 0 .28.02.4.13a.47.47 0 0 1 .15.34c-.01.1-.03.29-.05.45l-1.15 5.43c-.17.76-.62.95-1.02.95-.43 0-.6-.28-1.27-.77l-1.77-1.29c-.54-.39-.19-.6.12-.95l3.11-3.01c.13-.13-.03-.2-.2-.08L7.7 14.08c-.57.36-1.17.54-1.75.52-.62-.02-1.36-.25-2.07-.54l-1.1-.42c-.66-.27-.67-.81.14-1.45z"/></svg>
                                Telegram
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareMsg }}&url={{ $shareUrl }}" target="_blank"
                               class="btn btn-sm fw-bold px-3" style="background:#1DA1F2;color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="me-1"><path d="M23.953 4.57a10 10 0 0 1-2.825.775 4.958 4.958 0 0 0 2.163-2.723 9.99 9.99 0 0 1-3.127 1.195 4.92 4.92 0 0 0-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 0 0-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 0 1-2.228-.616v.06a4.923 4.923 0 0 0 3.946 4.827 4.996 4.996 0 0 1-2.212.085 4.936 4.936 0 0 0 4.604 3.417 9.867 9.867 0 0 1-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0 0 7.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0 0 24 4.59z"/></svg>
                                Twitter / X
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Referral History --}}
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-header bg-black border-secondary py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing:1px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#B87333" stroke-width="2" viewBox="0 0 24 24" class="me-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Referral History
                    </h6>
                    <span class="badge bg-secondary text-white">{{ $referrals->count() }} {{ Str::plural('friend', $referrals->count()) }}</span>
                </div>
                <div class="card-body p-0">
                    @if($referrals->isEmpty())
                        <div class="text-center py-5 px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" class="text-secondary mb-3"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <h6 class="text-white-50 mb-1">No referrals yet</h6>
                            <p class="text-white-50 small mb-3">Share your link above and start earning points when friends sign up!</p>
                            <button class="btn btn-sm btn-primary px-4" onclick="copyLink()">Copy My Link</button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0">
                                <thead>
                                    <tr class="border-secondary">
                                        <th class="px-4 py-3 text-white-50 text-uppercase fw-normal small" style="letter-spacing:1px;">#</th>
                                        <th class="py-3 text-white-50 text-uppercase fw-normal small" style="letter-spacing:1px;">Friend</th>
                                        <th class="py-3 text-white-50 text-uppercase fw-normal small" style="letter-spacing:1px;">Joined</th>
                                        <th class="py-3 text-end pe-4 text-white-50 text-uppercase fw-normal small" style="letter-spacing:1px;">Points Earned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referrals as $i => $referred)
                                    <tr class="border-secondary referral-row" style="animation-delay:{{ $i * 0.05 }}s">
                                        <td class="px-4 py-3 text-white-50 small">{{ $i + 1 }}</td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0"
                                                     style="width:36px;height:36px;font-size:.9rem;background:linear-gradient(135deg,#DC143C,#B87333);">
                                                    {{ strtoupper(substr($referred->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-white small fw-semibold">{{ $referred->name }}</div>
                                                    <div class="text-white-50" style="font-size:.75rem;">{{ Str::mask($referred->email, '*', 3, strlen($referred->email) - 7) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-white small">{{ $referred->created_at->format('M d, Y') }}</div>
                                            <div class="text-white-50" style="font-size:.75rem;">{{ $referred->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="py-3 text-end pe-4">
                                            <span class="badge px-3 py-2 fw-bold" style="background:rgba(255,193,7,.15);color:#ffc107;font-size:.8rem;">
                                                +50 pts
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-secondary border-top">
                                        <td colspan="3" class="px-4 py-3 text-white-50 small fw-bold text-uppercase" style="letter-spacing:1px;">Total Referral Points</td>
                                        <td class="py-3 text-end pe-4">
                                            <span class="text-warning fw-bold fs-6">+{{ $referralPoints }} pts</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- end tab-content --}}
</div>

<style>
.nav-tabs { border-bottom: 1px solid #343a40; }
.nav-tabs .nav-link { border: none; border-bottom: 3px solid transparent; border-radius: 0; padding: .75rem 1.25rem; transition: all .2s; }
.nav-tabs .nav-link.active { color: #fff !important; border-bottom-color: #DC143C; background: transparent; }
.nav-tabs .nav-link:hover { color: #fff !important; border-bottom-color: rgba(220,20,60,.4); }
.profile-avatar { transition: transform .3s; }
.profile-avatar:hover { transform: scale(1.05); }
.toggle-pwd { color: #6c757d; border-color: #495057; background: #000; }
.toggle-pwd:hover { color: #fff; background: #343a40; }
.form-control:focus { border-color: #DC143C !important; box-shadow: 0 0 0 .2rem rgba(220,20,60,.15) !important; }
@keyframes fadeInUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
.referral-row { animation: fadeInUp .35s ease both; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('open_tab'))
        const tab = document.querySelector('#btn-tab-{{ session("open_tab") }}');
        if (tab) { tab.click(); }
    @endif

    document.querySelectorAll('.toggle-pwd').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
});

function flashBtn(btn, label, cls) {
    const orig = btn.innerText;
    const origCls = btn.className;
    btn.innerText = label;
    btn.classList.add(cls);
    setTimeout(() => { btn.innerText = orig; btn.className = origCls; }, 2000);
}

function copyLink() {
    const el = document.getElementById('shareableLink');
    el.select();
    navigator.clipboard.writeText(el.value).then(() => {
        flashBtn(document.getElementById('copyLinkBtn'), '✓ Copied!', 'btn-success');
    });
}

function copyCode() {
    const el = document.getElementById('refCode');
    el.select();
    navigator.clipboard.writeText(el.value).then(() => {
        flashBtn(document.getElementById('copyCodeBtn'), '✓ Done', 'btn-success');
    });
}
</script>
@endsection
