<form wire:submit.prevent="updatePassword">
    <!-- Judul dan Deskripsi -->
    <div class="mb-4">
        <p class="text-muted">{{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}</p>
    </div>

    <div class="row g-3">
        <!-- Current Password -->
        <div class="col-md-6">
            <label for="current_password" class="form-label">{{ __('Password Saat Ini') }}</label>
            <input id="current_password" type="password" class="form-control"
                wire:model.defer="state.current_password" autocomplete="current-password">
            @error('current_password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="col-md-6">
            <label for="password" class="form-label">{{ __('Password Baru') }}</label>
            <input id="password" type="password" class="form-control"
                wire:model.defer="state.password" autocomplete="new-password">
            @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
            <input id="password_confirmation" type="password" class="form-control"
                wire:model.defer="state.password_confirmation" autocomplete="new-password">
            @error('password_confirmation')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
        <!-- Pesan sukses -->
        <x-action-message class="text-success small" on="saved">
            {{ __('Tersimpan.') }}
        </x-action-message>

        <!-- Tombol simpan -->
        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled" wire:target="photo">
            {{ __('Simpan') }}
        </button>
    </div>

</form>