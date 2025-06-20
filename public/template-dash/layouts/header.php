<!DOCTYPE html>
<html lang="en" data-layout=horizontal>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Codescandy" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/logo.webp" />

    <!-- Color modes -->
    <script src="assets/js/color-modes.js"></script>

    <!-- Libs CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/libs/theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Dashboard | Sistem Pendukung Keputusan</title>
</head>

<body>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalDeleteText">Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <main id="main-wrapper" class="main-wrapper">

        <div class="header">
            <!-- navbar -->
            <div class="navbar-custom navbar navbar-expand-lg">
                <div class="container-fluid px-0">
                    <img src="assets/images/logo.webp" width="50px" alt="Image" />

                    <a id="nav-toggle" href="#!" class="ms-auto ms-md-0 me-0 me-lg-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-text-indent-left text-muted" viewBox="0 0 16 16">
                            <path
                                d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708zM7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </a>

                    <div class="d-none d-md-none d-lg-block p-2">
                        Sistem Pendukung Keputusan
                    </div>
                    <!--Navbar nav -->
                    <ul class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0">
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-ghost btn-icon rounded-circle" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                                    <i class="bi theme-icon-active"></i>
                                    <span class="visually-hidden bs-theme-text">Toggle theme</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                            <i class="bi theme-icon bi-sun-fill"></i>
                                            <span class="ms-2">Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                            <i class="bi theme-icon bi-moon-stars-fill"></i>
                                            <span class="ms-2">Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                                            <i class="bi theme-icon bi-circle-half"></i>
                                            <span class="ms-2">Auto</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- List -->
                        <li class="dropdown ms-2">
                            <a class="rounded-circle" href="#!" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    <img alt="avatar" src="assets/images/avatar.png" class="rounded-circle" />
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                <div class="px-4 pb-0 pt-2">
                                    <div class="lh-1">
                                        <h5 class="mb-1">John E. Grainger</h5>
                                        <a href="#!" class="text-inherit fs-6">View my profile</a>
                                    </div>
                                    <div class="dropdown-divider mt-3 mb-2"></div>
                                </div>

                                <ul class="list-unstyled">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="#!">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>
                                            Edit Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#!">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="activity"></i>
                                            Activity Log
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="#!">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>
                                            Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="index.html">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>
                                            Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- navbar horizontal -->
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
                            <li class="nav-link">
                                <a href="/">Dashboard</a>
                            </li>
                            <li class="nav-link active">
                                <a href="KelolaBobot.php">Kelola Bobot Kriteria</a>
                            </li>
                            <li class="nav-link">
                                <a href="/">Kelola Data Tendik</a>
                            </li>
                            <li class="nav-link">
                                <a href="/">Kelola Penilaian</a>
                            </li>
                            <li class="nav-link">
                                <a href="/">Lihat Perhitungan</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>