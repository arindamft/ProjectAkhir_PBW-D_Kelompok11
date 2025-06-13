<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['pengunjung_id'])) {
    header("Location: login_pengunjung.php");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Film</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-100 text-gray-800">

    <div class="max-w-4xl mx-auto mt-10 bg-white rounded-lg shadow-md overflow-hidden">
        <?php if (!empty($row['gambar'])): ?>
            <img src="gambar_film/<?php echo htmlspecialchars($row['gambar']); ?>" 
                 alt="Poster <?php echo htmlspecialchars($row['judul']); ?>" 
                 class="w-full h-full object-cover">
        <?php else: ?>
            <img src="placeholder.jpg" alt="Tidak ada gambar" class="w-full h-full object-cover bg-gray-200">
        <?php endif; ?>

        <div class="p-6">
            <h2 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($row["judul"]); ?></h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <p><span class="font-semibold">🎬 Genre:</span> <?php echo htmlspecialchars($row["genre"]); ?></p>
                <p><span class="font-semibold">📅 Tanggal Rilis:</span>
                    <?php echo $row["tanggal_rilis"] !== "0000-00-00" ? htmlspecialchars($row["tanggal_rilis"]) : "Belum Tersedia"; ?>
                </p>
                <p><span class="font-semibold">⏱️ Durasi:</span> <?php echo htmlspecialchars($row["durasi"]); ?> menit</p>
                <p><span class="font-semibold">🎥 Sutradara:</span> <?php echo htmlspecialchars($row["sutradara"]); ?></p>
                <p><span class="font-semibold">👤 Rating Usia:</span> <?php echo htmlspecialchars($row["rating_usia"]); ?></p>
                <p><span class="font-semibold">📺 Status:</span> <?php echo htmlspecialchars($row["status"]); ?></p>
                <p><span class="font-semibold">⭐ Rating Film:</span>
                    <?php echo $row["rating_film"] !== null ? htmlspecialchars($row["rating_film"]) : "Belum Tersedia"; ?>
                </p>
            </div>

            <div class="mt-6">
                <p class="mb-2 font-semibold text-gray-800">📖 Sinopsis:</p>
                <p class="text-gray-700 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($row["sinopsis"])); ?></p>
            </div>

            <div class="mt-6">
                <p class="mb-2 font-semibold text-gray-800">👥 Aktor:</p>
                <p><?php echo htmlspecialchars($row["aktor"]); ?></p>
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="dashboard_pengunjung.php" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition">← Kembali</a>
                <a href="daftar_jadwal_pengunjung.php?id=<?php echo $id; ?>" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">🎟️ Lihat Jadwal</a>
            </div>
        </div>
    </div>

</body>
</html>
