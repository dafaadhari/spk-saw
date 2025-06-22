@extends('layouts.app')
<title>Kelola Data Tendik | Sistem Pendukung Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0" style="color:white">Edit Data Tendik</h3>
                        </div>
                        <div>
                            <a href="{{ url('tendik') }}" class="btn btn-white">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Error --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-10">
                        <div class="card-body">
                            <form class="row" action="{{ url('tendik/' . $tendik->nik) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Nama -->
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $tendik->nama) }}" placeholder="Masukkan Nama" required>
                                </div>

                                <!-- NIK -->
                                <div class="mb-3 col-md-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $tendik->nik) }}" maxlength="16" minlength="16" placeholder="Masukkan NIK 16 karakter" required>
                                </div>

                                <!-- Unit Kerja -->
                                <div class="mb-3 col-md-6">
                                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                    <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $tendik->unit_kerja) }}" placeholder="Masukkan Unit Kerja" required>
                                </div>

                                <!-- Jenis Pegawai -->
                                <div class="mb-3 col-md-6">
                                    <label for="jenis_pegawai" class="form-label">Jenis Pegawai</label>
                                    <input type="text" name="jenis_pegawai" class="form-control" value="{{ old('jenis_pegawai', $tendik->jenis_pegawai) }}" placeholder="Masukkan Jenis Pegawai" required>
                                </div>

                                <!-- Jam Kerja Tahunan -->
                                <div class="mb-3 col-md-6">
                                    <label for="jam_kerja_tahunan" class="form-label">Jam Kerja Tahunan</label>
                                    <input type="number" name="jam_kerja_tahunan" class="form-control" value="{{ old('jam_kerja_tahunan', $tendik->jam_kerja_tahunan) }}" min="0" placeholder="Contoh: 2382" required>
                                </div>

                                <!-- Jam Kerja Bulanan -->
                                <div class="mb-3 col-md-6">
                                    <label for="jam_kerja_bulanan" class="form-label">Jam Kerja Bulanan</label>
                                    <input type="text" name="jam_kerja_bulanan" class="form-control" value="{{ old('jam_kerja_bulanan', $tendik->jam_kerja_bulanan) }}" placeholder="Contoh: 198.50" required>
                                </div>

                                <!-- Hidden User ID -->
                                <input type="hidden" name="user_id" value="{{ $tendik->user_id }}">

                                <!-- Submit -->
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection