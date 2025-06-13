<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}
$message = "";

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $genre = trim($_POST['genre']);
    $tanggal_rilis = $_POST['tanggal_rilis'];
    $durasi = intval($_POST['durasi']);
    $sutradara = trim($_POST['sutradara']);
    $rating_usia = trim($_POST['rating_usia']);
    $status = $_POST['status'];
    $sinopsis = trim($_POST['sinopsis']);
    $aktor = trim($_POST['aktor']);
    $rating_film = trim($_POST['rating_film']);

    // Validasi rating (maksimal 5.0)
    if ($rating_film === "") {
        $rating_film = null;
    } elseif (!is_numeric($rating_film) || $rating_film < 0 || $rating_film > 5) {
        $message = "<p style='color:red;'>Rating film harus berupa angka antara 0 - 5.0</p>";
    }

    // Upload Gambar
    $gambar = "";
    if (!empty($_FILES["gambar"]["name"])) {
        $target_dir = "gambar_film/";
        $gambar = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;

        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
    }

    // Simpan ke DB
    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO film (judul, genre, tanggal_rilis, durasi, sutradara, rating_usia, status, sinopsis, aktor, gambar, rating_film) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissssssd", $judul, $genre, $tanggal_rilis, $durasi, $sutradara, $rating_usia, $status, $sinopsis, $aktor, $gambar, $rating_film);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Film berhasil ditambahkan.</p>";
            header("Location: dashboard_admin.php");
            exit;
        } else {
            $message = "<p style='color:red;'>Gagal menyimpan film.</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen">
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Form Tambah Film</h2>
            <a href="dashboard_admin.php" class="text-blue-500 hover:underline">← Kembali ke dashboard</a>
        </div>

        <?php if (isset($message)) echo "<p class='mb-4 text-red-500'>$message</p>"; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium">Judul</label>
                <input type="text" name="judul" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Genre</label>
                <input type="text" name="genre" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Tanggal Rilis</label>
                <input type="date" name="tanggal_rilis" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Durasi (menit)</label>
                <input type="number" name="durasi" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Sutradara</label>
                <input type="text" name="sutradara" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Rating Usia</label>
                <select name="rating_usia" class="w-full p-2 border rounded-md">
                    <option value="Semua Umur (SU)">Semua Umur (SU)</option>
                    <option value="R13">R13</option>
                    <option value="D17">D17</option>
                    <option value="D21">D21</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full p-2 border rounded-md">
                    <option value="Tayang">Tayang</option>
                    <option value="Segera Tayang">Segera Tayang</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">Rating Film (0 - 5, boleh kosong)</label>
                <input type="text" name="rating_film" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="w-full p-2 border rounded-md"></textarea>
            </div>

            <div>
                <label class="block font-medium">Aktor</label>
                <textarea name="aktor" rows="3" class="w-full p-2 border rounded-md"></textarea>
            </div>

            <div>
                <label class="block font-medium">Gambar Poster</label>
                <input type="file" name="gambar" class="w-full p-2 border rounded-md">
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition">
                Simpan Film
            </button>
        </form>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const durasi = parseInt(document.querySelector("input[name='durasi']").value);
        const sutradara = document.querySelector("input[name='sutradara']").value.trim();
        const aktor = document.querySelector("textarea[name='aktor']").value.trim();
        const rating = document.querySelector("input[name='rating_film']").value.trim();
        const gambar = document.querySelector("input[name='gambar']").value;

        // Durasi harus lebih dari 0
        if (isNaN(durasi) || durasi <= 0) {
            alert("Durasi harus lebih dari 0 menit.");
            e.preventDefault();
            return;
        }

        // Sutradara & Aktor hanya huruf dan simbol tertentu
        const validasiNama = /^[A-Za-z\s.,'\-]+$/;
        if (!validasiNama.test(sutradara)) {
            alert("Nama sutradara hanya boleh berisi huruf, spasi, titik, koma, tanda petik satu, dan tanda hubung.");
            e.preventDefault();
            return;
        }

        if (!validasiNama.test(aktor)) {
            alert("Nama aktor hanya boleh berisi huruf, spasi, titik, koma, tanda petik satu, dan tanda hubung.");
            e.preventDefault();
            return;
        }

        // Validasi rating film jika diisi
        if (rating !== "") {
            const num = parseFloat(rating);
            if (isNaN(num) || num < 0 || num > 5) {
                alert("Rating film harus berupa angka antara 0 sampai 5.");
                e.preventDefault();
                return;
            }
        }

        // Validasi gambar
        if (gambar !== "") {
            const ext = gambar.split('.').pop().toLowerCase();
            const allowed = ["jpg", "jpeg", "svg", "png", "webp", "avif"];
            if (!allowed.includes(ext)) {
                alert("Format gambar tidak valid. Gunakan: jpg, jpeg, svg, png, webp, atau avif.");
                e.preventDefault();
                return;
            }
        }
    });
    </script>
</body>
</html>