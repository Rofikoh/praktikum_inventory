<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inventory | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="AdminLTE-4.0.0-rc7/dist/css/adminlte.css" />
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6f9;

        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header text-center">
                <h3 class="card-title float-none">Inventory System</h3>
            </div>
            <form action="login.php" method="post">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="username" id="username"
                                placeholder="Masukkan Username" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Masukkan Password" required />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    <p class="mt-3 mb-1 text-center">
                        <a href="register.php">Register a new membership</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>