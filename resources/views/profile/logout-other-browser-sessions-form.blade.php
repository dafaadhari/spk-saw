<div>
    <!-- Judul & Deskripsi -->
    <div class="mb-4">
        <h5>{{ __('Sesi Browser') }}</h5>
        <p class="text-muted">
            {{ __('Kelola dan keluar dari sesi aktif Anda di browser dan perangkat lain.') }}
        </p>
    </div>

    <!-- Penjelasan -->
    <p class="text-muted small">
        {{ __('Jika perlu, Anda dapat keluar dari semua sesi peramban lain di semua perangkat Anda. Beberapa sesi terkini Anda tercantum di bawah ini; namun, daftar ini mungkin tidak lengkap. Jika Anda merasa akun Anda telah disusupi, Anda juga harus memperbarui kata sandi Anda.') }}
    </p>

    <!-- List Session -->
    @if (count($this->sessions) > 0)
    <div class="mt-4">
        @foreach ($this->sessions as $session)
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                @if ($session->agent->isDesktop())
                <i class="bi bi-laptop fs-3 text-secondary"></i>
                @else
                <i class="bi bi-phone fs-3 text-secondary"></i>
                @endif
            </div>

            <div>
                <div class="small fw-semibold">
                    {{ $session->agent->platform() ?? __('Unknown') }} - {{ $session->agent->browser() ?? __('Unknown') }}
                </div>
                <div class="text-muted small">
                    {{ $session->ip_address }},
                    @if ($session->is_current_device)
                    <span class="text-success fw-semibold">{{ __('This device') }}</span>
                    @else
                    {{ __('Terakhir aktif') }} {{ $session->last_active }}
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Tombol dan Alert -->
    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="button" class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#logoutSessionsModal"
            wire:click="confirmLogout"
            wire:loading.attr="disabled">
            {{ __('Keluar dari Sesi Browser Lainnya') }}
        </button>

        <!-- Notifikasi "Done" -->
        <x-action-message class="text-success small" on="loggedOut">
            {{ __('Done.') }}
        </x-action-message>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div wire:ignore.self class="modal fade" id="logoutSessionsModal" tabindex="-1" aria-labelledby="logoutSessionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutSessionsModalLabel">{{ __('Keluar dari Sesi Browser Lainnya') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin keluar dari sesi browser lain di semua perangkat Anda.') }}</p>

                    <input type="password"
                        class="form-control mt-3"
                        placeholder="{{ __('Password') }}"
                        wire:model.defer="password"
                        wire:keydown.enter="logoutOtherBrowserSessions"
                        autocomplete="current-password"
                        autofocus>

                    @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        wire:click="$set('confirmingLogout', false)">
                        {{ __('Batal') }}
                    </button>
                    <button type="button" class="btn btn-danger"
                        wire:click="logoutOtherBrowserSessions"
                        wire:loading.attr="disabled">
                        <i class="bi bi-box-arrow-right me-1"></i>{{ __('Keluar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>