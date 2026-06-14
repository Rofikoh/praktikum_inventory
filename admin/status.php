<?php
session_start();
include '../koneksi.php';


$data = mysqli_query(
    $koneksi,
    "SELECT * FROM status_barang"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Status Barang</title>

    <link rel="stylesheet" href="../AdminLTE-4.0.0-rc7/dist/css/adminlte.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card card-primary card-outline">

            <div class="card-header d-flex justify-content-between">

                <h3 class="card-title">
                    Data Status Barang
                </h3>

                <a href="tambah_status.php" class="btn btn-primary btn-sm">

                    Tambah Status

                </a>

            </div>

            <div class="card-body px-4">

                <table class="table table-bordered table-hover">

                    <tr class="table-dark">
                        <th width="60">No</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>

                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) {
                    ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= $row['nama_status']; ?>
                            </td>

                            <td>

                                <a href="edit_status.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="hapus_status.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus status ini?')">

                                    Hapus

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>

    </div>

</body>

</html>