@extends('errors.minimal')

@section('title', __('503 - Pemeliharaan Sistem'))
@section('code', '503')
@section('badge_text', 'ERROR 503 • MAINTENANCE')
@section('message', __('Sistem Dalam Pemeliharaan'))
@section('message2', __('Sistem Intra SMII sedang dalam pemeliharaan rutin untuk peningkatan kualitas layanan. Kami akan segera kembali.'))

@section('illustration')
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Outer Construction Circle -->
    <circle cx="100" cy="100" r="70" stroke="#c0a01f" stroke-width="4" stroke-dasharray="14 8" class="spin-gear" />
    
    <!-- Crossed Wrench & Screwdriver -->
    <g class="swing-element">
        <!-- Wrench 1 -->
        <path d="M60 140 L125 75 M120 70 L140 50 C145 45 135 35 130 40 L110 60 L120 70 Z" 
              stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
        <!-- Wrench 2 -->
        <path d="M140 140 L75 75 M70 70 L50 50 C45 45 35 55 40 60 L60 80 L70 70 Z" 
              stroke="#c0a01f" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
    </g>
    
    <circle cx="100" cy="100" r="10" fill="#0b0f19" stroke="#c0a01f" stroke-width="3" />
</svg>
@endsection
