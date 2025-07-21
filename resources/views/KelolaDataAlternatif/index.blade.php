@extends('layouts.app')

<title>Kelola Data Alternatif | Sistem Pendukung Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-22">
        </div>
        <div class="container-fluid mt-n22">
            <div class="row">
                <div class="col-12">
                    {{-- Alert responsif --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    @endif
                </div>

                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-start align-items-md-center gap-2 mb-4">
                        <h3 class="mb-0" style="color:white">Kelola Data Alternatif</h3>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('Alternatif/create') }}" class="btn btn-white">+ Tambah Data</a>
                            <!-- <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Excel
                            </button>
                            <a href="{{ route('Alternatif.export') }}" class="btn btn-success">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Export Excel
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="row mb-3 align-items-center">
                <div class=" col-md-8 mb-2 mb-md-0">
                    <label class="d-flex align-items-center gap-2 flex-wrap text-white">
                        Show
                        <select id="entriesSelect" class="form-select d-inline w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                </div>
            </div>

            {{-- Tabel --}}
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm" id="AlternatifTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenis Pegawai</th>
                                    <th>Jam Kerja/Tahun</th>
                                    <th>Jam Kerja/Bulan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $i => $row)
                                <tr>
                                    <th>{{ $i + 1 }}</th>
                                    <td>{{ $row->nama }}</td>
                                    <td>{{ $row->nik }}</td>
                                    <td>{{ $row->unit_kerja }}</td>
                                    <td>{{ $row->jenis_pegawai }}</td>
                                    <td>{{ $row->jam_kerja_tahunan }}</td>
                                    <td>{{ number_format($row->jam_kerja_bulanan, 2, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ url('Alternatif/' . $row->nik . '/edit') }}" class="btn btn-sm btn-primary mb-1" style="width:60px;">Edit</a>
                                        <a style="width:60px;" href="#" class="btn btn-danger btn-sm mb-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal"
                                            data-id="{{ $row->nik }}"
                                            data-nama="{{ $row->nama }}">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('Alternatif.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Import Data Alternatif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file_Alternatif" class="form-label">Pilih File Excel</label>
                        <input type="file" name="excel_file" id="excel_file_Alternatif" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">File harus berformat .xlsx, .xls, atau .csv dengan kolom: NIK, Nama, Unit Kerja, Jenis Pegawai, Jam Kerja Tahunan, Jam Kerja Bulanan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import Sekarang</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Script Filter & Pagination --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('confirmDeleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = deleteModal.querySelector('#deleteForm');
            form.action = '/Alternatif/' + id;
        });

        const table = document.querySelector('#AlternatifTable tbody');
        const rows = Array.from(table.querySelectorAll('tr'));
        const pagination = document.getElementById('pagination');
        const entriesSelect = document.getElementById('entriesSelect');
        const searchInput = document.getElementById('searchInput');

        let currentPage = 1;
        let rowsPerPage = parseInt(entriesSelect.value);
        let filteredRows = [...rows];

        function renderTableRows() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach(row => row.style.display = 'none');
            filteredRows.slice(start, end).forEach(row => row.style.display = '');
        }

        function renderPagination() {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            const prev = document.createElement('li');
            prev.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
            prev.innerHTML = `<span class="page-link">Previous</span>`;
            prev.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    update();
                }
            });
            pagination.appendChild(prev);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = 'page-item' + (i === currentPage ? ' active' : '');
                li.innerHTML = `<span class="page-link">${i}</span>`;
                li.addEventListener('click', () => {
                    currentPage = i;
                    update();
                });
                pagination.appendChild(li);
            }

            const next = document.createElement('li');
            next.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
            next.innerHTML = `<span class="page-link">Next</span>`;
            next.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    update();
                }
            });
            pagination.appendChild(next);
        }

        function update() {
            renderTableRows();
            renderPagination();
        }

        entriesSelect.addEventListener('change', () => {
            rowsPerPage = parseInt(entriesSelect.value);
            currentPage = 1;
            update();
        });

        searchInput.addEventListener('keyup', () => {
            const keyword = searchInput.value.toLowerCase();
            filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(keyword));
            currentPage = 1;
            update();
        });

        update();
    });
</script>