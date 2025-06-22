@extends('layouts.app')

<title>Kelola Bobot Kriteria | Sistem Pendukung Keputusan</title>
@section('content')

<style>
    .pagination .page-item .page-link {
        cursor: pointer;
    }

    .data-controls {
        flex-wrap: wrap;
    }
</style>

<div id="app-content">
    <div class="app-content-area pt-0 ">
        <div class="bg-primary pt-12 pb-21 "></div>
        <div class="container-fluid mt-n22 ">
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
                        <h3 class="mb-0" style="color:white">Kelola Bobot Kriteria</h3>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('kriteria.tambah') }}" class="btn btn-white">+ Tambah Data</a>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Excel
                            </button>
                            <a href="{{ route('export.excel') }}" class="btn btn-success">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Filter & Table -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-8 mb-2 mb-md-0">
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

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table" id="kriteriaTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Kriteria</th>
                                    <th scope="col">Bobot</th>
                                    <th scope="col">Sumber</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kriterias as $index => $kriteria)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $kriteria->nama }}</td>
                                    <td>{{ $kriteria->weight }}</td>
                                    <td>{{ $kriteria->sumber }}</td>
                                    <td>
                                        <a style="width: 60px;" href="{{ route('kriteria.edit', $kriteria->kode_kriteria) }}" class="btn btn-sm btn-primary mt-1">Edit</a>
                                        <button style="width: 60px;" type="button" class=" mt-1 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $kriteria->kode_kriteria }}" data-nama="{{ $kriteria->nama }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Belum ada data kriteria.</td>
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

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Konfirmasi Hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kriteria <strong id="kriteriaNama"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('import.indexExcel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Import Data Kriteria dari Excel</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel</label>
                        <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xls,.xlsx,.csv" required>
                        <small class="text-muted">File harus berformat .xlsx, .xls, atau .csv</small>
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

<script>
    const modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id'); // ini sekarang kode_kriteria
        const nama = button.getAttribute('data-nama');

        document.getElementById('kriteriaNama').textContent = nama;
        document.getElementById('deleteForm').action = `/kriteria/${id}`;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('#kriteriaTable tbody');
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
@endsection