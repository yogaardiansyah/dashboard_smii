@extends('errors.minimal')

@section('title', __('429 - Batas Akses Terlampaui'))
@section('code', '429')
@section('badge_text', 'ERROR 429 • TOO MANY REQUESTS')
@section('message', __('Permintaan Terlalu Banyak'))
@section('message2', __('Anda melakukan terlalu banyak permintaan dalam waktu singkat. Harap tunggu sebentar sebelum mencoba kembali.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="100" cy="100" r="70" stroke="#c0a01f" stroke-width="4" stroke-dasharray="10 5" class="spin-gear" />
    <path d="M50 125 A60 60 0 1 1 150 125" stroke="rgba(255, 255, 255, 0.2)" stroke-width="8" stroke-linecap="round" fill="none" />
    <path d="M50 125 A60 60 0 0 1 135 70" stroke="#ef4444" stroke-width="8" stroke-linecap="round" fill="none" class="pulse-element" />
    <!-- Gauge Needle -->
    <line x1="100" y1="115" x2="135" y2="70" stroke="#ffffff" stroke-width="5" stroke-linecap="round" />
    <circle cx="100" cy="115" r="8" fill="#c0a01f" />
</svg>
@endsection
