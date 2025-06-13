<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['pengunjung_id'])) {
    header("Location: login_pengunjung.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID film tidak ditemukan.";
    exit();
}

$film_id = intval($_GET['id']);

// Ambil informasi film
$stmt = $conn->prepare("SELECT judul FROM film WHERE id = ?");
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    echo "Film tidak ditemukan.";
    exit();
}

// Ambil semua jadwal tayang dari film ini
$stmt = $conn->prepare("SELECT * FROM jadwal_tayang WHERE film_id = ? ORDER BY tanggal, jam_mulai");
$stmt->bind_param("i", $film_id);
$stmt->execute();
$jadwal_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Tayang Film - Pengunjung</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-100 text-gray-800 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Jadwal Tayang untuk: <?= htmlspecialchars($film['judul']) ?></h2>

        <?php if ($jadwal_result->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-2 text-left font-semibold">Jam Mulai</th>
                            <th class="px-4 py-2 text-left font-semibold">Studio</th>
                            <th class="px-4 py-2 text-left font-semibold">Kursi Tersedia</th>
                            <th class="px-4 py-2 text-left font-semibold">Harga</th>
                            <th class="px-4 py-2 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while ($row = $jadwal_result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['jam_mulai']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['studio']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['kursi_tersedia']) ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($row['harga']) ?></td>
                                <td class="px-4 py-2">
                                    <a href="pesan_tiket.php?id=<?= $row['id'] ?>" 
                                       class="text-blue-600 hover:underline hover:text-blue-800 font-medium">
                                        Pesan Tiket
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Belum ada jadwal tayang untuk film ini.</p>
        <?php endif; ?>

        <div class="mt-6">
            <a href="detail_film_pengunjung.php?id=<?= $film_id ?>" 
               class="text-blue-500 hover:underline">&larr; Kembali ke Detail Film</a>
        </div>
    </div>
</body>
</html>
