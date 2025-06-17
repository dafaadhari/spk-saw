@extends('layouts.app')
<title>Kelola Data Tendik | Sistem Pengambilan Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">
        
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="mb-0 text-white">Form Tambah Tendik</h3>
                        <a href="{{ url('/tendik') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>

            <!-- Form Tambah Tendik -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-4">
                        <form action="{{ url('/tendik') }}" method="POST" class="row g-3 needs-validation" novalidate>
                            @csrf

                            <!-- Nama -->
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                                <div class="invalid-feedback">
                                    Nama wajib diisi.
                                </div>
                            </div>

                            <!-- NIP -->
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" maxlength="16" pattern="\d{1,16}" placeholder="Maksimal 16 Karakter" required>
                                <div class="invalid-feedback">
                                    NIK harus berupa angka maksimal 16 digit dan tidak boleh duplikat.
                                </div>
                            </div>

                            <!-- Unit Kerja -->
                            <div class="col-md-6">
                                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                <input type="text" class="form-control" id="unit_kerja" name="unit_kerja" placeholder="Masukkan Unit Kerja" required>
                                <div class="invalid-feedback">
                                    Unit kerja wajib diisi.
                                </div>
                            </div>

                            <!-- ID User dari Session -->
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                            <!-- Tombol Simpan -->
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
