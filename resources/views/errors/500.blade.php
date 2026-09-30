@extends('errors.minimal')

@section('title', __('500 - Server Error'))
@section('code', '500')
@section('badge_text', 'ERROR 500 • SERVER ERROR')
@section('message', __('Gangguan Sistem Internal'))
@section('message2', __('Terjadi kesalahan internal pada server kami. Tim sedang memproses masalah ini, silakan muat ulang atau coba beberapa saat lagi.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Server Glow Circle -->
    <circle cx="100" cy="100" r="75" fill="rgba(239, 68, 68, 0.1)" stroke="rgba(239, 68, 68, 0.3)" stroke-width="2" class="pulse-element" />

    <!-- Big Rotating Gear -->
    <g class="spin-gear" transform-origin="85 85">
        <circle cx="85" cy="85" r="38" stroke="#c0a01f" stroke-width="6" stroke-dasharray="12 6" fill="rgba(15, 23, 42, 0.8)" />
        <circle cx="85" cy="85" r="20" stroke="#ffffff" stroke-width="3" />
    </g>

    <!-- Small Counter-Rotating Gear -->
    <g class="spin-gear-reverse" transform-origin="135 125">
        <circle cx="135" cy="125" r="26" stroke="#ffffff" stroke-width="5" stroke-dasharray="10 5" fill="rgba(192, 160, 31, 0.2)" />
        <circle cx="135" cy="125" r="12" fill="#c0a01f" />
    </g>

    <!-- Warning Lightning / Alert Triangle -->
    <path d="M100 45 L120 80 L80 80 Z" fill="#ef4444" stroke="#ffffff" stroke-width="2" class="pulse-element" />
    <text x="100" y="75" font-family="sans-serif" font-weight="900" font-size="20" fill="#ffffff" text-anchor="middle">!</text>
</svg>
@endsection
