<?php
include '../koneksi.php'; // Keluar folder admin untuk membaca file koneksi

// 1. PROSES TAMBAH VENDOR
if (isset($_POST['tambah'])) {
    $nama_vendor = mysqli_real_escape_string($koneksi, $_POST['nama_vendor']);
    $kontak_vendor = mysqli_real_escape_string($koneksi, $_POST['kontak_vendor']);
    $alamat_vendor = mysqli_real_escape_string($koneksi, $_POST['alamat_vendor']);
    
    $query = "INSERT INTO vendor (nama_vendor, kontak_vendor, alamat_vendor) 
              VALUES ('$nama_vendor', '$kontak_vendor', '$alamat_vendor')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: vendor.php?status=sukses-tambah");
        exit();
    }
}

// 2. PROSES EDIT VENDOR
if (isset($_POST['ubah'])) {
    $id_vendor = $_POST['id_vendor'];
    $nama_vendor = mysqli_real_escape_string($koneksi, $_POST['nama_vendor']);
    $kontak_vendor = mysqli_real_escape_string($koneksi, $_POST['kontak_vendor']);
    $alamat_vendor = mysqli_real_escape_string($koneksi, $_POST['alamat_vendor']);
    
    $query = "UPDATE vendor SET 
                nama_vendor='$nama_vendor', 
                kontak_vendor='$kontak_vendor', 
                alamat_vendor='$alamat_vendor' 
              WHERE id_vendor='$id_vendor'";
              
    if (mysqli_query($koneksi, $query)) {
        header("Location: vendor.php?status=sukses-ubah");
        exit();
    }
}

// 3. PROSES HAPUS VENDOR
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM vendor WHERE id_vendor='$id_hapus'");
    header("Location: vendor.php?status=sukses-hapus");
    exit();
}

// 4. AMBIL DATA UNTUK FORM EDIT (Koleksi data sebelum form diganti mode)
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res = mysqli_query($koneksi, "SELECT * FROM vendor WHERE id_vendor='$id_edit'");
    $edit_data = mysqli_fetch_assoc($res);
}

// AMBIL DATA UNTUK DITAMPILKAN DI TABEL
$tampil = mysqli_query($koneksi, "SELECT * FROM vendor ORDER BY id_vendor DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Supplier / Vendor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 rounded shadow-sm">
    <h3 class="mb-4">🏢 Manajemen Data Supplier &amp; Vendor</h3>
    
    <div class="row">
        <!-- FORM CONTROL (Bisa berubah dinamis jadi Tambah / Edit) -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm border-secondary">
                <h5><?= $edit_data ? 'Form Edit Vendor' : 'Tambah Vendor Baru' ?></h5>
                <hr>
                <form method="POST" action="">
                    <?php if ($edit_data) { ?>
                        <!-- ID Rahasia penanda baris mana yang diedit -->
                        <input type="hidden" name="id_vendor" value="<?= $edit_data['id_vendor']; ?>">
                    <?php } ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan / Vendor</label>
                        <input type="text" name="nama_vendor" class="form-control" required 
                               value="<?= isset($edit_data['nama_vendor']) ? htmlspecialchars($edit_data['nama_vendor']) : ''; ?>" 
                               placeholder="Contoh: PT. Sumber Makmur">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Kontak / Telepon</label>
                        <input type="text" name="kontak_vendor" class="form-control" required
                               value="<?= isset($edit_data['kontak_vendor']) ? htmlspecialchars($edit_data['kontak_vendor']) : ''; ?>" 
                               placeholder="Contoh: 021-xxxxxxxx">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap Kantor</label>
                        <textarea name="alamat_vendor" class="form-control" rows="3" required placeholder="Tulis alamat operasional..."><?= isset($edit_data['alamat_vendor']) ? htmlspecialchars($edit_data['alamat_vendor']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" name="<?= $edit_data ? 'ubah' : 'tambah' ?>" class="btn <?= $edit_data ? 'btn-warning' : 'btn-success' ?> w-100 text-white">
                        <?= $edit_data ? 'Simpan Perubahan' : 'Daftarkan Vendor' ?>
                    </button>
                    
                    <?php if ($edit_data) { ?>
                        <a href="vendor.php" class="btn btn-secondary w-100 mt-2">Batalkan Perbaikan</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- TABEL PENAMPIL DATABASE VENDOR -->
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Vendor</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($tampil) > 0) {
                            while ($row = mysqli_fetch_assoc($tampil)) { 
                        ?>
                            <tr>
                                <td><?= $row['id_vendor']; ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_vendor']); ?></strong></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['kontak_vendor']); ?></span></td>
                                <td><small class="text-muted"><?= htmlspecialchars($row['alamat_vendor']); ?></small></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="vendor.php?edit=<?= $row['id_vendor']; ?>" class="btn btn-sm btn-warning text-white">Edit</a>
                                        <a href="vendor.php?hapus=<?= $row['id_vendor']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus vendor ini? Semua barang terikat mungkin terpengaruh.')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            } 
                        } else {
                            echo '<tr><td colspan="5" class="text-center text-muted">Belum ada vendor terdaftar. Silakan tambah data di form samping.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>