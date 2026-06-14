<?php
session_start();
include '../koneksi.php';



// 1. Ambil data referensi untuk pilihan dropdown form
$query_status      = mysqli_query($koneksi, "SELECT * FROM status_barang");
$query_penyimpanan = mysqli_query($koneksi, "SELECT * FROM penyimpanan");
$query_vendor      = mysqli_query($koneksi, "SELECT * FROM vendor");

// 2. Proses Insert Data saat tombol simpan diklik
if (isset($_POST['submit'])) {
    $nama        = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $status_id   = mysqli_real_escape_string($koneksi, $_POST['status_id']);
    $penyimp_id  = mysqli_real_escape_string($koneksi, $_POST['penyimpanan_id']);
    $harga       = intval($_POST['harga']);
    $stok_awal   = intval($_POST['stok']);
    $limit_stok  = intval($_POST['limit_stok']);
    $vendor_id   = intval($_POST['vendor_id']);

    // Query INSERT disesuaikan persis dengan struktur tabel barang Anda
    $query_insert = "INSERT INTO barang 
                     (nama_barang, status_id, penyimpanan_id, harga_barang, stok, limit_stok, vendor_id) 
                     VALUES 
                     ('$nama', '$status_id', '$penyimp_id', '$harga', '$stok_awal', '$limit_stok', '$vendor_id')";

    if (mysqli_query($koneksi, $query_insert)) {
        echo "<script>
                alert('Master barang baru berhasil ditambahkan!');
                window.location='index.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menambah data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang | Master Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-box-seam"></i> Form Tambah Master Barang Baru</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Barang</label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Keyboard Logi"
                                    required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Barang</label>
                                    <select name="status_id" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        <?php while ($st = mysqli_fetch_assoc($query_status)) { ?>
                                            <option value="<?= $st['id']; ?>"><?= htmlspecialchars($st['nama_status']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                                    <select name="penyimpanan_id" class="form-select" required>
                                        <option value="">-- Pilih Gedung/Rak --</option>
                                        <?php while ($pny = mysqli_fetch_assoc($query_penyimpanan)) { ?>
                                            <option value="<?= $pny['id']; ?>">
                                                <?= htmlspecialchars($pny['nama_penyimpanan']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Harga Barang (Rp)</label>
                                    <input type="number" name="harga" class="form-control" placeholder="0" min="0"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Vendor / Supplier</label>
                                    <select name="vendor_id" class="form-select" required>
                                        <option value="0">Belum Set (Default)</option>
                                        <?php while ($vd = mysqli_fetch_assoc($query_vendor)) { ?>
                                            <option value="<?= $vd['id_vendor']; ?>">
                                                <?= htmlspecialchars($vd['nama_vendor']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Stok Awal</label>
                                    <input type="number" name="stok" class="form-control" value="0" min="0" required>
                                    <div class="form-text text-muted">Kuantitas awal saat barang didaftarkan.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Limit Stok Minimum</label>
                                    <input type="number" name="limit_stok" class="form-control" value="5" min="1"
                                        required>
                                    <div class="form-text text-muted">Batas peringatan kritis warna merah di dashboard.
                                    </div>
                                </div>
                            </div>

                            <hr class="mt-4">

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                                </a>
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Data Master
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>