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

// Ambil data film
$stmt = $conn->prepare("SELECT judul, tanggal_rilis, durasi, status FROM film WHERE id = ?");
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    echo "Film tidak ditemukan.";
    exit();
}

// Cek status film
if ($film['status'] !== 'Tayang') {
    echo "<p style='color:red;'>Jadwal tidak dapat ditambahkan karena film berstatus <strong>" . htmlspecialchars($film['status']) . "</strong>.</p>";
    exit();
}

$message = "";

if (isset($_POST["submit"])) {
    $tanggal = $_POST["tanggal"];
    $jam_mulai = $_POST["jam"];
    $studio = trim($_POST["studio"]);
    $kursi = intval($_POST["kursi"]);
    $harga = intval($_POST["harga"]);

    // Validasi tanggal tayang >= tanggal rilis
    if ($tanggal < $film['tanggal_rilis']) {
        $message = "<p style='color:red;'>Tanggal tayang harus setelah atau sama dengan tanggal rilis film (" . htmlspecialchars($film['tanggal_rilis']) . ").</p>";
    } else {
        // Hitung waktu mulai dan selesai film
        $start_time = new DateTime("$tanggal $jam_mulai");
        $end_time = clone $start_time;
        $end_time->modify("+{$film['durasi']} minutes");
        $jam_selesai = $end_time->format('H:i:s');

        // Cek bentrok dengan jadwal lain di studio yang sama dan tanggal yang sama
        $query = "SELECT tanggal, jam_mulai, durasi, film.judul 
                  FROM jadwal_tayang 
                  JOIN film ON film.id = jadwal_tayang.film_id
                  WHERE tanggal = ? AND studio = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $tanggal, $studio);
        $stmt->execute();
        $result = $stmt->get_result();
        $bentrok = false;

        while ($row = $result->fetch_assoc()) {
            $existing_start = new DateTime($row['tanggal'] . ' ' . $row['jam_mulai']);
            $existing_end = clone $existing_start;
            $existing_end->modify("+{$row['durasi']} minutes");

            if ($start_time < $existing_end && $end_time > $existing_start) {
                $bentrok = true;
                $message = "<p style='color:red;'>Jadwal berbenturan dengan film <strong>" . htmlspecialchars($row['judul']) . "</strong> yang tayang dari " . $existing_start->format('H:i') . " sampai " . $existing_end->format('H:i') . ".</p>";
                break;
            }
        }

        if (!$bentrok) {
            $stmt = $conn->prepare("INSERT INTO jadwal_tayang (film_id, tanggal, jam_mulai, jam_selesai, studio, kursi_tersedia, harga) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssii", $film_id, $tanggal, $jam_mulai, $jam_selesai, $studio, $kursi, $harga);
            if ($stmt->execute()) {
                header("Location: daftar_jadwal_admin.php?id=$film_id");
                exit();
            } else {
                $message = "<p style='color:red;'>Gagal menambahkan jadwal.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen p-6">

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            Tambah Jadwal untuk: <?= htmlspecialchars($film['judul']) ?>
        </h2>

        <?php if (!empty($message)) : ?>
            <p class="mb-4 text-red-600"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Tayang</label>
                <input type="date" name="tanggal" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Tayang</label>
                <input type="time" name="jam" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Studio</label>
                <select name="studio" required class="w-full p-2 border rounded-md">
                    <option value="Studio 1">Studio 1</option>
                    <option value="Studio 2">Studio 2</option>
                    <option value="Studio 3">Studio 3</option>
                    <option value="Studio 4">Studio 4</option>
                    <option value="Studio 5">Studio 5</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Kursi</label>
                <input type="number" name="kursi" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Per Tiket</label>
                <input type="text" name="harga" required class="w-full p-2 border rounded-md">
            </div>

            <div>
                <button type="submit" name="submit"
                        onclick="return confirm('Yakin ingin menambahkan jadwal film ini?')"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                    Tambah Jadwal
                </button>
            </div>
        </form>

        <div class="mt-6">
            <a href="daftar_jadwal_admin.php?id=<?= $film_id ?>"
               class="text-blue-500 hover:underline">← Kembali ke Daftar Jadwal</a>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
        const kursi = parseInt(document.querySelector("input[name='kursi']").value);
        const harga = parseInt(document.querySelector("input[name='harga']").value);

        if (isNaN(kursi) || kursi < 20) {
            alert("Minimal kursi 20");
            e.preventDefault();
            return;
        }

        if (isNaN(harga) || harga < 10000) {
            alert("Minimal harga tiket Rp. 10.000");
            e.preventDefault();
            return;
        }
    });
    </script>
</body>
</html>