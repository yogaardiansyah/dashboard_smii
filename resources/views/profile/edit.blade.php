<x-app-layout>
    @section('title', 'Profile')

    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
        @include('layouts.partials.roleuser_styles')
        <style>
            /* Highest z-index to overlay everything including sidebars and headers */
            #crop-modal {
                z-index: 999999 !important;
            }

            /* Container wrapper for Cropper.js */
            .cropper-wrapper-container {
                height: 380px;
                max-height: 50vh;
                width: 100%;
                position: relative;
                overflow: hidden;
                border-radius: 1rem;
                background-color: #090d16;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #crop-image-element {
                max-width: 100%;
                max-height: 100%;
                display: block;
            }

            /* Custom CSS to replace Tailwind */
            .profile-container {
                max-width: 100%;
                margin: 0;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            .profile-header-card {
                background: #ffffff;
                border-radius: 24px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                position: relative;
            }

            .profile-banner {
                height: 140px;
                width: 100%;
                position: relative;
                transition: all 0.3s ease;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            @media (min-width: 640px) {
                .profile-banner {
                    height: 190px;
                }
            }

            @media (min-width: 1024px) {
                .profile-banner {
                    height: 250px;
                }
            }

            .change-cover-btn {
                position: absolute;
                top: 12px;
                right: 12px;
                background-color: rgba(0, 0, 0, 0.55);
                color: #ffffff;
                font-size: 10px;
                font-weight: 800;
                padding: 6px 12px;
                border-radius: 10px;
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 6px;
                transition: all 0.2s;
            }

            @media (min-width: 640px) {
                .change-cover-btn {
                    top: 16px;
                    right: 16px;
                    font-size: 11px;
                    padding: 8px 16px;
                    border-radius: 12px;
                }
            }

            .change-cover-btn:hover {
                background-color: rgba(0, 0, 0, 0.8);
                transform: translateY(-1px);
            }

            .profile-info-section {
                padding: 16px 20px 24px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                position: relative;
            }

            @media (min-width: 768px) {
                .profile-info-section {
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 16px 40px 32px 40px;
                    gap: 24px;
                }
            }

            .profile-avatar-container {
                position: relative;
                margin-top: -65px;
                z-index: 20;
            }

            @media (min-width: 640px) {
                .profile-avatar-container {
                    margin-top: -85px;
                }
            }

            @media (min-width: 1024px) {
                .profile-avatar-container {
                    margin-top: -100px;
                }
            }

            .profile-avatar-wrapper {
                position: relative;
                width: 130px;
                height: 175px;
                border-radius: 20px;
                border: 4px solid #ffffff;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
                background-color: #f1f5f9;
                overflow: hidden;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                flex-shrink: 0;
            }

            @media (min-width: 640px) {
                .profile-avatar-wrapper {
                    width: 155px;
                    height: 215px;
                    border-radius: 24px;
                    border-width: 5px;
                }
            }

            @media (min-width: 1024px) {
                .profile-avatar-wrapper {
                    width: 180px;
                    height: 270px;
                    border-radius: 28px;
                    border-width: 6px;
                }
            }

            .profile-avatar-wrapper:hover {
                transform: scale(1.02);
            }

            .profile-avatar-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 22px;
                object-position: top center;
            }

            .profile-avatar-overlay {
                position: absolute;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                opacity: 0;
                transition: opacity 0.2s ease;
                border-radius: 22px;
            }

            .profile-avatar-wrapper:hover .profile-avatar-overlay {
                opacity: 1;
            }

            .avatar-overlay-text {
                font-size: 11px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-top: 8px;
            }

            .profile-details {
                flex: 1;
                text-align: center;
                display: flex;
                flex-direction: column;
                gap: 12px;
                padding-top: 12px;
            }

            @media (min-width: 768px) {
                .profile-details {
                    text-align: left;
                }
            }

            .profile-name-row {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }

            @media (min-width: 768px) {
                .profile-name-row {
                    flex-direction: row;
                    justify-content: flex-start;
                }
            }

            .profile-name {
                font-size: 28px;
                font-weight: 900;
                color: #0f172a !important;
                margin: 0;
                letter-spacing: -0.02em;
            }

            .profile-roles-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 6px;
            }

            @media (min-width: 768px) {
                .profile-roles-container {
                    justify-content: flex-start;
                }
            }

            .role-badge {
                background-color: #3b82f6;
                color: #ffffff;
                font-weight: 800;
                font-size: 11px;
                padding: 4px 10px;
                border-radius: 6px;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                letter-spacing: 0.05em;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .profile-meta-row {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 12px;
                font-size: 13px;
                color: #64748b;
                font-weight: 600;
            }

            @media (min-width: 768px) {
                .profile-meta-row {
                    justify-content: flex-start;
                }
            }

            .profile-meta-value {
                color: #1e293b;
                font-weight: 700;
            }

            .profile-meta-separator {
                display: none;
                color: #cbd5e1;
            }

            @media (min-width: 640px) {
                .profile-meta-separator {
                    display: inline;
                }
            }

            .profile-badges-row {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
                padding-top: 8px;
                font-size: 12px;
                font-weight: 700;
                color: #475569;
            }

            @media (min-width: 768px) {
                .profile-badges-row {
                    justify-content: flex-start;
                }
            }

            .meta-badge {
                display: flex;
                align-items: center;
                gap: 6px;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                padding: 8px 14px;
                border-radius: 12px;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .meta-badge i.fa-envelope { color: #ef4444; }
            .meta-badge i.fa-briefcase { color: #6366f1; }
            .meta-badge i.fa-building { color: #10b981; }
            .meta-badge i.fa-user-tie { color: #f59e0b; }

            .profile-forms-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 24px;
                padding-top: 8px;
            }

            @media (min-width: 1024px) {
                .profile-forms-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .profile-form-card {
                background: #ffffff;
                padding: 32px;
                border-radius: 24px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            }

            /* Form beautification */
            .modern-input {
                width: 100%;
                padding: 12px 16px;
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                background-color: #f8fafc;
                font-size: 14px;
                color: #1e293b;
                transition: all 0.2s ease;
                box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
            }

            .modern-input:focus {
                outline: none;
                border-color: #3b82f6;
                background-color: #ffffff;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            }

            .modern-input:disabled, .modern-input[readonly] {
                background-color: #e2e8f0;
                color: #64748b;
                cursor: not-allowed;
            }

            .modern-label {
                display: block;
                font-size: 13px;
                font-weight: 700;
                color: #334155;
                margin-bottom: 6px;
            }

            .modern-btn {
                background-color: #0f172a;
                color: white;
                font-weight: 600;
                font-size: 14px;
                padding: 12px 24px;
                border-radius: 12px;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            }

            .modern-btn:hover {
                background-color: #1e293b;
                transform: translateY(-1px);
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
            }

            .modern-btn:active {
                transform: translateY(0);
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            }

            .form-group-spacing {
                margin-bottom: 20px;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4 profile-container">
        
        <!-- Main Profile Header Card -->
        <div class="profile-header-card">
            <!-- Decorative Gradient/Image Background Cover -->
            @php
                $bannerUrl = $user->banner_url;
                $bannerStyle = $bannerUrl ? "background-image: url('{$bannerUrl}'); background-size: cover; background-position: center;" : "background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 52%, #064e3b 100%);";
            @endphp
            <div 
                id="banner-cover"
                style="{{ $bannerStyle }}"
                class="profile-banner"
            >
                <!-- Uploader Trigger Button for Banner -->
                <button
                    type="button"
                    onclick="document.getElementById('banner').click()"
                    class="change-cover-btn"
                >
                    <i class="fa-solid fa-image"></i> Change Cover
                </button>
            </div>
            
            <!-- User Info Section -->
            <div class="profile-info-section">
                
                <!-- Rectangular Avatar Container -->
                <div class="profile-avatar-container">
                    <div 
                        onclick="document.getElementById('avatar').click()"
                        class="profile-avatar-wrapper"
                    >
                        @if ($user->avatar)
                            <img id="sidebar-avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar-img">
                        @else
                            <img id="sidebar-avatar-preview" src="{{ asset('assets') }}/images/sinarmeadow.png" alt="{{ $user->name }}" class="profile-avatar-img">
                        @endif
                        
                        <div class="profile-avatar-overlay">
                            <i class="fa-solid fa-camera" style="font-size: 28px;"></i>
                            <span class="avatar-overlay-text">Change Photo</span>
                        </div>
                    </div>
                </div>

                <!-- User Details Text Area -->
                <div class="profile-details">
                    <div class="profile-name-row">
                        <h1 class="profile-name">
                            {{ $user->name }}
                        </h1>
                        <!-- Roles Badges -->
                        <div class="profile-roles-container">
                            @foreach ($user->roles as $role)
                                <span class="role-badge">
                                    <i class="fa-solid fa-shield"></i> {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- NIK & Username -->
                    <div class="profile-meta-row">
                        <span>NIK: <span class="profile-meta-value">{{ $user->nik }}</span></span>
                        <span class="profile-meta-separator">•</span>
                        <span>Username: <span class="profile-meta-value">{{ '@' . $user->username }}</span></span>
                    </div>
                    
                    <!-- Meta badges for Email, Position, Department, & Atasan -->
                    <div class="profile-badges-row">
                        <span class="meta-badge">
                            <i class="fa-solid fa-envelope"></i> {{ $user->email }}
                        </span>
                        @if($user->position)
                            <span class="meta-badge">
                                <i class="fa-solid fa-briefcase"></i> {{ $user->position->position_name }}
                            </span>
                        @endif
                        @if($user->department)
                            <span class="meta-badge">
                                <i class="fa-solid fa-building"></i> {{ $user->department->department_name }}
                            </span>
                        @endif
                        {{-- @if($user->getAtasan())
                            <span class="meta-badge">
                                <i class="fa-solid fa-user-tie"></i> Atasan: {{ $user->getAtasan()->name }}
                            </span>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid of Profile Forms -->
        <div class="profile-forms-grid">
            <!-- Profile Info form card -->
            <div class="profile-form-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Password update form card -->
            <div class="profile-form-card">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    <!-- Modal Cropper Banner & Avatar -->
    <div id="crop-modal" style="z-index: 999999 !important;" class="fixed inset-0 bg-slate-900/85 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full flex flex-col max-h-[90vh] my-auto overflow-hidden border border-slate-100 relative animate-in fade-in zoom-in duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100">
                        <i class="fa-solid fa-crop-simple"></i>
                    </div>
                    <div>
                        <h3 id="crop-modal-title" class="text-base font-bold text-slate-800">Sesuaikan Tampilan Gambar</h3>
                        <p id="crop-modal-desc" class="text-xs text-slate-500">Geser, perbesar/perkecil, atau putar gambar untuk menyesuaikan posisi.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCropModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 sm:p-6 flex-1 overflow-y-auto flex flex-col items-center justify-center bg-slate-950/5 min-h-0">
                <div class="cropper-wrapper-container shadow-inner">
                    <img id="crop-image-element" src="" alt="Crop target">
                </div>

                <!-- Controls Toolbar -->
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2 bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-200/80 shrink-0">
                    <button type="button" onclick="cropperZoom(0.1)" title="Perbesar" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Zoom In
                    </button>
                    <button type="button" onclick="cropperZoom(-0.1)" title="Perkecil" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-magnifying-glass-minus"></i> Zoom Out
                    </button>
                    <div class="h-4 w-px bg-slate-200 my-auto hidden sm:block"></div>
                    <button type="button" onclick="cropperRotate(-90)" title="Putar Kiri" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-rotate-left"></i> Putar Kiri
                    </button>
                    <button type="button" onclick="cropperRotate(90)" title="Putar Kanan" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-rotate-right"></i> Putar Kanan
                    </button>
                    <div class="h-4 w-px bg-slate-200 my-auto hidden sm:block"></div>
                    <button type="button" onclick="cropperReset()" title="Reset" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/80 shrink-0">
                <button type="button" onclick="closeCropModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="button" onclick="applyCropResult()" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Terapkan Gambar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <script>
            $(document).ready(function() {
                // Toggle Password Visibility
                $(document).on('click', '.toggle-password-btn', function() {
                    var target = $($(this).data('target'));
                    var icon = $(this).find('i');
                    if (target.attr('type') === 'password') {
                        target.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        target.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
