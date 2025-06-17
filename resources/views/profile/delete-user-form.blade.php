<div>
    <!-- Judul & Deskripsi -->
    <div class="mb-4">
        <h5 class="mb-1">{{ __('Delete Account') }}</h5>
        <p class="text-muted">{{ __('Permanently delete your account.') }}</p>

        <p class="text-muted small mt-3">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <!-- Tombol Trigger -->
        <button type="button"
            class="btn btn-danger mt-3"
            data-bs-toggle="modal"
            data-bs-target="#deleteAccountModal"
            wire:click="confirmUserDeletion"
            wire:loading.attr="disabled">
            <i class="bi bi-trash me-1"></i>{{ __('Delete Account') }}
        </button>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <div wire:ignore.self class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">{{ __('Delete Account') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <p class="text-muted small">
                        {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                    </p>

                    <p class="text-sm mt-3">
                        {{ __('Please enter your password to confirm:') }}
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
                        {{ __('Cancel') }}
                    </button>

                    <button type="button"
                        class="btn btn-danger"
                        wire:click="deleteUser"
                        wire:loading.attr="disabled">
                        <i class="bi bi-trash-fill me-1"></i>{{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>