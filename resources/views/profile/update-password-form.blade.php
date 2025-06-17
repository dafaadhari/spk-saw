<form wire:submit.prevent="updatePassword">
    <!-- Judul dan Deskripsi -->
    <div class="mb-4">
        <h5 class="mb-1">{{ __('Update Password') }}</h5>
        <p class="text-muted">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </div>

    <div class="row g-3">
        <!-- Current Password -->
        <div class="col-md-6">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" type="password" class="form-control"
                wire:model.defer="state.current_password" autocomplete="current-password">
            @error('current_password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="col-md-6">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" type="password" class="form-control"
                wire:model.defer="state.password" autocomplete="new-password">
            @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
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
            {{ __('Saved.') }}
        </x-action-message>

        <!-- Tombol simpan -->
        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </button>
    </div>

</form>