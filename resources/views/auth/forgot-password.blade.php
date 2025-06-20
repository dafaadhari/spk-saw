<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo"><x-authentication-card-logo /></x-slot>

        <div class="mb-4 text-sm text-gray-600">
            Masukkan email Anda untuk menerima kode OTP pengaturan ulang password.
        </div>

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <x-validation-errors class="mb-4" />

        {{-- Form kirim OTP --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="block">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>Kirim Kode OTP</x-button>
            </div>
        </form>

        {{-- Modal input OTP --}}
        @if (session('status'))
        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-[90%]">
                <h2 class="text-lg font-semibold mb-4 text-center">Verifikasi OTP</h2>

                <form method="POST" action="{{ route('password.verify.otp') }}">
                    @csrf
                    <x-label for="otp" value="Masukkan Kode OTP" />
                    <x-input id="otp" type="text" name="otp" aria-placeholder="kode otp.." required class="mt-1 block w-full" />

                    <div class="flex justify-end mt-4">
                        <x-button>Verifikasi</x-button>
                        <button type="button" @click="open = false" class="ml-2 px-4 py-2 text-sm text-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </x-authentication-card>
</x-guest-layout>