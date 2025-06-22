@extends('layouts.app')
<title>Tambah Penilaian | Sistem Pendukung Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

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

            <script>
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 5000);
            </script>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="mb-0" style="color:white">Tambah Penilaian</h3>
                        <a href="/nilai" class="btn btn-light btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-10">
                        <div class="tab-content p-4">
                            <form method="POST" action="{{ route('nilai') }}">
                                @csrf

                                <div class="row align-items-end">
                                    <!-- Kolom Pilih Tendik -->
                                    <div class="col-md-3">
                                        <label class="form-label text-capitalize">Nama Tendik</label>
                                        <select name="tendik_nik" class="form-select" required>
                                            <option disabled selected value="">-- Pilih Tendik --</option>
                                            @foreach($tendiks as $tendik)
                                            <option value="{{ $tendik->nik }}">{{ $tendik->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('tendik_nik')
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Input Tiap Kriteria -->
                                    @foreach($kriterias as $kriteria)
                                    <div class="col-md-2">
                                        <label class="form-label text-capitalize">{{ $kriteria->nama }}</label>
                                        <input type="number"
                                            name="nilai[{{ $kriteria->kode_kriteria }}]"
                                            class="form-control"
                                            min="0" max="100" step="0.01"
                                            placeholder="0-100" required>
                                        @error("nilai.{$kriteria->kode_kriteria}")
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endforeach
                                </div>

                                <div class="col-md-2 d-flex mt-4">
                                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                </div>
                            </form>

                            <!-- JavaScript Dinamis -->
                            <script>
                                function updateKriteriaOptions() {
                                    const rows = document.querySelectorAll('.nilai-row');

                                    rows.forEach((currentRow, currentIndex) => {
                                        const currentTendik = currentRow.querySelector('select[name="tendik_nik[]"]');
                                        const currentKriteria = currentRow.querySelector('select[name="kode_kriteria[]"]');

                                        const selectedPairs = [];
                                        rows.forEach((row, idx) => {
                                            if (idx !== currentIndex) {
                                                const tendik = row.querySelector('select[name="tendik_nik[]"]').value;
                                                const kriteria = row.querySelector('select[name="kode_kriteria[]"]').value;
                                                if (tendik && kriteria) {
                                                    selectedPairs.push({
                                                        tendik,
                                                        kriteria
                                                    });
                                                }
                                            }
                                        });

                                        const options = currentKriteria.querySelectorAll('option');
                                        options.forEach(option => {
                                            option.disabled = false;
                                            option.hidden = false;
                                        });

                                        selectedPairs.forEach(pair => {
                                            if (pair.tendik === currentTendik.value) {
                                                options.forEach(option => {
                                                    if (option.value === pair.kriteria) {
                                                        option.disabled = true;
                                                        option.hidden = true;
                                                    }
                                                });
                                            }
                                        });
                                    });
                                }

                                function bindEvents(row) {
                                    row.querySelectorAll('select[name="tendik_nik[]"], select[name="kode_kriteria[]"]').forEach(select => {
                                        select.addEventListener('change', updateKriteriaOptions);
                                    });
                                }

                                document.getElementById('add-row').addEventListener('click', function() {
                                    const container = document.getElementById('nilai-form-container');
                                    const firstRow = container.querySelector('.nilai-row');
                                    const newRow = firstRow.cloneNode(true);

                                    newRow.querySelectorAll('select, input').forEach(input => {
                                        input.value = '';
                                    });

                                    const removeBtn = newRow.querySelector('.remove-row');
                                    removeBtn.style.display = 'inline-block';
                                    removeBtn.addEventListener('click', function() {
                                        newRow.remove();
                                        updateKriteriaOptions();
                                    });

                                    container.appendChild(newRow);
                                    bindEvents(newRow);
                                    updateKriteriaOptions();
                                });

                                document.addEventListener('DOMContentLoaded', function() {
                                    document.querySelectorAll('.remove-row').forEach(btn => {
                                        btn.addEventListener('click', function() {
                                            btn.closest('.nilai-row').remove();
                                            updateKriteriaOptions();
                                        });
                                    });

                                    document.querySelectorAll('.nilai-row').forEach(row => bindEvents(row));
                                    updateKriteriaOptions();
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection