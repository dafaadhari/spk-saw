@extends('layouts.app')
<title>Edit Bobot Kriteria | Sistem Pendukung Keputusan</title>
@section('content')

<div id="app-content">
    <div class="app-content-area pt-0 ">
        <div class="bg-primary pt-12 pb-21 "></div>
        <div class="container-fluid mt-n22 ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>⚠ {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0 " style="color:white">Edit Kriteria</h3>
                        </div>
                        <div>
                            <a href="/kriteria" class="btn btn-white">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- validation -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="mb-10 card">
                        <div class="tab-content p-4">
                            <div class="tab-pane fade show active">
                                <form action="{{ route('kriteria.update', $kriteria->kode_kriteria) }}" method="POST" class="row g-3 needs-validation" novalidate>
                                    @csrf


                                    <!-- Kode Kriteria (read-only) -->
                                    <div class="col-md-4">
                                        <label for="kode_kriteria" class="form-label">Kode Kriteria</label>
                                        <input type="text" class="form-control" id="kode_kriteria" name="kode_kriteria" value="{{ $kriteria->kode_kriteria }}" readonly>
                                    </div>

                                    <!-- Nama Kriteria -->
                                    <div class="col-md-4">
                                        <label for="nama" class="form-label">Nama Kriteria</label>
                                        <input type="text" class="form-control" id="nama" name="nama_kriteria" value="{{ $kriteria->nama }}" required>
                                        <div class="invalid-feedback">
                                            Nama kriteria wajib diisi.
                                        </div>
                                    </div>

                                    <!-- Bobot -->
                                    <div class="col-md-2">
                                        <label for="weight" class="form-label">Bobot</label>
                                        <input type="number" step="0.01" min="0" max="1" class="form-control" id="weight" name="bobot" value="{{ $kriteria->weight }}" required>
                                        <div class="invalid-feedback">
                                            Masukkan nilai bobot antara 0 - 1.
                                        </div>
                                    </div>

                                    <!-- Sumber -->
                                    <div class="col-md-2">
                                        <label for="sumber" class="form-label">Sumber</label>
                                        <input type="text" class="form-control" id="sumber" name="sumber" value="{{ $kriteria->sumber }}" required>
                                        <div class="invalid-feedback">
                                            Masukkan sumber (HRD,LAPORAN).
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Update Kriteria</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- validation -->
        </div>
    </div>
</div>

@endsection