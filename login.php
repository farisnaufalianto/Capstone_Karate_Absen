<?php
require 'config.php';
if (!empty($_SESSION['user'])) { header('Location: beranda.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = strtolower(trim($_POST['email'] ?? ''));
    $p = $_POST['password'] ?? '';
    $s = $pdo->prepare('SELECT * FROM pengguna WHERE email = ?');
    $s->execute([$u]);
    $user = $s->fetch();
    if ($user && password_verify($p, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $user['id_pengguna'], 'nama' => $user['nama'], 'role' => $user['role']];
        header('Location: beranda.php'); exit;
    }
    $error = 'Email atau password salah';
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Karate</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css"></head>
<body><div class="app"><main class="isi login">
  <div class="logo-box"><?= logo() ?></div>
  <p class="nama-sistem">Sistem Informasi Manajemen<br>Kegiatan Latihan Karate</p>
  <p class="kecil center">Kelola peserta, jadwal, dan absensi latihan dalam satu tempat.</p>

  <h1 class="judul-login">Masuk ke Akun Anda</h1>
  <p class="kecil">Silakan masuk untuk mengelola latihan.</p>

  <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>

  <form method="post" autocomplete="off">
    <label>Email
      <span class="field <?= $error ? 'salah' : '' ?>"><?= ikon('user') ?>
        <input type="email" name="email" placeholder="Masukkan email" value="<?= e($_POST['email'] ?? '') ?>" required></span></label>
    <label>Password
      <span class="field <?= $error ? 'salah' : '' ?>"><?= ikon('lock') ?>
        <input type="password" id="pw" name="password" placeholder="Masukkan password" required>
        <button type="button" class="mata" id="mata" aria-label="Tampilkan password"><?= ikon('eye') ?></button></span></label>
    <button class="btn">Login</button>
  </form>
  <p class="kecil center">Belum punya akun? <a href="#" class="link">Hubungi admin</a></p>
  <p class="footer-login">Disiplin berlatih. Tertib mengelola.</p>
</main></div>
<script>
  document.getElementById('mata').onclick = () => {
    const p = document.getElementById('pw');
    p.type = p.type === 'password' ? 'text' : 'password';
  };
</script>
</body></html>