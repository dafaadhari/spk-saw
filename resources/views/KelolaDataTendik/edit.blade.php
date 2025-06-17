@extends('layouts.app')
<title>Kelola Data Tendik | Sistem Pengambilan Keputusan</title>

@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0 text-white">Edit Data Tendik</h3>
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
                            <form class="row" action="{{ url('tendik/' . $tendik->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $tendik->nama) }}" placeholder="Masukkan Nama" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="number" name="nik" class="form-control" value="{{ old('nik', $tendik->nik) }}" maxlength="16" pattern="\d{1,16}" placeholder="Maksimal 16 Karakter" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                    <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $tendik->unit_kerja) }}" placeholder="Masukkan Unit Kerja" required>
                                </div>

                                <input type="hidden" name="user_id" value="{{ $tendik->user_id }}">

                                <div class="col-12">
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