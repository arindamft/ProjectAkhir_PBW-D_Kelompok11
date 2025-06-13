<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

if (!isset($_GET["id"])) {
    echo "ID film tidak ditemukan.";
    exit;
}

$id = $_GET["id"];

$query = "SELECT * FROM film WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    echo "Film tidak ditemukan.";
    exit;
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <?php if (!empty($row['gambar'])): ?>
            <img src="gambar_film/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Poster Film"
                 class="w-full h-full object-cover rounded mb-6">
        <?php else: ?>
            <img src="placeholder.jpg" alt="Tidak ada gambar"
                 class="w-full h-full object-cover rounded mb-6">
        <?php endif; ?>

        <h2 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($row["judul"]); ?></h2>

        <div class="space-y-2 text-gray-700">
            <p><span class="font-semibold">🎬 Genre:</span> <?php echo htmlspecialchars($row["genre"]); ?></p>
            <p><span class="font-semibold">📅 Tanggal Rilis:</span>
                <?php echo $row["tanggal_rilis"] !== "0000-00-00" ? htmlspecialchars($row["tanggal_rilis"]) : "Tanggal Rilis Film Belum Tersedia"; ?>
            </p>
            <p><span class="font-semibold">⏱️ Durasi:</span> <?php echo htmlspecialchars($row["durasi"]); ?> menit</p>
            <p><span class="font-semibold">🎥 Sutradara:</span> <?php echo htmlspecialchars($row["sutradara"]); ?></p>
            <p><span class="font-semibold">👪 Rating Usia:</span> <?php echo htmlspecialchars($row["rating_usia"]); ?></p>
            <p><span class="font-semibold">📌 Status:</span> <?php echo htmlspecialchars($row["status"]); ?></p>
            <p><span class="font-semibold">⭐ Rating Film:</span>
                <?php echo $row["rating_film"] !== null ? htmlspecialchars($row["rating_film"]) : "Belum Tersedia"; ?>
            </p>
            <p><span class="font-semibold">📖 Sinopsis:</span><br> <?php echo nl2br(htmlspecialchars($row["sinopsis"])); ?></p>
            <p><span class="font-semibold">👤 Aktor:</span> <?php echo htmlspecialchars($row["aktor"]); ?></p>
        </div>

        <div class="mt-6 flex flex-wrap gap-4">
            <a href="dashboard_admin.php"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded transition">
                ← Kembali ke Dashboard
            </a>
            <a href="update_film.php?id=<?php echo $id; ?>"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                ✏️ Edit Film
            </a>
            <a href="daftar_jadwal_admin.php?id=<?php echo $id; ?>"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
                📅 Daftar Jadwal
            </a>
            <a href="delete_film.php?id=<?php echo $id; ?>"
               onclick="return confirm('Yakin ingin menghapus film ini?')"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                🗑️ Hapus Film
            </a>
        </div>
    </div>

</body>
</html>
