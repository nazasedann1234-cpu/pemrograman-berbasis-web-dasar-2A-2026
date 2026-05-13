<?php
session_start();

$conn = new mysqli("localhost", "root", "", "db_toko2");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');

$error = "";

// ── Proses Login ─────────────────────────────────────────
if (isset($_POST['login_admin'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Username atau password admin salah.";
    }
}

// ── Logout ─
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
}

$loggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// ── Operasi CRUD 
$notif = "";

if ($loggedIn) {

    // TAMBAH
    if (isset($_POST['tambah'])) {
        $judul    = trim($_POST['judul']);
        $pengarang= trim($_POST['pengarang']);
        $tahun    = (int)$_POST['tahun'];
        $genre    = trim($_POST['genre']);
        $stok     = (int)$_POST['stok'];
        $harga    = (float)$_POST['harga'];
        $tersedia = isset($_POST['tersedia']) ? 1 : 0;

        $stmt = $conn->prepare(
            "INSERT INTO buku (judul,pengarang,tahun,genre,stok,harga,tersedia) VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->bind_param("ssissdi", $judul, $pengarang, $tahun, $genre, $stok, $harga, $tersedia);
        $stmt->execute();
        $notif = "✅ Buku berhasil ditambahkan.";
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $id       = (int)$_POST['id'];
        $judul    = trim($_POST['judul']);
        $pengarang= trim($_POST['pengarang']);
        $tahun    = (int)$_POST['tahun'];
        $genre    = trim($_POST['genre']);
        $stok     = (int)$_POST['stok'];
        $harga    = (float)$_POST['harga'];
        $tersedia = isset($_POST['tersedia']) ? 1 : 0;

        $stmt = $conn->prepare(
            "UPDATE buku SET judul=?,pengarang=?,tahun=?,genre=?,stok=?,harga=?,tersedia=? WHERE id=?"
        );
        $stmt->bind_param("ssissdii", $judul, $pengarang, $tahun, $genre, $stok, $harga, $tersedia, $id);
        $stmt->execute();
        $notif = "✅ Buku berhasil diperbarui.";
    }

    // HAPUS
    if (isset($_GET['hapus'])) {
        $id   = (int)$_GET['hapus'];
        $stmt = $conn->prepare("DELETE FROM buku WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $notif = "🗑️ Buku berhasil dihapus.";
    }

    // Ambil semua buku
    $res  = $conn->query("SELECT * FROM buku ORDER BY id DESC");
    $buku = [];
    while ($row = $res->fetch_assoc()) $buku[] = $row;

    // Ambil data buku untuk form edit (jika ada ?edit=id)
    $editData = null;
    if (isset($_GET['edit'])) {
        $eid  = (int)$_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM buku WHERE id=?");
        $stmt->bind_param("i", $eid);
        $stmt->execute();
        $editData = $stmt->get_result()->fetch_assoc();
    }
}

$genres = ['Novel','Fiksi','Non-Fiksi','Sejarah','Fantasi','Pengembangan Diri','Sains','Pendidikan','Lainnya'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Toko Buku – Panel Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
<style>body{font-family:'Lato',sans-serif}h1,h2{font-family:'Merriweather',serif}</style>
</head>
<body class="bg-gray-100 min-h-screen">

<?php if (!$loggedIn): ?>
<!--  HALAMAN LOGIN ADMIN  -->
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-800 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Panel Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola data buku</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Login Admin</h2>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username Admin</label>
                    <input type="text" name="username" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-600"
                        placeholder="Username admin">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Admin</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-600"
                        placeholder="Password admin">
                </div>
                <button type="submit" name="login_admin"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2.5 rounded-lg transition-colors">
                    Masuk sebagai Admin
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-400 text-center">
                Bukan admin?
                <a href="user.php" class="text-amber-700 font-semibold hover:underline">Login sebagai User →</a>
            </div>
        </div>

        <div class="mt-4 bg-gray-200 rounded-xl px-4 py-3 text-xs text-gray-600">
            <span class="font-semibold">Akun admin:</span>
            username <code class="bg-white px-1 rounded">admin</code> / password <code class="bg-white px-1 rounded">admin123</code>
        </div>
    </div>
</div>

<?php else: ?>
<!-- PANEL ADMIN  -->

<!-- Navbar -->
<nav class="bg-gray-800 text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <span class="font-bold text-lg" style="font-family:'Merriweather',serif">🛠️ Panel Admin – Toko Buku</span>
        <div class="flex items-center gap-4 text-sm">
            <a href="user.php" class="text-gray-300 hover:text-white transition-colors">Lihat Halaman User</a>
            <a href="?logout" class="bg-white text-gray-800 hover:bg-gray-100 px-3 py-1.5 rounded-lg font-semibold transition-colors">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

    <!-- Notifikasi -->
    <?php if ($notif): ?>
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
        <?= htmlspecialchars($notif) ?>
    </div>
    <?php endif; ?>

    <!-- ── FORM TAMBAH / EDIT ── -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">
            <?= $editData ? '✏️ Edit Buku' : '➕ Tambah Buku Baru' ?>
        </h2>

        <form method="POST">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Judul -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required
                        value="<?= htmlspecialchars($editData['judul'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500"
                        placeholder="Judul buku">
                </div>
                <!-- Pengarang -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pengarang <span class="text-red-500">*</span></label>
                    <input type="text" name="pengarang" required
                        value="<?= htmlspecialchars($editData['pengarang'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500"
                        placeholder="Nama pengarang">
                </div>
                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun" min="1000" max="<?= date('Y') ?>"
                        value="<?= $editData['tahun'] ?? date('Y') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <!-- Genre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Genre</label>
                    <select name="genre" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white">
                        <?php foreach ($genres as $g): ?>
                            <option value="<?= $g ?>" <?= ($editData['genre'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Stok -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stok" min="0"
                        value="<?= $editData['stok'] ?? 0 ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <!-- Harga -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" step="500"
                        value="<?= $editData['harga'] ?? 0 ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <!-- Tersedia -->
                <div class="sm:col-span-2 flex items-center gap-3">
                    <input type="checkbox" name="tersedia" id="tersedia" value="1"
                        <?= ($editData['tersedia'] ?? 1) ? 'checked' : '' ?>
                        class="w-4 h-4 accent-gray-700">
                    <label for="tersedia" class="text-sm font-semibold text-gray-700">Tandai sebagai tersedia</label>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <?php if ($editData): ?>
                    <button type="submit" name="update"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors">
                        Simpan Perubahan
                    </button>
                    <a href="admin.php"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors">
                        Batal
                    </a>
                <?php else: ?>
                    <button type="submit" name="tambah"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors">
                        Tambah Buku
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ── TABEL DAFTAR BUKU ── -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">📋 Daftar Buku</h2>
            <span class="text-xs text-gray-400"><?= count($buku) ?> buku</span>
        </div>

        <?php if (count($buku) > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Judul</th>
                        <th class="px-5 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-5 py-3 text-left font-semibold">Tahun</th>
                        <th class="px-5 py-3 text-left font-semibold">Genre</th>
                        <th class="px-5 py-3 text-left font-semibold">Stok</th>
                        <th class="px-5 py-3 text-left font-semibold">Harga</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($buku as $b): ?>
                    <tr class="hover:bg-gray-50 transition-colors <?= $editData && $editData['id'] == $b['id'] ? 'bg-blue-50' : '' ?>">
                        <td class="px-5 py-3 font-semibold text-gray-800"><?= htmlspecialchars($b['judul']) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($b['pengarang']) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= $b['tahun'] ?></td>
                        <td class="px-5 py-3">
                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                <?= htmlspecialchars($b['genre']) ?>
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700"><?= $b['stok'] ?></td>
                        <td class="px-5 py-3 text-gray-700">Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                        <td class="px-5 py-3">
                            <?php if ($b['tersedia']): ?>
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Tersedia</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="?edit=<?= $b['id'] ?>"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                                    Edit
                                </a>
                                <a href="?hapus=<?= $b['id'] ?>"
                                    onclick="return confirm('Yakin hapus buku ini?')"
                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12 text-gray-400">
            <p>Belum ada data buku. Tambahkan melalui form di atas.</p>
        </div>
        <?php endif; ?>
    </div>

</div><!-- end max-w -->
<?php endif; ?>

<?php $conn->close(); ?>
</body>
</html>
