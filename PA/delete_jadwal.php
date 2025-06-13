<?php
include "koneksi.php";
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Validasi parameter id
if (!isset($_GET['id'])) {
    echo "ID jadwal tidak ditemukan.";
    exit();
}

$jadwal_id = intval($_GET['id']);

// Ambil informasi film_id sebelum menghapus, untuk redirect balik
$stmt = $conn->prepare("SELECT film_id FROM jadwal_tayang WHERE id = ?");
$stmt->bind_param("i", $jadwal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data jadwal tidak ditemukan.";
    exit();
}

$data = $result->fetch_assoc();
$film_id = $data['film_id'];

// Hapus jadwal
$stmt = $conn->prepare("DELETE FROM jadwal_tayang WHERE id = ?");
$stmt->bind_param("i", $jadwal_id);
if ($stmt->execute()) {
    header("Location: daftar_jadwal_admin.php?id=$film_id");
    exit();
} else {
    echo "Gagal menghapus jadwal.";
}
?>