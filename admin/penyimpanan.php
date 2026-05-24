<?php
include '../koneksi.php';  

// 1. PROSES TAMBAH DATA
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_penyimpanan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    
    $query = "INSERT INTO penyimpanan (nama_penyimpanan, lokasi) VALUES ('$nama', '$lokasi')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: penyimpanan.php?status=sukses");
    }
}

// 2. PROSES EDIT DATA
if (isset($_POST['ubah'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_penyimpanan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    
    $query = "UPDATE penyimpanan SET nama_penyimpanan='$nama', lokasi='$lokasi' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: penyimpanan.php?status=sukses");
    }
}

// 3. PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM penyimpanan WHERE id='$id'");
    header("Location: penyimpanan.php");
}

// 4. AMBIL DATA UNTUK TOMBOL EDIT
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res = mysqli_query($koneksi, "SELECT * FROM penyimpanan WHERE id='$id_edit'");
    $edit_data = mysqli_fetch_assoc($res);
}

// AMBIL SEMUA DATA UNTUK TABEL
$tampil = mysqli_query($koneksi, "SELECT * FROM penyimpanan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>CRUD Penyimpanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 rounded shadow-sm">
    <h3 class="mb-4">📦 Manajemen Lokasi Penyimpanan</h3>
    
    <div class="row">
        <!-- FORM INPUT (TAMBAH / EDIT) -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
                <h5><?= $edit_data ? 'Edit Lokasi' : 'Tambah Lokasi Baru' ?></h5>
                <form method="POST" action="">
                    <?php if ($edit_data) { ?>
                        <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
                    <?php } ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Penyimpanan / Gudang</label>
                        <input type="text" name="nama_penyimpanan" class="form-control" required value="<?= $edit_data ? $edit_data['nama_penyimpanan'] : ''; ?>" placeholder="Contoh: Gudang Utama A">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Detail Lokasi (Keterangan)</label>
                        <input type="text" name="lokasi" class="form-control" value="<?= $edit_data ? $edit_data['lokasi'] : ''; ?>" placeholder="Contoh: Blok B Lantai 2">
                    </div>
                    
                    <button type="submit" name="<?= $edit_data ? 'ubah' : 'tambah' ?>" class="btn <?= $edit_data ? 'btn-warning' : 'btn-primary' ?> w-100">
                        <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Data' ?>
                    </button>
                    <?php if ($edit_data) { echo '<a href="penyimpanan.php" class="btn btn-secondary w-100 mt-2">Batal</a>'; } ?>
                </form>
            </div>
        </div>

        <!-- TABEL DATA -->
        <div class="col-md-8">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Penyimpanan</th>
                        <th>Keterangan Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($tampil)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_penyimpanan']); ?></strong></td>
                        <td><?= htmlspecialchars($row['lokasi']); ?></td>
                        <td>
                            <a href="penyimpanan.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-info text-white">Edit</a>
                            <a href="penyimpanan.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus lokasi ini?')">Hapus</a>
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