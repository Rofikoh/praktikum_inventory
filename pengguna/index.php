<?php
session_start();
include '../koneksi.php';


//terupdate
$nama_user = $_SESSION['nama_lengkap'];
$username  = $_SESSION['username'];
$role      = ucfirst($_SESSION['role']);


// 1. Ambil data untuk opsi dropdown filter
$query_status      = mysqli_query($koneksi, "SELECT * FROM status_barang");
$query_penyimpanan = mysqli_query($koneksi, "SELECT * FROM penyimpanan");
$query_vendor      = mysqli_query($koneksi, "SELECT * FROM vendor");

// 2. Tangkap data filter dari URL jika ada (Metode GET)
$search             = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_status      = isset($_GET['status_id']) ? mysqli_real_escape_string($koneksi, $_GET['status_id']) : '';
$filter_penyimpanan = isset($_GET['penyimpanan_id']) ? mysqli_real_escape_string($koneksi, $_GET['penyimpanan_id']) : '';
$filter_vendor      = isset($_GET['vendor_id']) ? mysqli_real_escape_string($koneksi, $_GET['vendor_id']) : '';

// 3. Menyusun query filter dinamis
$where_clauses = [];
if (!empty($search)) {
    $where_clauses[] = "barang.nama_barang LIKE '%$search%'";
}
if (!empty($filter_status)) {
    $where_clauses[] = "barang.status_id = '$filter_status'";
}
if (!empty($filter_penyimpanan)) {
    $where_clauses[] = "barang.penyimpanan_id = '$filter_penyimpanan'";
}
if (!empty($filter_vendor)) {
    $where_clauses[] = "barang.vendor_id = '$filter_vendor'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// 4. Query utama dengan penyesuaian kolom vendor.id_vendor dan barang.vendor_id
$query_string = "SELECT barang.*, 
                        status_barang.nama_status, 
                        penyimpanan.nama_penyimpanan, 
                        vendor.nama_vendor 
                 FROM barang 
                 LEFT JOIN status_barang ON barang.status_id = status_barang.id
                 LEFT JOIN penyimpanan ON barang.penyimpanan_id = penyimpanan.id
                 LEFT JOIN vendor ON barang.vendor_id = vendor.id_vendor" . $where_sql;

$data = mysqli_query($koneksi, $query_string);

// Baru
$query_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total_barang FROM barang");
$data_barang  = mysqli_fetch_assoc($query_barang);
$total_barang = $data_barang['total_barang'];

// 2. Hitung Persentase Barang yang Butuh Restok (Pengganti Bounce Rate)
// Mencari barang yang stoknya sudah menyentuh atau di bawah limit_stok
$query_kritis = mysqli_query($koneksi, "SELECT COUNT(*) AS total_kritis FROM barang WHERE stok <= limit_stok");
$data_kritis  = mysqli_fetch_assoc($query_kritis);
$total_kritis = $data_kritis['total_kritis'];

// Rumus presentase agar aman dari pembagian dengan angka 0 (division by zero)
$bounce_rate_persen = $total_barang > 0 ? round(($total_kritis / $total_barang) * 100) : 0;

// 3. Hitung Total User Registrations
$query_users = mysqli_query($koneksi, "SELECT COUNT(*) AS total_users FROM users");
$data_users  = mysqli_fetch_assoc($query_users);
$total_users = $data_users['total_users'];

// 4. Hitung Total Distribusi Barang (Pengganti Unique Visitors)
$query_distribusi = mysqli_query($koneksi, "SELECT COUNT(*) AS total_distribusi FROM distribusi");
$data_distribusi  = mysqli_fetch_assoc($query_distribusi);
$total_distribusi = $data_distribusi['total_distribusi'];

?>
<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />

    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->



                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="../assets/img/Gambar2.png" class="user-image rounded-circle shadow"
                                alt="User Image" />
                            <span class="d-none d-md-inline"><?= htmlspecialchars($username); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="../assets/img/Gambar2.png" class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    <?= htmlspecialchars($nama_user); ?> - <?= $role; ?>
                                    <small>Member since Agustus 2025</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                            </li>
                            <li class="user-footer">
                                <a href="profile.php" class="btn btn-outline-secondary">Profile</a>
                                <a href="logout.php" class="btn btn-outline-danger float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="./index.html" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">AdminLTE 4</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                        aria-label="Main navigation" data-accordion="false" id="navigation">
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                            <a href="stok.php" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Stok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="distribusi.php" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Distribusi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tambah_barang.php" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tambah Data</p>
                            </a>
                        </li>
                                <li class="nav-item">
                                    <a href="logout.php" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Logout</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 1-->
                            <div class="small-box text-bg-primary">


                            </div>
                            <!--end::Small Box Widget 1-->
                        </div>
                        <!--end::Col-->
                        <div class="app-content">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box text-bg-primary">
                                            <div class="inner">
                                                <h3><?php echo $total_barang; ?></h3>
                                                <p>Total Produk</p>
                                            </div>
                                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path
                                                    d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z">
                                                </path>
                                            </svg>
                                            <a href="barang.php"
                                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                                Lihat Produk <i class="bi bi-link-45deg"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box text-bg-success">
                                            <div class="inner">
                                                <h3><?php echo $bounce_rate_persen; ?><sup class="fs-5">%</sup></h3>
                                                <p>Stok Perlu Restok</p>
                                            </div>
                                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z">
                                                </path>
                                            </svg>
                                            <a href="restok.php"
                                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                                Cek Limit Stok <i class="bi bi-link-45deg"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box text-bg-warning">
                                            <div class="inner">
                                                <h3><?php echo $total_users; ?></h3>
                                                <p>User Terdaftar</p>
                                            </div>
                                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path
                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                                                </path>
                                            </svg>
                                            <a href="users.php"
                                                class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                                                Kelola User <i class="bi bi-link-45deg"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box text-bg-danger">
                                            <div class="inner">
                                                <h3><?php echo $total_distribusi; ?></h3>
                                                <p>Log Distribusi</p>
                                            </div>
                                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path clip-rule="evenodd" fill-rule="evenodd"
                                                    d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z">
                                                </path>
                                                <path clip-rule="evenodd" fill-rule="evenodd"
                                                    d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z">
                                                </path>
                                            </svg>
                                            <a href="distribusi.php"
                                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                                Lihat Riwayat <i class="bi bi-link-45deg"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!-- /.contacts-list-info -->
                        </a>
                        </li>
                        <!-- End Contact Item -->
                        </ul>
                        <!-- /.contacts-list -->
                    </div>
                    <!-- /.direct-chat-pane -->
                </div>
            </div>

            <!--awal-->
            <div class="px-4">
                <div class="card-body">

                    <!-- SECTION FILTER DIATAS TABEL -->
                    <form method="GET" action="" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..."
                                value="<?= htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status_id" class="form-control">
                                <option value="">-- Semua Status --</option>
                                <?php while ($st = mysqli_fetch_assoc($query_status)) { ?>
                                <option value="<?= $st['id']; ?>" <?= $filter_status == $st['id'] ? 'selected' : ''; ?>>
                                    <?= $st['nama_status']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="vendor_id" class="form-control">
                                <option value="">-- Semua Vendor --</option>
                                <?php while ($vd = mysqli_fetch_assoc($query_vendor)) { ?>
                                <option value="<?= $vd['id_vendor']; ?>"
                                    <?= $filter_vendor == $vd['id_vendor'] ? 'selected' : ''; ?>>
                                    <?= $vd['nama_vendor']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>

                    <!-- DATA TABEL BARANG -->
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Vendor</th>
                                <th>Status</th>
                                <th>Penyimpanan</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Limit Stok</th>
                                <?php if ($_SESSION['role'] == 'admin') { ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) {
                                // Logika pewarnaan jika stok kritis menyentuh/kurang dari limit
                                $stok_kritis = ($row['stok'] <= $row['limit_stok']);
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_barang']); ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_vendor'] ?? 'Belum Set'); ?></td>
                                <td>
                                    <!-- Antisipasi data lama seperti 'aktif' agar tidak merusak tampilan text layout -->
                                    <?= htmlspecialchars($row['nama_status'] ?? $row['status_id']); ?>
                                </td>
                                <td>
                                    <!-- Antisipasi data lama seperti 'gudang A' agar aman dari blank data -->
                                    <?= htmlspecialchars($row['nama_penyimpanan'] ?? $row['penyimpanan_id']); ?>
                                </td>
                                <td>Rp <?= number_format($row['harga_barang'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($stok_kritis) { ?>
                                    <span class="badge bg-danger">Sisa: <?= $row['stok']; ?></span>
                                    <?php } else { ?>
                                    <span class="badge bg-success"><?= $row['stok']; ?></span>
                                    <?php } ?>
                                </td>
                                <td><span class="text-muted"><?= $row['limit_stok']; ?></span></td>

                                <?php if ($_SESSION['role'] == 'admin') { ?>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="edit_barang.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>
                                        <a href="hapus_barang.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus data?')">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>


    </div>
    </div>
    </div>
    <!-- /.Start col -->
    </div>
    <!-- /.row (main row) -->
    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
            Copyright &copy; 2014-2026&nbsp;
            <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
            sidebarWrapper &&
            OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
            !isMobile
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>

    <!-- sortablejs -->
    <script>
    new Sortable(document.querySelector('.connectedSortable'), {
        group: 'shared',
        handle: '.card-header',
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
    });
    </script>

    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>

    <!-- ChartJS -->
    <script>
    // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
    // IT'S ALL JUST JUNK FOR DEMO
    // ++++++++++++++++++++++++++++++++++++++++++

    const sales_chart_options = {
        series: [{
                name: 'Digital Goods',
                data: [28, 48, 40, 19, 86, 27, 90],
            },
            {
                name: 'Electronics',
                data: [65, 59, 80, 81, 56, 55, 40],
            },
        ],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        legend: {
            show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
        },
        xaxis: {
            type: 'datetime',
            categories: [
                '2023-01-01',
                '2023-02-01',
                '2023-03-01',
                '2023-04-01',
                '2023-05-01',
                '2023-06-01',
                '2023-07-01',
            ],
        },
        tooltip: {
            x: {
                format: 'MMMM yyyy',
            },
        },
    };

    const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
    );
    sales_chart.render();
    </script>

    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>

    <!-- jsvectormap -->
    <script>
    // World map by jsVectorMap
    new jsVectorMap({
        selector: '#world-map',
        map: 'world',
    });

    // Sparkline charts
    const option_sparkline1 = {
        series: [{
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
        }, ],
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true,
            },
        },
        stroke: {
            curve: 'straight',
        },
        fill: {
            opacity: 0.3,
        },
        yaxis: {
            min: 0,
        },
        colors: ['#DCE6EC'],
    };

    const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
    sparkline1.render();

    const option_sparkline2 = {
        series: [{
            data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
        }, ],
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true,
            },
        },
        stroke: {
            curve: 'straight',
        },
        fill: {
            opacity: 0.3,
        },
        yaxis: {
            min: 0,
        },
        colors: ['#DCE6EC'],
    };

    const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
    sparkline2.render();

    const option_sparkline3 = {
        series: [{
            data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
        }, ],
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true,
            },
        },
        stroke: {
            curve: 'straight',
        },
        fill: {
            opacity: 0.3,
        },
        yaxis: {
            min: 0,
        },
        colors: ['#DCE6EC'],
    };

    const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
    sparkline3.render();
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>