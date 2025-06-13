<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['pengunjung_id'])) {
    header("Location: login_pengunjung.php");
    exit();
}

$pengunjung_id = $_SESSION['pengunjung_id'];

// Ambil semua kursi yang dimiliki pengunjung
$query = "SELECT film.judul, jadwal_tayang.tanggal, jadwal_tayang.jam_mulai, jadwal_tayang.studio,
                 tiket.tanggal_pembelian, detail_tiket.kursi_nomor, detail_tiket.id
          FROM detail_tiket
          JOIN tiket ON detail_tiket.id_tiket = tiket.id
          JOIN jadwal_tayang ON tiket.id_jadwal = jadwal_tayang.id
          JOIN film ON jadwal_tayang.film_id = film.id
          WHERE tiket.id_pengunjung = ?
          ORDER BY jadwal_tayang.tanggal ASC, detail_tiket.kursi_nomor ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pengunjung_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen p-6">

    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Daftar Tiket Anda</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                        <p><span class="font-semibold text-gray-700">🎟️ ID:</span> <?= htmlspecialchars($row['id']) ?></p>
                        <p><span class="font-semibold text-gray-700">🎬 Judul:</span> <?= htmlspecialchars($row['judul']) ?></p>
                        <p><span class="font-semibold text-gray-700">📅 Tanggal:</span> <?= htmlspecialchars($row['tanggal']) ?></p>
                        <p><span class="font-semibold text-gray-700">⏰ Jam:</span> <?= htmlspecialchars($row['jam_mulai']) ?></p>
                        <p><span class="font-semibold text-gray-700">🏢 Studio:</span> <?= htmlspecialchars($row['studio']) ?></p>
                        <p><span class="font-semibold text-gray-700">💺 Kursi ke-:</span> <?= htmlspecialchars($row['kursi_nomor']) ?></p>
                        <p><span class="font-semibold text-gray-700">🕓 Dipesan pada:</span> <?= htmlspecialchars($row['tanggal_pembelian']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Belum ada tiket yang dipesan.</p>
        <?php endif; ?>
    </div>

</body>
</html>
