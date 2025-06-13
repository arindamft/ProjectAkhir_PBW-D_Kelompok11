<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['pengunjung_id'])) {
    header("Location: login_pengunjung.php");
    exit();
}


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searching = false;

if ($search !== '') {
    // Cari berdasarkan judul
    $stmt = mysqli_prepare($conn, "SELECT * FROM film WHERE judul LIKE ?");
    $param = "%{$search}%";
    mysqli_stmt_bind_param($stmt, "s", $param);
    mysqli_stmt_execute($stmt);
    $result_search = mysqli_stmt_get_result($stmt);
    $searching = true;
} else {
    // Ambil data film yang sedang tayang
    $query_sedang = "SELECT * FROM film WHERE status = 'Tayang'";
    $result_sedang = mysqli_query($conn, $query_sedang);

    // Ambil data film yang akan datang
    $query_segera = "SELECT * FROM film WHERE status = 'Segera Tayang'";
    $result_segera = mysqli_query($conn, $query_segera);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengunjung</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen flex flex-col">

    <header class="bg-white shadow-md p-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-xl font-bold text-blue-700">🎬 Absolute Cinema - Pengunjung Page</h1>

        <form method="GET" action="" class="flex flex-col sm:flex-row items-center gap-2">
            <input type="text" name="search" placeholder="Cari judul film..." value="<?php echo htmlspecialchars($search); ?>"
                class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                Cari
            </button>
        </form>

        <a href="logout_pengunjung.php" class="text-blue-600 hover:underline">Logout</a>
    </header>


    <main class="flex-1 container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Daftar Film</h2>
            <a href="tiket.php"
               class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-200">
                🎫 Lihat Tiket
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($searching): ?>
                <?php if (mysqli_num_rows($result_search) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_search)) : ?>
                        <?php include 'card_film_pengunjung.php'; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada film ditemukan.</p>
                <?php endif; ?> 
            <?php else: ?>
                <div class="col-span-full">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🎥
                    Sedang Tayang:</h3>
                </div>
                <?php while ($row = mysqli_fetch_assoc($result_sedang)) : ?>
                    <?php include 'card_film_pengunjung.php'; ?>
                <?php endwhile; ?>
                <div class="col-span-full mt-6">
                    <h3 class="text-xl font-semibold text-gray-800 
                    mb-2">🎞️ Segera Tayang:</h3>
                </div>
                
                <?php while ($row = mysqli_fetch_assoc($result_segera)) : ?>
                    <?php include 'card_film_pengunjung.php'; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white text-center text-gray-600 p-4 shadow-inner mt-auto">
        © Absolute Cinema - All Rights Reserved
    </footer>
</body>
</html>
