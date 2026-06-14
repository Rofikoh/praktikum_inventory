<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Mengambil log distribusi yang di-join dengan tabel barang untuk mendapatkan nama_barang
$query_string = "SELECT distribusi.*, barang.nama_barang 
                 FROM distribusi 
                 INNER JOIN barang ON distribusi.id_barang = barang.id 
                 ORDER BY distribusi.tanggal_distribusi DESC";

$data_distribusi = mysqli_query($koneksi, $query_string);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Log Distribusi</title>
    <link rel="stylesheet" href="./css/adminlte.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
</head>

<body class="bg-body-tertiary p-4">

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-journal-text"></i> Riwayat Riil Distribusi Pengiriman</h5>
                <a href="input.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Log Transaksi
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal & Waktu</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Kuantitas</th>
                                <th>Keterangan / Tujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($data_distribusi) > 0) {
                                while ($row = mysqli_fetch_assoc($data_distribusi)) {
                                    $is_masuk = ($row['jenis_distribusi'] == 'Masuk');
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span
                                        class="text-muted"><?= date('d M Y - H:i', strtotime($row['tanggal_distribusi'])); ?>
                                        WIB</span></td>
                                <td><strong><?= htmlspecialchars($row['nama_barang']); ?></strong></td>
                                <td>
                                    <?php if ($is_masuk) { ?>
                                    <span class="badge bg-success"><i class="bi bi-box-arrow-in-down"></i> Masuk</span>
                                    <?php } else { ?>
                                    <span class="badge bg-danger"><i class="bi bi-box-arrow-up"></i> Keluar</span>
                                    <?php } ?>
                                </td>
                                <td><strong><?= $row['jumlah']; ?></strong> unit</td>
                                <td><?= htmlspecialchars($row['keterangan']); ?></td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center p-4 text-muted'>Belum ada riwayat transaksi pengiriman barang.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-house"></i> Kembali ke
                    Dashboard</a>
                <span class="text-muted small pt-1">Sistem Manajemen Stok Terintegrasi</span>
            </div>
        </div>
    </div>

</body>

</html>