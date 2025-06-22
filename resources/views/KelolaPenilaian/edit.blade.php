@extends('layouts.app')
<title>Edit Penilaian | Sistem Pendukung Keputusan</title>

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
                            <h3 class="mb-0" style="color:white">Edit Penilaian</h3>
                        </div>
                        <div>
                            <a href="/nilai" class="btn btn-light btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit Nilai -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-10">
                        <div class="tab-content p-4">
                            <form method="POST" action="/nilai/tendik/{{ $tendik->nik }}">
                                @csrf
                                @method('PUT')

                                <div class="row align-items-end">
                                    <!-- NIK Tendik (readonly) -->
                                    <div class="col-md-3">
                                        <label class="form-label text-capitalize">Nama Tendik</label>
                                        <input type="text" class="form-control" value="{{ $tendik->nama }}" disabled>
                                        <input type="hidden" name="tendik_nik" value="{{ $tendik->nik }}">
                                    </div>

                                    <!-- Input nilai untuk setiap kriteria -->
                                    @foreach($kriterias as $kriteria)
                                    <div class="col-md-2">
                                        <label class="form-label text-capitalize">{{ $kriteria->nama }}</label>
                                        <input type="number"
                                            name="nilai[{{ $kriteria->kode_kriteria }}]"
                                            class="form-control"
                                            placeholder="0 - 100"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            value="{{ old('nilai.' . $kriteria->kode_kriteria, $nilaiMap[$kriteria->kode_kriteria] ?? '') }}"
                                            required>
                                        @error("nilai.{$kriteria->kode_kriteria}")
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Tombol simpan di bawah -->
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-start gap-2">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
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