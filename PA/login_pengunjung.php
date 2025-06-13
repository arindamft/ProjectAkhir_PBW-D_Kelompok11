<?php
include "koneksi.php";
session_start();

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pw = $_POST['pw'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p class='text-red-500'>Format email tidak valid.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id, nama, password FROM pengunjung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $nama, $hashed_pw);
            $stmt->fetch();

            if (password_verify($pw, $hashed_pw)) {
                $_SESSION['pengunjung_id'] = $id;
                $_SESSION['pengunjung_nama'] = $nama;
                header("Location: dashboard_pengunjung.php");
                exit();
            } else {
                $message = "<p class='text-red-500'>Email atau password salah.</p>"; // Pesan umum untuk keamanan
            }
        } else {
            $message = "<p class='text-red-500'>Email atau password salah.</p>"; // Pesan umum untuk keamanan
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Login ke Absolute Cinema</h2>

        <?php if (!empty($message)) echo '<div class="mb-4 text-red-500 text-sm text-center">' . $message . '</div>'; ?>

        <form method="post" action="">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" placeholder="Masukkan Alamat Email" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="pw" placeholder="Masukkan Password" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" name="login"
                    class="w-full bg-blue-500 text-white font-semibold py-2 rounded-md hover:bg-blue-600 transition duration-300">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-700">
            Belum punya akun?
            <a href="registrasi_pengunjung.php" class="text-blue-600 hover:underline">Daftar sekarang!</a>
        </p>
    </div>

</body>
</html>
