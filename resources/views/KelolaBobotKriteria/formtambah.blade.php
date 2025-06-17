@extends('layouts.app')
<title>Tambah Bobot Kriteria | Sistem Pemgambilan Keputusan</title>
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
                    <!-- Page header -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0  text-white">Form Bobot Kriteria</h3>
                        </div>
                        <div>
                            <a href="/kriteria" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- validation -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <!-- Card -->
                    <div class="mb-10 card">
                        <div class="tab-content p-4" id="pills-tabContent-validation">
                            <div class="tab-pane tab-example-design fade show active" id="pills-validation-design"
                                role="tabpanel" aria-labelledby="pills-validation-design-tab">
                                <form action="{{ route('kriteria.save') }}" method="POST" class="row g-3 needs-validation" novalidate>
                                    <!-- Nama Kriteria -->
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="namaKriteria" class="form-label">Nama Kriteria</label>
                                        <input type="text" class="form-control" id="namaKriteria" name="nama_kriteria" placeholder="Nama Kriteria" required>
                                        <div class="invalid-feedback">
                                            Nama kriteria wajib diisi.
                                        </div>
                                    </div>

                                    <!-- Bobot -->
                                    <div class="col-md-3">
                                        <label for="bobot" class="form-label">Bobot</label>
                                        <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" min="0" max="1" placeholder="Bobot" required>
                                        <div class="invalid-feedback">
                                            Masukkan nilai bobot antara 0 - 1.
                                        </div>
                                    </div>

                                    <!-- sumber (Benefit/Cost) -->
                                    <div class="col-md-3">
                                        <label for="sumber" class="form-label">Sumber</label>
                                        <input type="text" class="form-control" id="sumber" name="sumber" required placeholder="Masukkan sumber (HRD,LAPORAN)." required>
                                        <div class="invalid-feedback">
                                            Masukkan sumber (HRD,LAPORAN).
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Simpan Kriteria</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- validation -->
    </div>
</div>

@endsection