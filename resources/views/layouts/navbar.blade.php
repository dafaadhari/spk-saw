<!-- navbar -->
<div class="navbar-horizontal nav-dashboard">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-default navbar-dropdown p-0 py-lg-2">
            <div class="d-flex d-lg-block justify-content-between align-items-center w-100 w-lg-0 py-2 px-4 px-md-2 px-lg-0">
                <span class="d-lg-none">Menu</span>
                <!-- Button -->
                <button
                    class="navbar-toggler collapsed ms-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbar-default"
                    aria-controls="navbar-default"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="icon-bar top-bar mt-0"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>
            </div>
            <!-- Collapse -->
            <div class="collapse navbar-collapse px-6 px-lg-0" id="navbar-default">
                <ul class="navbar-nav">
                    <li class="nav-link {{ Request::is('dashboard') ? 'active fw-bold' : '' }}">
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-link {{ Request::is('kriteria*') ? 'active fw-bold' : '' }}">
                        <a href="{{ url('/kriteria') }}">Kelola Bobot Kriteria</a>
                    </li>
                    <li class="nav-link {{ Request::is('Alternatif*') ? 'active fw-bold' : '' }}">
                        <a href="{{ url('/Alternatif') }}">Kelola Data Alternatif</a>
                    </li>
                    <li class="nav-link {{ Request::is('nilai*') ? 'active fw-bold' : '' }}">
                        <a href="{{ url('/nilai') }}">Kelola Penilaian</a>
                    </li>

                    <!-- Tetap tampilkan menu Lihat Perhitungan -->
                    <li class="nav-link {{ Request::is('lihatPerhitungan') ? 'active fw-bold' : '' }}">
                        <a href="{{ url('/lihatPerhitungan') }}">Lihat Perhitungan</a>
                    </li> 
                </ul>
            </div>

        </nav>
    </div>
</div>