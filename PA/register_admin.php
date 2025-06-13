<?php
include "koneksi.php";
session_start();

$message = "";

if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Password dan konfirmasi tidak cocok.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM admin WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "Email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_prepare($conn, "INSERT INTO admin (email, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($insert, "ss", $email, $hashed_password);

            if (mysqli_stmt_execute($insert)) {
                $message = "Registrasi berhasil. Silakan login.";
                header("Location: login_admin.php?registrasi=sukses");
                exit();
            } else {
                $message = "Gagal registrasi: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrasi Admin</h2>
        <?php if ($message): ?>
            <p class="text-red-500 mb-4"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Konfirmasi Password</label>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <button type="submit" name="register" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition duration-200">Register</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login_admin.php" class="text-blue-500 hover:underline">Login</a></p>
    </div>

    <script>
        document.querySelector('form').addEventListener("submit", function(e) {
            const email = document.querySelector("input[name='email']").value.trim();
            const pw = document.querySelector("input[name='password']").value.trim();
            const confirm = document.querySelector("input[name='confirm_password']").value.trim();

        const validasiEmail = /^[a-zA-Z0-9._%+-]+@adminbioskop\.com$/.test(email);
            if (!validasiEmail) {
            alert('Email harus menggunakan domain @adminbioskop.com');
            e.preventDefault();
            return;
        }
        if (pw.length < 10) {
            alert("Password harus minimal 10 karakter.");
            e.preventDefault();
            return;
        }
        if (pw !== confirm) {
            alert("Password dan konfirmasi tidak cocok.");
            e.preventDefault();
        }
    });
    </script>

</body>
</html>