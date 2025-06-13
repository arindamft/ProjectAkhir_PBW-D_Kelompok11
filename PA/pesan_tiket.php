<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['pengunjung_id'])) {
    header("Location: login_pengunjung.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID jadwal tidak ditemukan.";
    exit();
}

$jadwal_id = intval($_GET['id']);
$pengunjung_id = $_SESSION['pengunjung_id'];

// Ambil data jadwal
$stmt = $conn->prepare("SELECT jadwal_tayang.*, film.judul, film.durasi, film.id AS film_id FROM jadwal_tayang 
                        JOIN film ON film.id = jadwal_tayang.film_id
                        WHERE jadwal_tayang.id = ?");
$stmt->bind_param("i", $jadwal_id);
$stmt->execute();
$result = $stmt->get_result();
$jadwal = $result->fetch_assoc();

if (!$jadwal) {
    echo "Jadwal tidak ditemukan.";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = intval($_POST["jumlah"]);
    $metode_pembayaran = $_POST["metode"] ?? "";

    if ($jumlah < 1) {
        $message = "<p style='color:red;'>Jumlah tiket minimal 1.</p>";
    } elseif ($jumlah > $jadwal['kursi_tersedia']) {
        $message = "<p style='color:red;'>Jumlah tiket melebihi kursi yang tersedia (" . $jadwal['kursi_tersedia'] . ").</p>";
    } elseif (!in_array($metode_pembayaran, ['OVO', 'ShopeePay'])) {
        $message = "<p style='color:red;'>Metode pembayaran tidak valid.</p>";
    } else {
        $conn->begin_transaction();

        try {
            // Simpan tiket
            $stmt = $conn->prepare("INSERT INTO tiket (id_pengunjung, id_jadwal, jumlah, metode_pembayaran) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $pengunjung_id, $jadwal_id, $jumlah, $metode_pembayaran);
            $stmt->execute();
            $tiket_id = $stmt->insert_id;

            // Ambil kursi yang sudah terisi
            $stmt = $conn->prepare("SELECT kursi_nomor FROM detail_tiket 
                                    JOIN tiket ON detail_tiket.id_tiket = tiket.id
                                    WHERE tiket.id_jadwal = ?");
            $stmt->bind_param("i", $jadwal_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $kursi_terisi = [];
            while ($row = $result->fetch_assoc()) {
                $kursi_terisi[] = intval($row['kursi_nomor']);
            }

            // Hitung kursi kosong
            $kursi_dipesan = 0;
            for ($i = 1; $i <= $jadwal['kursi_tersedia']; $i++) {
                if (!in_array($i, $kursi_terisi)) {
                    // Simpan ke detail_tiket
                    $stmt = $conn->prepare("INSERT INTO detail_tiket (id_tiket, kursi_nomor) VALUES (?, ?)");
                    $stmt->bind_param("ii", $tiket_id, $i);
                    $stmt->execute();

                    $kursi_dipesan++;
                    if ($kursi_dipesan == $jumlah) break;
                }
            }

            // Update kursi tersedia
            $stmt = $conn->prepare("UPDATE jadwal_tayang SET kursi_tersedia = kursi_tersedia - ? WHERE id = ?");
            $stmt->bind_param("ii", $jumlah, $jadwal_id);
            $stmt->execute();

            $conn->commit();
            header("Location: tiket.php?id=$pengunjung_id");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $message = "<p style='color:red;'>Terjadi kesalahan saat memesan tiket.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen p-6">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6 space-y-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pesan Tiket untuk: <?= htmlspecialchars($jadwal['judul']) ?></h2>

        <?php if (!empty($message)) : ?>
            <p class="text-red-600"><?= $message ?></p>
        <?php endif; ?>

        <div class="text-gray-700 space-y-1">
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($jadwal['tanggal']) ?></p>
            <p><strong>Jam:</strong> <?= htmlspecialchars($jadwal['jam_mulai']) ?></p>
            <p><strong>Studio:</strong> <?= htmlspecialchars($jadwal['studio']) ?></p>
            <p><strong>Kursi Tersisa:</strong> <?= $jadwal['kursi_tersedia'] ?></p>
            <p><strong>Harga per Tiket:</strong> Rp <?= number_format($jadwal['harga']) ?></p>
        </div>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium text-gray-700">Jumlah Tiket:</label>
                <input type="number" name="jumlah" min="1" max="<?= $jadwal['kursi_tersedia'] ?>" required 
                       oninput="updateTotal()" class="w-full p-2 border rounded-md">
            </div>

            <p class="text-lg font-semibold text-gray-800">Total Harga: <span id="totalHarga">Rp 0</span></p>

            <input type="hidden" name="metode" id="metode">

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit"
                        onclick="document.getElementById('metode').value='OVO'; return confirm('Bayar dengan OVO?')"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded w-full">
                    Bayar dengan OVO
                </button>

                <button type="submit"
                        onclick="document.getElementById('metode').value='ShopeePay'; return confirm('Bayar dengan ShopeePay?')"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded w-full">
                    Bayar dengan ShopeePay
                </button>
            </div>
        </form>

        <div class="mt-4">
            <a href="daftar_jadwal_pengunjung.php?id=<?= $jadwal['film_id'] ?>"
               class="text-blue-500 hover:underline">← Kembali ke Jadwal Film</a>
        </div>
    </div>

    <script>
        function updateTotal() {
            const harga = <?= $jadwal['harga'] ?>;
            const jumlah = document.querySelector("input[name='jumlah']").value;
            const total = harga * jumlah;
            document.getElementById("totalHarga").innerText = isNaN(total) ? "Rp 0" : "Rp " + total.toLocaleString();
        }
    </script>
</body>
</html>