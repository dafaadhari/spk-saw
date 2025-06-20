<div>
    <!-- Judul & Deskripsi -->
    <div class="mb-4">
        <p class="text-muted">{{ __('Hapus akun Anda secara permanen.') }}</p>

        <p class="text-muted small mt-3">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>

        <!-- Tombol Trigger -->
        <button type="button"
            class="btn btn-danger mt-3"
            data-bs-toggle="modal"
            data-bs-target="#deleteAccountModal"
            wire:click="confirmUserDeletion"
            wire:loading.attr="disabled">
            <i class="bi bi-trash me-1"></i>{{ __('Hapus Akun') }}
        </button>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <div wire:ignore.self class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">{{ __('Hapus Akun') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <p class="text-muted small">
                        {{ __('Apakah Anda yakin ingin menghapus akun Anda? Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.') }}
                    </p>

                    <p class="text-sm mt-3">
                        {{ __('Silakan masukkan kata sandi Anda untuk konfirmasi:') }}
                    </p>

                    <input type="password"
                        class="form-control mt-2"
                        placeholder="{{ __('Password') }}"
                        wire:model.defer="password"
                        wire:keydown.enter="deleteUser"
                        autocomplete="current-password"
                        autofocus>

                    @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                        wire:click="$set('confirmingUserDeletion', false)">
                        {{ __('Batal') }}
                    </button>

                    <button type="button"
                        class="btn btn-danger"
                        wire:click="deleteUser"
                        wire:loading.attr="disabled">
                        <i class="bi bi-trash-fill me-1"></i>{{ __('Hapus Akun') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>