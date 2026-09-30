@extends('errors.minimal')

@section('title', __('419 - Sesi Berakhir'))
@section('code', '419')
@section('badge_text', 'ERROR 419 • SESSION EXPIRED')
@section('message', __('Sesi Halaman Berakhir'))
@section('message2', __('Masa berlaku formulir atau sesi keamanan Anda telah habis. Silakan muat ulang halaman ini atau login kembali.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Clock Rim -->
    <circle cx="100" cy="100" r="70" stroke="rgba(192, 160, 31, 0.3)" stroke-width="2" stroke-dasharray="8 8" class="spin-gear" />
    <circle cx="100" cy="100" r="55" fill="rgba(15, 23, 42, 0.7)" stroke="#c0a01f" stroke-width="4" />
    
    <!-- Clock Hands (Rotating Hour Hand, Fast Minute Hand) -->
    <line x1="100" y1="100" x2="100" y2="65" stroke="#ffffff" stroke-width="4" stroke-linecap="round" />
    <line x1="100" y1="100" x2="125" y2="100" stroke="#c0a01f" stroke-width="4" stroke-linecap="round" class="spin-gear-reverse" transform-origin="100 100" />
    <circle cx="100" cy="100" r="7" fill="#ffffff" />
    
    <!-- Refresh Arrows Loop around clock -->
    <path d="M145 75 A65 65 0 0 1 155 120" stroke="#c0a01f" stroke-width="4" stroke-linecap="round" fill="none" class="pulse-element" />
    <polygon points="155,125 165,115 145,115" fill="#c0a01f" />
</svg>
@endsection

@section('actions')
<a href="{{ route('login') }}" class="error-btn error-btn-primary">
    <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v3H3v4h7v3zm9-14H9c-1.1 0-2 .9-2 2v3h2V5h10v14H9v-3H7v3c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
    <span>{{ __('Login Ulang') }}</span>
</a>
<button onclick="window.location.reload()" class="error-btn error-btn-secondary">
    <svg viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
    <span>{{ __('Muat Ulang') }}</span>
</button>
<a href="{{ route('dashboard') }}" class="error-btn error-btn-secondary">
    <span>{{ __('Dashboard') }}</span>
</a>
@endsection
