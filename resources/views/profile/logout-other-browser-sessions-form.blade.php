<div>
    <!-- Judul & Deskripsi -->
    <div class="mb-4">
        <h5>{{ __('Browser Sessions') }}</h5>
        <p class="text-muted">
            {{ __('Manage and log out your active sessions on other browsers and devices.') }}
        </p>
    </div>

    <!-- Penjelasan -->
    <p class="text-muted small">
        {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
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
                    {{ __('Last active') }} {{ $session->last_active }}
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
            {{ __('Log Out Other Browser Sessions') }}
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
                    <h5 class="modal-title" id="logoutSessionsModalLabel">{{ __('Log Out Other Browser Sessions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}</p>

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
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger"
                        wire:click="logoutOtherBrowserSessions"
                        wire:loading.attr="disabled">
                        <i class="bi bi-box-arrow-right me-1"></i>{{ __('Log Out') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>