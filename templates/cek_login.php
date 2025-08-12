<?php 
session_start();
include 'koneksi.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Hash password menggunakan API Flask
$data = json_encode(["password" => $password]);
$ch = curl_init("http://localhost:5000/hash");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

$hashed_password = json_decode($response, true)["hashed_password"];

// Query ke database untuk cek login
$login = mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$username' AND password='$hashed_password'");
$cek = mysqli_num_rows($login);

// Cek apakah ditemukan user
if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);

    // Set session dan redirect sesuai level
    $_SESSION['username'] = $username;
    $_SESSION['level'] = $data['level'];

    if ($data['level'] == "admin" || $data['level'] == "petugas") {
        header("location:dashboard.php");
    } else if ($data['level'] == "siswa") {
        header("location:history.php");
    } else {
        header("location:index.php?pesan=gagal");
    }
} else {
    // Jika login gagal
    header("location:index.php?pesan=gagal");
}
?>
