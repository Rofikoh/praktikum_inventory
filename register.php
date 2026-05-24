<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inventory | Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />

    <!-- AdminLTE -->
    <link rel="stylesheet" href="AdminLTE-4.0.0-rc7/dist/css/adminlte.css" />

    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6f9;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <div class="card card-success card-outline mb-4">
            <div class="card-header text-center">
                <img src="assets/img/gambar1.jpeg" width="80" height="80" alt="icon">
                
                <h3 class="card-title float-none">
                    Nusantara Tech
                </h3>
            </div>

            <form action="proses_register.php" method="post">

                <div class="card-body">

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <div class="input-group">
                            <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap"
                                required />
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">
                            Username
                        </label>

                        <div class="input-group">
                            <input type="text" class="form-control" name="username" placeholder="Masukkan Username"
                                required />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Masukkan Password"
                                required />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Confirm Password
                        </label>

                        <div class="input-group">
                            <input type="password" class="form-control" name="confirm_password"
                                placeholder="Konfirmasi Password" required />
                        </div>
                    </div>

                </div>

                <div class="card-footer">

                    <button type="submit" class="btn btn-success w-100">
                        Register
                    </button>

                    <p class="mt-3 mb-1 text-center">
                        <a href="login.php">
                            I already have a membership
                        </a>
                    </p>

                </div>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>