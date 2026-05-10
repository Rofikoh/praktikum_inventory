<?php
session_start();
include 'koneksi.php';
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = md5($_POST['password']);
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($query);


    $_SESSION['username'] = $username;
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = 'login';


    if ($data['role'] == "admin") {
        header("location:admin/index.php");
    } else {
        header("location:pengguna/index.php");
    }
} else {

    header("location:index.php?pesan=gagal");
}
