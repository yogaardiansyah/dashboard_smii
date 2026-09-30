@extends('errors.minimal')

@section('title', __('404 - Halaman Tidak Ditemukan'))
@section('code', '404')
@section('badge_text', 'ERROR 404 • NOT FOUND')
@section('message', __('Halaman Tidak Ditemukan'))
@section('message2', __('Maaf, halaman yang Anda cari tidak dapat ditemukan, telah dihapus, atau URL yang dimasukkan salah.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Animated Outer Ring -->
    <circle cx="100" cy="100" r="85" stroke="rgba(192, 160, 31, 0.2)" stroke-width="2" stroke-dasharray="6 6" class="spin-gear" />
    
    <!-- Radar Base Background -->
    <circle cx="90" cy="90" r="55" fill="rgba(192, 160, 31, 0.08)" stroke="#c0a01f" stroke-width="3" class="pulse-element" />
    
    <!-- Floating Magnifying Glass & 404 Icon -->
    <g transform="translate(5, 5)">
        <!-- Glass Rim -->
        <circle cx="85" cy="85" r="35" stroke="#ffffff" stroke-width="6" fill="rgba(15, 23, 42, 0.6)" />
        <circle cx="85" cy="85" r="28" stroke="#c0a01f" stroke-width="2" stroke-dasharray="4 4" class="spin-gear-reverse" />
        <!-- Handle -->
        <path d="M110 110 L145 145" stroke="#c0a01f" stroke-width="10" stroke-linecap="round" />
        <path d="M110 110 L145 145" stroke="#ffffff" stroke-width="4" stroke-linecap="round" />
        <!-- Question Mark / Search Glow inside Glass -->
        <text x="85" y="95" font-family="sans-serif" font-weight="900" font-size="28" fill="#c0a01f" text-anchor="middle">?</text>
    </g>
    
    <!-- Sparkles -->
    <circle cx="150" cy="50" r="3" fill="#c0a01f" class="pulse-element" />
    <circle cx="40" cy="140" r="4" fill="#ffffff" class="pulse-element" />
</svg>
@endsection
