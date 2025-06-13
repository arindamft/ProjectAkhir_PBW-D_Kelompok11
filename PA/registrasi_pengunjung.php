<?php
include "koneksi.php";
$message = "";

if (isset($_POST['registrasi'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pw = $_POST['pw'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if ($pw !== $confirm_password) {
        $message = "Password dan konfirmasi tidak cocok.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM pengunjung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "<p class='text-red-500'>Email sudah terdaftar. Gunakan email lain.</p>";
        } else {
            $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO pengunjung (nama, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $hashed_pw);
            
            if ($stmt->execute()) {
                header("Location: login_pengunjung.php?registrasi=sukses");
                exit();
            } else {
                $message = "<p class='text-red-500'>Terjadi kesalahan. Coba lagi nanti.</p>";
            }
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
    <title>Registration Page</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrasi</h2>
        <?php if (!empty($message)) echo $message; ?>
        <?php if (isset($_GET['registrasi']) && $_GET['registrasi'] === 'sukses'): ?>
            <p class='text-green-500 mb-4'>Registrasi berhasil! Silakan login.</p>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-4">
                <label class="block text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan Nama Anda" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" placeholder="Masukkan Alamat Email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="pw" placeholder="Masukkan Password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Konfirmasi Password</label>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <button type="submit" name="registrasi" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition duration-200">Daftar</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login_pengunjung.php" class="text-blue-500 hover:underline">Login di sini</a></p>
    </div>

    <script>
        document.querySelector('form').addEventListener("submit", function(e) {
            const nama = document.querySelector("input[name='nama']").value.trim();
            const email = document.querySelector("input[name='email']").value.trim();
            const pw = document.querySelector("input[name='pw']").value.trim();

            const validasiNama = /^[A-Za-z\s]+$/.test(nama);
            if (!validasiNama) {
                alert('Nama hanya boleh mengandung huruf dan spasi.');
                e.preventDefault();
                return;
            }
            const validasiEmail = /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email);
            if (!validasiEmail) {
                alert('Email harus menggunakan email Google.');
                e.preventDefault();
                return;
            }
            if (pw.length < 8) {
                alert("Password harus minimal 8 karakter.");
                e.preventDefault();
                return;
            }
        })
    </script>
</body>
</html>