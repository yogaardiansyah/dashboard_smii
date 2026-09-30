@extends('errors.minimal')

@section('title', __('403 - Akses Ditolak'))
@section('code', '403')
@section('badge_text', 'ERROR 403 • FORBIDDEN')
@section('message', __('Akses Tidak Diizinkan'))
@section('message2', __('Maaf, akun Anda tidak memiliki hak akses untuk membuka halaman ini. Silakan hubungi Administrator sistem.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Glowing Outer Shield Base -->
    <path d="M100 25 L160 55 V105 C160 145 100 175 100 175 C100 175 40 145 40 105 V55 L100 25 Z" 
          fill="rgba(192, 160, 31, 0.08)" stroke="#c0a01f" stroke-width="3" class="pulse-element" />

    <!-- Lock Element -->
    <g class="swing-element">
        <!-- Lock Shackle -->
        <path d="M80 85 V65 C80 54 89 45 100 45 C111 45 120 54 120 65 V85" 
              stroke="#ffffff" stroke-width="6" stroke-linecap="round" fill="none" />
        <!-- Lock Body -->
        <rect x="70" y="85" width="60" height="50" rx="10" fill="#c0a01f" stroke="#ffffff" stroke-width="3" />
        <!-- Keyhole -->
        <circle cx="100" cy="105" r="5" fill="#0b0f19" />
        <path d="M100 108 L100 120" stroke="#0b0f19" stroke-width="4" stroke-linecap="round" />
    </g>
</svg>
@endsection
