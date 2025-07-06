<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        <x-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="form-login">
            @csrf

            <div>
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    autofocus
                    autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Kata Sandi" />
                <x-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
                @endif

                <x-button class="ms-4">
                    Masuk
                </x-button>
            </div>
        </form>

        <div id="modal-error" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-md shadow-xl p-6 w-[50%] max-w-lg mx-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-red-600">Oops!</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                </div>
                <div id="modal-error-message" class="text-sm text-gray-700"></div>
                <div class="mt-4 text-right">
                    <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('form-login');
                const email = form.querySelector('input[name="email"]');
                const password = form.querySelector('input[name="password"]');

                form.addEventListener('submit', function(e) {
                    let error = null;

                    if (!email.value.trim()) {
                        error = "Email wajib diisi.";
                        email.focus();
                    } else if (!password.value.trim()) {
                        error = "Kata sandi wajib diisi.";
                        password.focus();
                    }

                    if (error) {
                        e.preventDefault();
                        showModal(error);
                    }
                });
            });

            function showModal(message) {
                const modal = document.getElementById('modal-error');
                const messageBox = document.getElementById('modal-error-message');
                messageBox.textContent = message;
                modal.classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('modal-error').classList.add('hidden');
            }
        </script>

    </x-authentication-card>
</x-guest-layout>