@extends('layouts.app')
<title>Kelola Data Alternatif | Sistem Pendukung Keputusan</title>

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
                        <h3 class="mb-0" style="color:white">Form Tambah Alternatif</h3>
                        <a href="{{ url('/Alternatif') }}" class="btn btn-white">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-4">
                        <form action="{{ url('/Alternatif') }}" method="POST" class="row g-3 needs-validation" novalidate>
                            @csrf

                            <!-- NIK -->
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" minlength="16" maxlength="16" placeholder="Masukkan NIK 16 karakter" required pattern="\w{16}">
                                <div class="invalid-feedback">
                                    NIK wajib diisi dengan tepat 16 karakter.
                                </div>
                            </div>


                            <!-- Nama -->
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                                <div class="invalid-feedback">
                                    Nama wajib diisi.
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

                            <!-- Jenis Pegawai -->
                            <div class="col-md-6">
                                <label for="jenis_pegawai" class="form-label">Jenis Pegawai</label>
                                <input type="text" class="form-control" id="jenis_pegawai" name="jenis_pegawai" placeholder="Masukkan Jenis Pegawai" required>
                                <div class="invalid-feedback">
                                    Jenis pegawai wajib diisi.
                                </div>
                            </div>


                            <!-- Jam Kerja Tahunan -->
                            <div class="col-md-6">
                                <label for="jam_kerja_tahunan" class="form-label">Jam Kerja Tahunan</label>
                                <input type="number" class="form-control" id="jam_kerja_tahunan" name="jam_kerja_tahunan" placeholder="Contoh: 2382" required>
                                <div class="invalid-feedback">
                                    Jam kerja tahunan wajib diisi.
                                </div>
                            </div>

                            <!-- Jam Kerja Bulanan -->
                            <div class="col-md-6">
                                <label for="jam_kerja_bulanan" class="form-label">Jam Kerja Bulanan</label>
                                <input type="number" step="0.01" class="form-control" id="jam_kerja_bulanan" name="jam_kerja_bulanan" placeholder="Contoh: 198.50" required>
                                <div class="invalid-feedback">
                                    Jam kerja bulanan wajib diisi.
                                </div>
                            </div>

                            <!-- User ID dari Session -->
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