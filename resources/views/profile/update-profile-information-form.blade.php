<form wire:submit.prevent="updateProfileInformation">
    <!-- Judul -->
    <div class="mb-4">
        <h5 class="mb-1">{{ __('Profile Information') }}</h5>
        <p class="text-muted">{{ __('Perbarui informasi profil akun dan alamat email Anda.') }}</p>
    </div>

    <div class="row g-3">

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div class="col-md-12">
            <label class="form-label">{{ __('Photo') }}</label>

            <div x-data="{photoName: null, photoPreview: null}" class="d-flex flex-column gap-2">
                <!-- File Input -->
                <input type="file" id="photo" class="d-none"
                    wire:model.live="photo"
                    x-ref="photo"
                    x-on:change="
                        photoName = $refs.photo.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            photoPreview = e.target.result;
                        };
                        reader.readAsDataURL($refs.photo.files[0]);
                    " />

                <!-- Current Photo -->
                <div x-show="!photoPreview" class="mt-1">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                </div>

                <!-- Preview -->
                <div x-show="photoPreview" style="display: none;" class="mt-1">
                    <div class="rounded-circle bg-cover bg-center" style="width: 80px; height: 80px;" x-bind:style="'background-image: url(' + photoPreview + ')'"></div>
                </div>

                <!-- Buttons -->
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select A New Photo') }}
                    </button>

                    @if ($this->user->profile_photo_path)
                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </button>
                    @endif
                </div>

                @error('photo') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
        @endif

        <!-- Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">{{ __('Nama') }}</label>
            <input id="name" type="text" class="form-control" wire:model="state.name" required autocomplete="name">
            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control" wire:model="state.email" required autocomplete="username">
            @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
            <div class="form-text mt-2">
                {{ __('Alamat email Anda belum diverifikasi.') }}

                <button type="button" class="btn btn-link p-0 align-baseline" wire:click.prevent="sendEmailVerification">
                    {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                </button>
            </div>

            @if ($this->verificationLinkSent)
            <div class="text-success mt-1">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-start gap-4 mt-6" style="text-align: end;">
        <!-- Pesan sukses -->
        <x-action-message class="text-green-600 text-sm" on="saved">
            {{ __('Tersimpan.') }}
        </x-action-message>

        <!-- Tombol simpan -->
        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled" wire:target="photo">
            {{ __('Simpan') }}
        </button>
    </div>

</form>