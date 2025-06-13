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
<html>
<head>
    <title>Jadwal Tayang Film - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-100 p-6 font-sans">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Jadwal Tayang untuk: <?= htmlspecialchars($film['judul']) ?></h2>

        <a href="tambah_jadwal.php?id=<?= $film_id ?>" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Tambah Jadwal Baru</a>

        <?php if ($jadwal_result->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Jam Mulai</th>
                            <th class="px-4 py-2 text-left">Jam Selesai</th>
                            <th class="px-4 py-2 text-left">Studio</th>
                            <th class="px-4 py-2 text-left">Kursi Tersedia</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $jadwal_result->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['jam_mulai']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['jam_selesai']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['studio']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['kursi_tersedia']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['harga']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-700 mt-4">Belum ada jadwal tayang untuk film ini.</p>
        <?php endif; ?>

        <div class="mt-6">
            <a href="detail_film_admin.php?id=<?= $film_id ?>" class="text-blue-600 hover:underline">← Kembali ke Detail Film</a>
        </div>
    </div>
</body>
</html>