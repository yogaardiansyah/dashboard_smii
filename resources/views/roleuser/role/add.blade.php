<x-app-layout>
    @section('title')
        Give Permission to Role
    @endsection

    @push('css')
        @include('layouts.partials.roleuser_styles')
        <style>
            .permission-group-card {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 18px;
                transition: all 0.2s ease;
            }
            .permission-group-card:hover {
                border-color: #cbd5e1;
                box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            }
            .permission-checkbox-item {
                display: flex;
                align-items: center;
                padding: 8px;
                border-radius: 8px;
                transition: background-color 0.15s ease;
            }
            .permission-checkbox-item:hover {
                background-color: #f1f5f9;
            }
            .custom-checkbox {
                border-radius: 6px;
                border-color: #cbd5e1;
                color: #2563eb;
                width: 18px;
                height: 18px;
                transition: all 0.15s ease;
            }
            .custom-checkbox:focus {
                ring-color: #3b82f6;
            }
        </style>
    @endpush

    <div class="pl-shell mt-4">
        <div class="pl-hero">
            <span class="pl-hero-kicker">Role & Permission Matrix</span>
            <div class="pl-hero-title">Role: {{ $role->name }}</div>
            <p class="pl-hero-copy">Atur dan distribusikan hak akses (permissions) yang dimiliki oleh peran kelompok ini.</p>
            <div class="pl-toolbar">
                <a href="{{ url('roles') }}" class="pl-btn pl-btn-neutral">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke List Roles
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm mb-6" role="alert">
                <p class="font-bold">Sukses</p>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="pl-card">
            <div class="pl-card-head flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <div class="pl-card-title">Matriks Hak Akses</div>
                    <div class="pl-card-copy">Centang pilihan di bawah untuk memberikan akses.</div>
                </div>
                <div class="mt-4 md:mt-0 flex items-center bg-slate-100 p-2.5 rounded-xl border border-slate-200">
                    <input type="checkbox" id="selectAll" class="custom-checkbox text-blue-600 focus:ring-blue-500" onclick="toggleAllCheckboxes(this)">
                    <label for="selectAll" class="text-sm font-semibold ml-2 text-slate-700 cursor-pointer select-none">Pilih Semua Hak Akses</label>
                </div>
            </div>
            <div class="pl-card-body">
                <form action="{{ url('roles/'.$role->id.'/give-permissions') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        @error('permission')
                            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 font-medium">{{ $message }}</div>
                        @enderror

                        @php
                            $permissionsByLastWord = [];

                            foreach ($permissions as $permission) {
                                $words = explode(' ', $permission->name);
                                $lastWord = end($words);

                                if (!isset($permissionsByLastWord[$lastWord])) {
                                    $permissionsByLastWord[$lastWord] = [];
                                }

                                $permissionsByLastWord[$lastWord][] = $permission;
                            }
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($permissionsByLastWord as $lastWord => $groupedPermissions)
                                <div class="permission-group-card">
                                    <div class="flex items-center space-x-2 border-b border-slate-200 pb-3 mb-3">
                                        <i class="fa-solid fa-folder-open text-blue-600 dark:text-blue-400"></i>
                                        <label class="text-base font-bold text-slate-800 uppercase tracking-wide">
                                            Modul: {{ $lastWord }}
                                        </label>
                                    </div>
                                    <div class="space-y-1">
                                        @foreach($groupedPermissions as $permission)
                                            <div class="permission-checkbox-item">
                                                <input type="checkbox" 
                                                    id="permissionCheckbox{{ $permission->id }}" 
                                                    class="custom-checkbox text-blue-600 focus:ring-blue-500" 
                                                    name="permission[]" 
                                                    value="{{ $permission->name }}" 
                                                    {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                <label for="permissionCheckbox{{ $permission->id }}" class="text-sm font-semibold text-slate-700 ml-3 cursor-pointer select-none">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-100 pt-6 flex justify-end">
                        <button type="submit" class="pl-btn pl-btn-primary px-8 py-3">
                            <i class="fa-solid fa-circle-check mr-2"></i> Update Matriks Akses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleAllCheckboxes(source) {
            var checkboxes = document.querySelectorAll('input[name="permission[]"]');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    @endpush
</x-app-layout>
