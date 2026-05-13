<?php
$artikel = [
'html' => [
'judul'=>'Belajar HTML',
'tanggal'=>'01 Januari 2026',


'konten'=>'Awal belajar HTML membingungkan tapi lama-lama
paham.',
'gambar'=>'https://blue.kumparan.com/image/upload/fl_progr
essive,fl_lossy,c_fill,f_auto,q_auto:best,w_640/v1634025439/01jcd3
8qpm7txnr4r26w4s3fp7.jpg',
'ref'=>['https://developer.mozilla.org','https://w3schools
.com']
],
'error' => [
'judul'=>'Error Pertama',
'tanggal'=>'02 Januari 2026',
'konten'=>'Error pertama bikin panik tapi jadi lebih
teliti.',
'gambar'=>'https://tse2.mm.bing.net/th/id/OIP.GjsaEl25qgWzprhZXi9u-gHaET?pid=Api&P=0&h=180',
'ref'=>['https://php.net','https://stackoverflow.com']
]
];
$kutipan = [
"Setiap developer hebat pernah pemula.",
"Code is like humor.",
"Debugging itu seperti jadi detektif.",
"Error hari ini, solusi besok."
];
$acak = $kutipan[array_rand($kutipan)];
$pilih = $_GET['artikel'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
<title>Blog Developer</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="max-w-xl mx-auto mt-8 bg-white p-5 rounded shadow">
<h2 class="text-center text-xl font-bold mb-3">Blog Developer</h2>
<div class="bg-gray-800 text-white text-center p-2 rounded mb-3">
💬 <?= $acak ?>

</div>
<!-- Daftar Artikel -->
<?php foreach($artikel as $key=>$a): ?>
<a href="?artikel=<?= $key ?>"
class="block p-2 mb-2 rounded <?= ($pilih==$key)?'bg-gray-800
text-white':'bg-gray-200' ?>">
<?= $a['judul'] ?> (<?= $a['tanggal'] ?>)
</a>
<?php endforeach; ?>
<!-- Detail -->
<?php if($pilih && isset($artikel[$pilih])):
$a = $artikel[$pilih]; ?>
<div class="mt-4">
<h3 class="font-bold"><?= $a['judul'] ?></h3>
<small class="text-gray-500"><?= $a['tanggal'] ?></small>

<img src="<?= $a['gambar'] ?>" class="w-full mt-2 rounded">
<p class="mt-2"><?= $a['konten'] ?></p>
<p class="mt-2 font-bold">Referensi:</p>
<?php foreach($a['ref'] as $r): ?>
<a href="<?= $r ?>" target="_blank" class="text-blue-600
block"><?= $r ?></a>
<?php endforeach; ?>
</div>
<?php elseif($pilih): ?>
<p class="text-red-500">Artikel tidak ditemukan</p>
<?php else: ?>
<p class="text-center mt-3">Pilih artikel</p>
<?php endif; ?>
<!-- Navigasi -->
<div class="text-center mt-5">
<a href="index.php" class="bg-gray-800 text-white px-3 py-1
rounded">Profil</a>
<a href="timeline.php" class="bg-gray-800 text-white px-3 py-1
rounded">Timeline</a>
</div>
</div>
</body>
</html>