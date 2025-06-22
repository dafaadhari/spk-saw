@extends('layouts.app')

<title>Kelola Penilaian | Sistem Pendukung Keputusan</title>

@section('content')
<div id="app-content">
     <div class="app-content-area pt-0">
          <div class="bg-primary pt-12 pb-21"></div>
          <div class="container-fluid mt-n22">
               @if (session('success'))
               <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
               </div>
               @endif

               <script>
                    setTimeout(function() {
                         var alert = document.getElementById('success-alert');
                         if (alert) {
                              alert.classList.remove('show');
                              alert.classList.add('fade');
                              setTimeout(() => alert.remove(), 500);
                         }
                    }, 5000);
               </script>

               <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                         <div class="d-flex flex-wrap justify-content-between align-items-md-center align-items-start gap-2 mb-5">
                              <div class="mb-2 mb-lg-0">
                                   <h3 class="mb-0 " style="color:white">Kelola Penilaian</h3>
                              </div>
                              <div class="d-flex flex-wrap gap-2">
                                   <a href="{{ url('/nilai/create') }}" class="btn btn-white">+ Tambah Data</a>
                                   <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalImport">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Import Excel
                                   </button>
                                   <a href="{{ url('/nilai/export') }}" class="btn btn-success">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Export Excel
                                   </a>
                              </div>
                         </div>
                    </div>
               </div>

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
                    <div class="col-xl-12">
                         <div class="table-responsive">
                              <table class="table table-bordered align-middle" id="penilaianTable">
                                   <thead class="table-light">
                                        <tr>
                                             <th>No</th>
                                             <th>NIK</th>
                                             <th>Nama</th>
                                             @foreach($kriterias as $kriteria)
                                             <th class="text-uppercase bg-secondary text-white text-center">{{ $kriteria->nama }}</th>
                                             @endforeach
                                             <th>Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @forelse($tendiks as $index => $tendik)
                                        <tr>
                                             <td>{{ $index + 1 }}</td>
                                             <td>{{ $tendik->nik }}</td>
                                             <td>{{ $tendik->nama }}</td>

                                             @foreach($kriterias as $kriteria)
                                             @php
                                             $kode = strtoupper($kriteria->kode_kriteria);
                                             $nilai = $tendik->nilais->firstWhere(fn($n) => strtoupper($n->kode_kriteria) === $kode);
                                             @endphp
                                             <td class="text-center">{{ $nilai->value ?? '-' }}</td>
                                             @endforeach

                                             <td class="d-flex gap-2">
                                                  <!-- Tombol Edit -->
                                                  <a href="{{ url('/nilai/tendik/' . $tendik->nik . '/edit') }}" class="btn btn-sm btn-primary" style="width:60px;">Edit</a>

                                                  <!-- Tombol Hapus (trigger modal) -->
                                                  <button type="button" class="btn btn-danger btn-sm" style="width:60px;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $tendik->nik }}">
                                                       Hapus
                                                  </button>

                                                  <!-- Modal Konfirmasi Hapus -->
                                                  <div class="modal fade" id="deleteModal{{ $tendik->nik }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $tendik->nik }}" aria-hidden="true">
                                                       <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                 <div class="modal-header">
                                                                      <h5 class="modal-title" id="deleteModalLabel{{ $tendik->nik }}">Konfirmasi Hapus</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                 </div>
                                                                 <div class="modal-body">
                                                                      Yakin ingin menghapus <strong>semua nilai</strong> untuk <strong>{{ $tendik->nama }}</strong>?
                                                                 </div>
                                                                 <div class="modal-footer">
                                                                      <form action="{{ url('/nilai/tendik/' . $tendik->nik) }}" method="POST">
                                                                           @csrf
                                                                           @method('DELETE')
                                                                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                           <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                                      </form>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </td>
                                        </tr>
                                        @empty
                                        <tr>
                                             <td colspan="{{ 4 + $kriterias->count() }}" class="text-center">Belum ada data penilaian.</td>
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

<!-- Modal Import -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <form action="{{ url('/nilai/import') }}" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="modalImportLabel">Import Data Penilaian</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                         <div class="mb-3">
                              <label for="excel_file_nilai" class="form-label">Pilih File Excel</label>
                              <input type="file" name="excel_file" id="excel_file_nilai" class="form-control" accept=".xlsx,.xls,.csv" required>
                              <small class="text-muted">File harus berformat .xlsx, .xls, atau .csv <br> dengan kolom: NIK, Nama, KODE KRITERIA</small>
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



@endsection

<script>
     document.addEventListener('DOMContentLoaded', function() {
          const table = document.querySelector('#penilaianTable tbody');
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