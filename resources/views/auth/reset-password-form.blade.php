<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo"><x-authentication-card-logo /></x-slot>

        <div class="mb-4 text-sm text-gray-600">
            Silakan masukkan password baru Anda.
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.reset.update') }}">
            @csrf

            <div class="mt-4">
                <x-label for="password" value="Password Baru" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Konfirmasi Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="flex justify-end mt-4">
                <x-button>Simpan Password Baru</x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>