<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID film tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT * FROM film WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data film tidak ditemukan.";
    exit();
}

if (isset($_POST["update"])) {
    $judul = $_POST["judul"];
    $genre = $_POST["genre"];
    $jadwal = $_POST["jadwal"];
    $durasi = $_POST["durasi"];
    $sutradara = $_POST["sutradara"];
    $rating_usia = $_POST["rating_usia"];
    $status = $_POST["status"];
    $sinopsis = $_POST["sinopsis"];
    $aktor = $_POST["aktor"];

    $rating_film = trim($_POST['rating_film']);
    if ($rating_film === "") {
        $rating_film = null;
    }
    $gambar = $data['gambar']; 
    $folder = "gambar_film/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    if (!empty($_FILES["gambar"]["name"])) {
        $fileName = $_FILES["gambar"]["name"];
        $fileTmp = $_FILES["gambar"]["tmp_name"];
        $gambar = uniqid() . "_" . basename($fileName);
        move_uploaded_file($fileTmp, $folder . $gambar);
    }

    $query = "UPDATE film SET judul=?, genre=?, tanggal_rilis=?, durasi=?, sutradara=?, rating_usia=?, status=?, sinopsis=?, aktor=?, gambar=?, rating_film=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssissssssdi", 
        $judul, $genre, $jadwal, $durasi, $sutradara, $rating_usia,
        $status, $sinopsis, $aktor, $gambar, $rating_film, $id
    );


    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard_admin.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 p-6 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Update Film</h2>

        <form method="post" action="" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium text-gray-700">Judul Film</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Genre Film</label>
                <input type="text" name="genre" value="<?= htmlspecialchars($data['genre']) ?>" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Jadwal Film</label>
                <input type="date" name="jadwal" value="<?= htmlspecialchars($data['tanggal_rilis']) ?>" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Durasi (menit)</label>
                <input type="number" name="durasi" value="<?= $data['durasi'] ?>" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Sutradara</label>
                <input type="text" name="sutradara" value="<?= htmlspecialchars($data['sutradara']) ?>" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Rating Usia</label>
                <select name="rating_usia" class="w-full p-2 border rounded-md">
                    <option value="Semua Umur (SU)" <?= $data['rating_usia'] == 'Semua Umur (SU)' ? 'selected' : '' ?>>Semua Umur (SU)</option>
                    <option value="R13" <?= $data['rating_usia'] == 'R13' ? 'selected' : '' ?>>R13</option>
                    <option value="D17" <?= $data['rating_usia'] == 'D17' ? 'selected' : '' ?>>D17</option>
                    <option value="D21" <?= $data['rating_usia'] == 'D21' ? 'selected' : '' ?>>D21</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Status</label>
                <select name="status" class="w-full p-2 border rounded-md">
                    <option value="Tayang" <?= $data['status'] == 'Tayang' ? 'selected' : '' ?>>Tayang</option>
                    <option value="Segera Tayang" <?= $data['status'] == 'Segera Tayang' ? 'selected' : '' ?>>Segera Tayang</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Rating Film (0 - 5, boleh kosong)</label>
                <input type="text" name="rating_film" value="<?= htmlspecialchars($data['rating_film']) ?>" class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="w-full p-2 border rounded-md"><?= htmlspecialchars($data['sinopsis']) ?></textarea>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Aktor</label>
                <textarea name="aktor" rows="4" class="w-full p-2 border rounded-md"><?= htmlspecialchars($data['aktor']) ?></textarea>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Gambar Film (Kosongkan jika tidak ingin ganti)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full p-2 border rounded-md">
                <?php if (!empty($data['gambar'])): ?>
                    <img src="gambar_film/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar" class="mt-2 w-40 rounded shadow">
                <?php endif; ?>
            </div>

            <div>
                <button type="submit" name="update"
                        onclick="return confirm('Yakin ingin memperbarui data film ini?')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition w-full">
                    Update
                </button>
            </div>
        </form>

        <div class="mt-6">
            <a href="detail_film_admin.php?id=<?= $id ?>" class="text-blue-500 hover:underline">← Kembali ke detail film</a>
        </div>
    </div>

    <?php
    ?>

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