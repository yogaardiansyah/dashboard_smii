@extends('errors.minimal')

@section('title', __('401 - Otentikasi Diperlukan'))
@section('code', '401')
@section('badge_text', 'ERROR 401 • UNAUTHORIZED')
@section('message', __('Otentikasi Diperlukan'))
@section('message2', __('Anda harus masuk (login) terlebih dahulu untuk mengakses halaman atau sumber daya ini.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="100" cy="100" r="70" stroke="rgba(192, 160, 31, 0.2)" stroke-width="2" class="pulse-element" />
    <g class="swing-element">
        <circle cx="100" cy="80" r="28" stroke="#c0a01f" stroke-width="5" fill="none" />
        <path d="M60 145 C60 115 80 105 100 105 C120 105 140 115 140 145" stroke="#ffffff" stroke-width="5" stroke-linecap="round" fill="none" />
        <path d="M120 70 L140 50 M135 55 L145 65" stroke="#c0a01f" stroke-width="4" stroke-linecap="round" />
    </g>
</svg>
@endsection

@section('actions')
<a href="{{ route('login') }}" class="error-btn error-btn-primary">
    <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v3H3v4h7v3zm9-14H9c-1.1 0-2 .9-2 2v3h2V5h10v14H9v-3H7v3c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
    <span>{{ __('Halaman Login') }}</span>
</a>
<a href="{{ route('dashboard') }}" class="error-btn error-btn-secondary">
    <span>{{ __('Dashboard') }}</span>
</a>
@endsection
