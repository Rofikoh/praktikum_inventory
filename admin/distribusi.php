<?php
include '../koneksi.php';

// 1. PROSES SIMPAN TRANSAKSI DISTRIBUSI (TAMBAH KELUAR/MASUK)
if (isset($_POST['tambah_distribusi'])) {
    $id_barang = $_POST['id_barang']; 
    $jenis = $_POST['jenis_distribusi']; // Berupa ENUM: 'Masuk' atau 'Keluar'
    $jumlah = (int)$_POST['jumlah'];
    // Disesuaikan: Database kamu menggunakan kolom bernama 'keterangan'
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $tanggal = $_POST['tanggal'];

    // Disesuaikan: Nama kolom adalah (id_barang, jenis_distribusi, jumlah, keterangan, tanggal_distribusi)
    $query = "INSERT INTO distribusi (id_barang, jenis_distribusi, jumlah, keterangan, tanggal_distribusi) 
              VALUES ('$id_barang', '$jenis', '$jumlah', '$keterangan', '$tanggal')";
              
    if (mysqli_query($koneksi, $query)) {
        // OTOMATIS MEMOTONG / MENAMBAH STOK BARANG UTAMA DI TABEL BARANG
        if ($jenis == 'Masuk') {
            mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id = '$id_barang'");
        } else if ($jenis == 'Keluar') {
            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id = '$id_barang'");
        }
        header("Location: distribusi.php?status=sukses");
        exit();
    }
}

// 2. PROSES HAPUS LOG DISTRIBUSI
if (isset($_GET['hapus'])) {
    $id_dist = $_GET['hapus'];
    // Disesuaikan: menghapus berdasarkan id_distribusi
    mysqli_query($koneksi, "DELETE FROM distribusi WHERE id_distribusi='$id_dist'");
    header("Location: distribusi.php");
    exit();
}

// DATA OPTION UNTUK SELECT BARANG
$list_barang = mysqli_query($koneksi, "SELECT id, nama_barang, stok FROM barang");

// 3. PERBAIKAN SINKRONISASI BARIS 45 (ORDER BY d.id_distribusi):
$query_riwayat = "SELECT d.*, b.nama_barang FROM distribusi d 
                  JOIN barang b ON d.id_barang = b.id 
                  ORDER BY d.tanggal_distribusi DESC, d.id_distribusi DESC";
$riwayat = mysqli_query($koneksi, $query_riwayat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Distribusi Logistik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 rounded shadow-sm">
    <h3 class="mb-4">🚚 Catatan Distribusi & Alur Barang</h3>

    <div class="row">
        <!-- FORM LOG TRANSAKSI -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm border-primary">
                <h5>Input Alur Logistik</h5>
                <form method="POST" action="">
                    <div class="mb-2">
                        <label class="form-label">Pilih Barang</label>
                        <select name="id_barang" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <?php while($b = mysqli_fetch_assoc($list_barang)) { ?>
                                <option value="<?= $b['id']; ?>"><?= $b['nama_barang']; ?> (Stok: <?= $b['stok']; ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jenis Distribusi</label>
                        <select name="jenis_distribusi" class="form-select" required>
                            <option value="Masuk">Barang Masuk (+)</option>
                            <option value="Keluar">Barang Keluar (-)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Keterangan / Tujuan / Asal</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Kirim ke Cabang B" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="datetime-local" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    <button type="submit" name="tambah_distribusi" class="btn btn-primary w-100">Proses Logistik</button>
                </form>
            </div>
        </div>

        <!-- TABEL LOG HISTORI -->
        <div class="col-md-8">
            <h5>Riwayat Keluar Masuk Barang</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Waktu & Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = mysqli_fetch_assoc($riwayat)) { ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($r['tanggal_distribusi'])); ?></td>
                        <td><strong><?= htmlspecialchars($r['nama_barang']); ?></strong></td>
                        <td>
                            <span class="badge <?= $r['jenis_distribusi'] == 'Masuk' ? 'bg-success' : 'bg-danger'; ?>">
                                <?= $r['jenis_distribusi']; ?>
                            </span>
                        </td>
                        <td><?= $r['jumlah']; ?></td>
                        <td><?= htmlspecialchars($r['keterangan']); ?></td>
                        <td>
                            <a href="distribusi.php?hapus=<?= $r['id_distribusi']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus log riwayat ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>