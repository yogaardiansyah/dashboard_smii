<section>
    <div class="border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
        <div class="bg-rose-100 p-3 rounded-2xl text-rose-600">
            <i class="fa-solid fa-lock text-2xl"></i>
        </div>
        <div>
            <div class="text-xl font-bold text-slate-800 capitalize">
                {{ __('Update Password') }}
            </div>
            <p class="text-sm text-slate-500 mt-1">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div class="form-group-spacing">
            <label for="update_password_current_password" class="modern-label">{{ __('Current Password') }}</label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password" class="modern-input pr-10" autocomplete="current-password" />
                <button type="button" class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" data-target="#update_password_current_password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 form-group-spacing">
            <div>
                <label for="update_password_password" class="modern-label">{{ __('New Password') }}</label>
                <div class="relative">
                    <input id="update_password_password" name="password" type="password" class="modern-input pr-10" autocomplete="new-password" />
                    <button type="button" class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" data-target="#update_password_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="modern-label">{{ __('Confirm Password') }}</label>
                <div class="relative">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="modern-input pr-10" autocomplete="new-password" />
                    <button type="button" class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" data-target="#update_password_password_confirmation">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="password-match-feedback" class="mt-1.5 hidden">
                    <span id="password-match-text" class="text-xs font-semibold"></span>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 mt-2">
            <button type="submit" class="modern-btn">{{ __('Update Password') }}</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold flex items-center"
                >
                    <i class="fa-solid fa-circle-check mr-1.5"></i> {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var $password = $('#update_password_password');
                var $confirmPassword = $('#update_password_password_confirmation');
                var $feedback = $('#password-match-feedback');
                var $feedbackText = $('#password-match-text');

                function checkPasswordMatch() {
                    var passwordVal = $password.val();
                    var confirmVal = $confirmPassword.val();

                    if (passwordVal === '' && confirmVal === '') {
                        $feedback.addClass('hidden');
                        $confirmPassword.css('border-color', '');
                        return;
                    }

                    $feedback.removeClass('hidden');

                    if (passwordVal === confirmVal) {
                        $feedbackText
                            .html('<i class="fa-solid fa-circle-check mr-1"></i> Passwords match')
                            .removeClass('text-red-500')
                            .addClass('text-green-600');
                        $confirmPassword.css('border-color', '#22c55e');
                    } else {
                        $feedbackText
                            .html('<i class="fa-solid fa-circle-xmark mr-1"></i> Passwords do not match')
                            .removeClass('text-green-600')
                            .addClass('text-red-500');
                        $confirmPassword.css('border-color', '#ef4444');
                    }
                }

                $password.on('input', checkPasswordMatch);
                $confirmPassword.on('input', checkPasswordMatch);
            });

            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session()->get('success') }}',
                    text: '{{ session()->get('message') }}',
                });
            @endif
        </script>
    @endpush
</section>
