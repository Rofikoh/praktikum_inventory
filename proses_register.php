<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = md5($_POST['password']);

mysqli_query(
    $koneksi,
    "INSERT INTO users
(nama_lengkap, username, password, role)
VALUES
('$nama','$username','$password','pengguna')
"
);

echo "
<script>
alert('Register berhasil');
window.location='login.php';
</script>
";