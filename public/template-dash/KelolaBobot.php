<?php include('layouts/header.php'); ?>
<div id="app-content">
    <div class="app-content-area pt-0 ">
        <div class="bg-primary pt-12 pb-21 "></div>
        <div class="container-fluid mt-n22 ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <!-- Page header -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0  text-white">Kelola Bobot Kriteria</h3>
                        </div>
                        <div>
                            <a href="editBobot.php" class="btn btn-white">+ Tambah Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Responsive tables -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Kriteria</th>
                                    <th scope="col">Bobot</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Disiplin</td>
                                    <td>0.3</td>
                                    <td>Benefit</td>
                                    <td>
                                        <a href="editBobot.php" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="setDeleteTarget('Disiplin')">Hapus</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Kerja Sama</td>
                                    <td>0.2</td>
                                    <td>Benefit</td>
                                    <td>
                                        <a href="editBobot.php" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="setDeleteTarget('Disiplin')">Hapus</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Inisiatif</td>
                                    <td>0.2</td>
                                    <td>Benefit</td>
                                    <td>
                                        <a href="editBobot.php" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="setDeleteTarget('Disiplin')">Hapus</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>Tanggung Jawab</td>
                                    <td>0.3</td>
                                    <td>Benefit</td>
                                    <td>
                                        <a href="editBobot.php" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="setDeleteTarget('Disiplin')">Hapus</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('layouts/footer.php'); ?>