<?php
session_start();

// ── Koneksi ──────────────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "db_toko2");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

// ── Akun user yang diizinkan (bisa ditambah) ─────────────
$USERS = [
    "user"  => "user123",
    "budi"  => "budi456",
    "siti"  => "siti789",
];

$error = "";

// ── Proses Login ─────────────────────────────────────────
if (isset($_POST['login'])) {
    $u = trim($_POST['username']);
    $p = $_POST['password'];
    if (isset($USERS[$u]) && $USERS[$u] === $p) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name']      = $u;
    } else {
        $error = "Username atau password salah.";
    }
}

// ── Logout ───────────────────────────────────────────────
if (isset($_GET['logout'])) {
    unset($_SESSION['user_logged_in'], $_SESSION['user_name']);
}

$loggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'];

// ── Ambil data buku (hanya jika sudah login) ─────────────
$buku = [];
if ($loggedIn) {
    $res  = $conn->query("SELECT * FROM buku ORDER BY id DESC");
    while ($row = $res->fetch_assoc()) $buku[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Toko Buku – Halaman User</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
<style>body{font-family:'Lato',sans-serif}h1,h2{font-family:'Merriweather',serif}</style>
</head>
<body class="bg-amber-50 min-h-screen">

<?php if (!$loggedIn): ?>
<!-- HALAMAN LOGIN USER  -->
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-700 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-amber-900">Toko Buku</h1>
            <p class="text-amber-700 text-sm mt-1">Login sebagai User untuk melihat katalog</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Masuk Akun User</h2>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Masukkan username">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Masukkan password">
                </div>
                <button type="submit" name="login"
                    class="w-full bg-amber-700 hover:bg-amber-800 text-white font-semibold py-2.5 rounded-lg transition-colors">
                    Login
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-400 text-center">
                Admin?
                <a href="admin.php" class="text-amber-700 font-semibold hover:underline">Login di halaman Admin →</a>
            </div>
        </div>

        <!-- Hint akun demo -->
        <div class="mt-4 bg-amber-100 rounded-xl px-4 py-3 text-xs text-amber-800">
            <span class="font-semibold">Akun demo:</span>
            username <code class="bg-white px-1 rounded">user</code> / password <code class="bg-white px-1 rounded">user123</code>
        </div>
    </div>
</div>

<?php else: ?>
<!--  HALAMAN KATALOG BUKU -- >

< -- Navbar -->
<nav class="bg-amber-800 text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <span class="font-bold text-lg" style="font-family:'Merriweather',serif">📚 Toko Buku</span>
        <div class="flex items-center gap-4 text-sm">
            <span class="text-amber-200">Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                <span class="ml-1 bg-amber-600 text-xs px-2 py-0.5 rounded-full">User</span>
            </span>
            <a href="?logout" class="bg-white text-amber-800 hover:bg-amber-100 px-3 py-1.5 rounded-lg font-semibold transition-colors">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Katalog Buku</h1>
        <p class="text-gray-500 text-sm mt-0.5">Lihat koleksi, stok, dan harga buku kami</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <?php if (count($buku) > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-amber-100 text-amber-900">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Judul</th>
                        <th class="px-5 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-5 py-3 text-left font-semibold">Tahun</th>
                        <th class="px-5 py-3 text-left font-semibold">Genre</th>
                        <th class="px-5 py-3 text-left font-semibold">Stok</th>
                        <th class="px-5 py-3 text-left font-semibold">Harga</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($buku as $b): ?>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="px-5 py-3 font-semibold text-gray-800"><?= htmlspecialchars($b['judul']) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($b['pengarang']) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= $b['tahun'] ?></td>
                        <td class="px-5 py-3">
                            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                <?= htmlspecialchars($b['genre']) ?>
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700 font-medium"><?= $b['stok'] ?></td>
                        <td class="px-5 py-3 text-gray-700 font-medium">Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                        <td class="px-5 py-3">
                            <?php if ($b['tersedia']): ?>
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Tersedia</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">Habis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-400">
            Total <?= count($buku) ?> buku tersedia di katalog.
        </div>
        <?php else: ?>
        <div class="text-center py-16 text-gray-400">
            <p class="font-medium">Belum ada data buku.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>
<?php $conn->close(); ?>
</body>
</html>
