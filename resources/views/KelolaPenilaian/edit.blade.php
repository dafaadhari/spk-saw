@extends('layouts.app')
<title>Edit Penilaian | Sistem Pengambilan Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <!-- Notifikasi Inputan Salah -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <!-- Auto close alert setelah 5 detik -->
            <script>
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 500); // benar-benar hapus dari DOM
                    }
                }, 5000);
            </script>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0 text-white">Edit Penilaian</h3>
                        </div>
                        <div>
                            <a href="/nilai" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit Nilai -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-10">
                        <div class="tab-content p-4">
                            <form class="row g-3 needs-validation" method="POST" action="/nilai/{{ $data->id }}" novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Tendik -->
                                <div class="col-md-6">
                                    <label for="tendik_id" class="form-label">Nama Tendik</label>
                                    <select name="tendik_id" id="tendik_id" class="form-select" required>
                                        <option disabled value="">-- Pilih Tendik --</option>
                                        @foreach($tendiks as $tendik)
                                            <option value="{{ $tendik->id }}" {{ $data->tendik_id == $tendik->id ? 'selected' : '' }}>
                                                {{ $tendik->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Pilih tendik.</div>
                                </div>

                                <!-- Kriteria -->
                                <div class="col-md-6">
                                    <label for="kriteria_id" class="form-label">Nama Kriteria</label>
                                    <select name="kriteria_id" id="kriteria_id" class="form-select" required>
                                        <option disabled value="">-- Pilih Kriteria --</option>
                                        @foreach($kriterias as $kriteria)
                                            <option value="{{ $kriteria->id }}" {{ $data->kriteria_id == $kriteria->id ? 'selected' : '' }}>
                                                {{ $kriteria->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Pilih kriteria.</div>
                                </div>

                                <!-- Nilai -->
                                <div class="col-md-6">
                                    <label for="value" class="form-label">Nilai</label>
                                    <input type="number" class="form-control" id="value" name="value" min="0" step="0.01" value="{{ $data->value }}" placeholder="Contoh: 0 - 100"  required>
                                    <div class="invalid-feedback">Masukkan nilai.</div>
                                </div>

                                <!-- Tombol Simpan -->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
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
