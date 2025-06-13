<?php
include "koneksi.php";
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Cek apakah ada parameter id
if (!isset($_GET['id'])) {
    echo "ID film tidak ditemukan.";
    exit();
}

$film_id = intval($_GET['id']);

// Hapus jadwal tayang terlebih dahulu (jika foreign key belum ON DELETE CASCADE)
$stmt = $conn->prepare("DELETE FROM jadwal_tayang WHERE film_id = ?");
$stmt->bind_param("i", $film_id);
$stmt->execute();

// Hapus film dari tabel film
$stmt = $conn->prepare("DELETE FROM film WHERE id = ?");
$stmt->bind_param("i", $film_id);

if ($stmt->execute()) {
    header("Location: dashboard_admin.php?msg=hapus_sukses");
    exit();
} else {
    echo "Gagal menghapus film.";
}
?>