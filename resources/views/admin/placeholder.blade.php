@extends('layouts.admin')

@section('title', $section)

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center h-100 py-5">
    <div class="text-center p-5 rounded-4 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.05); max-width: 500px;">
        <div class="mb-4 text-primary">
            <!-- Wrench SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        <h3 class="text-white mb-3">{{ $section }}</h3>
        <p class="text-secondary mb-4">The {{ $section }} module is currently under development. Please check back later.</p>

    </div>
</div>
@endsection
