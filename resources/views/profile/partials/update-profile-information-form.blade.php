<section>
    <div class="border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
        <div class="bg-blue-100 p-3 rounded-2xl text-blue-600">
            <i class="fa-solid fa-user-pen text-2xl"></i>
        </div>
        <div>
            <div class="text-xl font-bold text-slate-800">
                {{ __('Informasi Profil') }}
            </div>
            <p class="text-sm text-slate-500 mt-1">
                {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
            </p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.updates') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 form-group-spacing">
            <div>
                <label for="nik" class="modern-label">{{ __('NIK') }}</label>
                <input id="nik" name="nik" type="text" class="modern-input" value="{{ old('nik', $user->nik) }}" required autofocus autocomplete="nik" readonly />
                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
            </div>

            <div>
                <label for="name" class="modern-label">{{ __('Nama') }}</label>
                <input id="name" name="name" type="text" class="modern-input" value="{{ old('name', $user->name) }}" required autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="form-group-spacing">
            <label for="email" class="modern-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="modern-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-slate-700">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-group-spacing">
            <label for="avatar" class="modern-label">{{ __('Foto Profil / Avatar') }}</label>
            <div class="flex items-center gap-4">
                @if ($user->avatar)
                    <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-slate-200">
                @else
                    <img id="avatar-preview" src="{{ asset('assets') }}/images/sinarmeadow.png" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-slate-200 hidden">
                @endif
                <div class="flex-grow">
                    <input type="file" name="avatar" id="avatar" class="modern-input" accept="image/*" onchange="previewAvatar()" style="padding: 8px;">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPEG, PNG, JPG, GIF, SVG, WEBP (Max: 2MB)</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="form-group-custom hidden">
            <!-- Hidden inputs triggered by profile header -->
            <input type="file" name="banner" id="banner" accept="image/*" onchange="previewBanner()">
        </div>

        <script>
            var currentCropper = null;
            var currentCropType = null;
            var selectedFile = null;

            function previewAvatar() {
                var fileInput = document.getElementById('avatar');
                var file = fileInput.files && fileInput.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file foto maksimal 5MB');
                    fileInput.value = '';
                    return;
                }

                openCropModal(file, 'avatar');
            }

            function previewBanner() {
                var fileInput = document.getElementById('banner');
                var file = fileInput.files && fileInput.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file banner maksimal 10MB');
                    fileInput.value = '';
                    return;
                }

                openCropModal(file, 'banner');
            }

            function openCropModal(file, type) {
                currentCropType = type;
                selectedFile = file;

                var modal = document.getElementById('crop-modal');
                var imgEl = document.getElementById('crop-image-element');
                var titleEl = document.getElementById('crop-modal-title');
                var descEl = document.getElementById('crop-modal-desc');

                if (!modal || !imgEl) return;

                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
                modal.style.zIndex = '999999';

                if (type === 'banner') {
                    titleEl.textContent = 'Sesuaikan & Potong Banner Profil';
                    descEl.textContent = 'Geser, perbesar/perkecil, atau putar gambar untuk menyesuaikan posisi banner profil Anda.';
                } else {
                    titleEl.textContent = 'Sesuaikan & Potong Foto Profil';
                    descEl.textContent = 'Geser, perbesar/perkecil, atau putar gambar untuk menyesuaikan posisi foto profil Anda.';
                }

                if (currentCropper) {
                    currentCropper.destroy();
                    currentCropper = null;
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                var reader = new FileReader();
                reader.onload = function (e) {
                    imgEl.src = e.target.result;

                    setTimeout(function () {
                        var aspectRatio = (type === 'banner') ? (1200 / 300) : 1;

                        currentCropper = new Cropper(imgEl, {
                            aspectRatio: aspectRatio,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.95,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: true,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            responsive: true,
                            checkOrientation: false
                        });
                    }, 60);
                };
                reader.readAsDataURL(file);
            }

            function cropperZoom(delta) {
                if (currentCropper) currentCropper.zoom(delta);
            }

            function cropperRotate(deg) {
                if (currentCropper) currentCropper.rotate(deg);
            }

            function cropperReset() {
                if (currentCropper) currentCropper.reset();
            }

            function closeCropModal() {
                var modal = document.getElementById('crop-modal');
                if (modal) modal.classList.add('hidden');
                document.body.style.overflow = '';
                if (currentCropper) {
                    currentCropper.destroy();
                    currentCropper = null;
                }
            }

            function applyCropResult() {
                if (!currentCropper) return;

                var outputWidth = (currentCropType === 'banner') ? 1200 : 400;
                var outputHeight = (currentCropType === 'banner') ? 300 : 400;

                var canvas = currentCropper.getCroppedCanvas({
                    width: outputWidth,
                    height: outputHeight,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                canvas.toBlob(function (blob) {
                    if (!blob) return;

                    var mimeType = selectedFile ? selectedFile.type : 'image/png';
                    var fileName = selectedFile ? selectedFile.name : (currentCropType === 'banner' ? 'banner.png' : 'avatar.png');
                    var croppedFile = new File([blob], fileName, { type: mimeType });

                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);

                    var inputId = (currentCropType === 'banner') ? 'banner' : 'avatar';
                    var inputEl = document.getElementById(inputId);
                    if (inputEl) {
                        inputEl.files = dataTransfer.files;
                    }

                    var previewUrl = URL.createObjectURL(blob);

                    if (currentCropType === 'banner') {
                        var bannerCover = document.getElementById('banner-cover');
                        if (bannerCover) {
                            bannerCover.style.backgroundImage = 'url(' + previewUrl + ')';
                            bannerCover.style.backgroundSize = 'cover';
                            bannerCover.style.backgroundPosition = 'center';
                        }
                    } else if (currentCropType === 'avatar') {
                        var sidebarImg = document.getElementById('sidebar-avatar-preview');
                        if (sidebarImg) sidebarImg.src = previewUrl;

                        var avatarPreview = document.getElementById('avatar-preview');
                        if (avatarPreview) {
                            avatarPreview.src = previewUrl;
                            avatarPreview.classList.remove('hidden');
                        }
                    }

                    closeCropModal();
                }, selectedFile ? selectedFile.type : 'image/png', 0.92);
            }
        </script>

        <div class="flex items-center gap-4 pt-4 mt-2">
            <button type="submit" class="modern-btn">{{ __('Save Profile Information') }}</button>

            @if (session('status') === 'profile-updated' || session('flash'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-semibold flex items-center"
                >
                    <i class="fa-solid fa-circle-check mr-1.5"></i> {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
