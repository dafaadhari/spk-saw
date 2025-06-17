@extends('layouts.app')

<title>Kelola Penilaian | Sistem Pengambilan Keputusan</title>

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
                         <div class="d-flex justify-content-between align-items-center mb-5">
                              <div class="mb-2 mb-lg-0">
                                   <h3 class="mb-0 text-white">Kelola Penilaian</h3>
                              </div>
                              <div>
                                   <a href="{{ url('/nilai/create') }}" class="btn btn-white">+ Tambah Data</a>
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
                                             <th>Nama Tendik</th>
                                             <th>Nama Kriteria</th>
                                             <th>Nilai</th>
                                             <th>Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @foreach($data as $index => $nilai)
                                        <tr>
                                             <td>{{ $index + 1 }}</td>
                                             <td>{{ $nilai->tendik->nama ?? '-' }}</td>
                                             <td>{{ $nilai->kriteria->nama ?? '-' }}</td>
                                             <td>{{ $nilai->value }}</td>
                                             <td>
                                                  <a href="{{ url('/nilai/' . $nilai->id . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
                                                  <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $nilai->id }}">
                                                       Hapus
                                                  </button>
                                                  <div class="modal fade" id="deleteModal{{ $nilai->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $nilai->id }}" aria-hidden="true">
                                                       <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                 <div class="modal-header">
                                                                      <h5 class="modal-title" id="deleteModalLabel{{ $nilai->id }}">Konfirmasi Hapus</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                 </div>
                                                                 <div class="modal-body">
                                                                      Apakah kamu yakin ingin menghapus nilai ini?
                                                                 </div>
                                                                 <div class="modal-footer">
                                                                      <form action="{{ url('/nilai/' . $nilai->id) }}" method="POST">
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
                                        @endforeach

                                        @if($data->isEmpty())
                                        <tr>
                                             <td colspan="5" class="text-center">Belum ada data nilai.</td>
                                        </tr>
                                        @endif
                                   </tbody>
                              </table>
                              <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
                         </div>
                    </div>
               </div>
          </div>
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