<?php
// mengaktifkan session pada php
session_start();

// menghubungkan php dengan koneksi database
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {$nama = $_POST["nama_petugas"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $level = $_POST["level"];

    // Validasi password dengan Python API
    $data = json_encode(["password" => $password]);

    $ch = curl_init("http://localhost:5000/check_strength");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (!$result || !isset($result["valid"])) {
        echo "<script>alert('Terjadi kesalahan saat validasi password. Pastikan API aktif.'); window.history.back();</script>";
        exit;
    }

    if (!$result["valid"]) {
        echo "<script>alert('Password lemah: " . $result["message"] . "'); window.history.back();</script>";
        exit;
    }

    // Hash password dengan Python API
    $ch = curl_init("http://localhost:5000/hash");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $hashed_password = json_decode($response, true)["hashed_password"];

    $stmt = $koneksi->prepare("INSERT INTO petugas (username, password, nama_petugas, level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $nama, $level);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '
        <div id="popupMessage" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #d4edda;
            color: #155724;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 9999;
            font-size: 16px;
            max-width: 80%;
        ">
            <strong>Registrasi berhasil!</strong>
            <span style="
                position: absolute;
                top: 8px;
                right: 12px;
                cursor: pointer;
                font-weight: bold;
            " onclick="document.getElementById(\'popupMessage\').remove()">×</span>
        </div>';
    } else {
        echo '
        <div id="popupMessage" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 9999;
            font-size: 16px;
            max-width: 80%;
        ">
            <strong>Registrasi gagal!</strong>
            <span style="
                position: absolute;
                top: 8px;
                right: 12px;
                cursor: pointer;
                font-weight: bold;
            " onclick="document.getElementById(\'popupMessage\').remove()">×</span>
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="assets/fontawesome-free/css/all.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/components.css">
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="img/avatar/avatar-1.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-info">
              <div class="card-header"><h4>Daftar Akun Baru</h4></div>
              <div class="card-body">
                <?php if (isset($_SESSION['pesan'])): ?>
                  <div class="alert alert-info"><?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="form-group">
                        <label>Nama</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                            <input type="text" name="nama_petugas" class="form-control" required>
                        </div>
                    </div>

                  <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                      </div>
                      <input type="text" name="username" class="form-control" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                      </div>
                      <input type="password" name="password" class="form-control" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control" required>
                      <option value="">-- Pilih Level --</option>
                      <option value="admin">Admin</option>
                      <option value="petugas">Petugas</option>
                      <option value="siswa">Siswa</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <button type="submit" name="daftar" class="btn btn-primary btn-lg btn-block">
                      Daftar
                    </button>
                  </div>
                </form>

                <div class="text-center">
                  Sudah punya akun? <a href="index.php">Login di sini</a>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="bootstrap/jquery-3.3.1.min.js"></script>
  <script src="bootstrap/popper.min.js"></script>
  <script src="bootstrap/bootstrap.min.js"></script>
  <script src="bootstrap/jquery.nicescroll.min.js"></script>
  <script src="bootstrap/moment.min.js"></script>
  <script src="bootstrap/stisla.js"></script>

  <!-- Template JS File -->
  <script src="bootstrap/scripts.js"></script>
  <script src="bootstrap/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="bootstrap/page/index.js"></script>

  <!-- Page Specific JS File -->
</body>
</html>
