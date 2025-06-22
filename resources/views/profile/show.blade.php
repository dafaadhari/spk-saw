@extends('layouts.app')
<title>My Profile | Sistem Pendukung Keputusan</title>
@section('content')
<div id="app-content">

    <div class="app-content-area pt-0 ">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">
            <div class="row">
                <div class="col-12 mb-4">
                    <h3 class="text-white">Profil Akun</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12">
                    {{-- SECTION: Update Profil --}}
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">Informasi Profil</h4>
                            @livewire('profile.update-profile-information-form')
                        </div>
                    </div>
                    @endif

                    {{-- SECTION: Update Password --}}
                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">Ubah Password</h4>
                            @livewire('profile.update-password-form')
                        </div>
                    </div>
                    @endif

                    {{-- SECTION: Two-Factor Auth --}}
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">Autentikasi Dua Faktor</h4>
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    </div>
                    @endif

                    {{-- SECTION: Logout Other Sessions --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">Sesi Perangkat Lain</h4>
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>
                    </div>

                    {{-- SECTION: Delete Account --}}
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="card mb-4 border-danger">
                        <div class="card-body">
                            <h4 class="mb-3 text-danger">Hapus Akun</h4>
                            @livewire('profile.delete-user-form')
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection