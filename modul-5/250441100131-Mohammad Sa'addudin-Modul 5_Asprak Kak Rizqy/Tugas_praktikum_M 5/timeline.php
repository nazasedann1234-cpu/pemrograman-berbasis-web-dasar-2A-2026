<?php
$riwayat = [
 ['tahun'=>'2023','judul'=>'Masuk SMA','ket'=>'Belajar IPA','penting'=>false],
 ['tahun'=>'2025','judul'=>'Masuk Kuliah','ket'=>'Jurusan SI','penting'=>true],
 ['tahun'=>'2025','judul'=>'Belajar Programming','ket'=>'Dasar coding','penting'=>true],
 ['tahun'=>'2025','judul'=>'Project Web','ket'=>'Website sederhana','penting'=>true],
 ['tahun'=>'2025','judul'=>'Belajar PHP','ket'=>'Dasar PHP','penting'=>false],
];

function judul($j,$p){ return $p ? "<b class='text-red-500'>$j</b>" : $j; }
function tahun($t,$p){
 $c = $p ? "bg-red-500" : "bg-gray-700";
 return "<span class='$c text-white px-2 py-1 rounded text-xs'>$t</span>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Timeline</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-md mx-auto bg-white p-4 mt-6 rounded shadow">

<h2 class="text-center font-bold">Timeline Belajar</h2>

<div class="relative mt-5">

<div class="absolute left-16 top-0 bottom-0 w-0.5 bg-gray-300"></div>

<?php foreach($riwayat as $r): ?>
<div class="flex mb-4">

<div class="w-16 text-right pr-2">
<?= tahun($r['tahun'],$r['penting']) ?>
</div>


<div class="w-3 h-3 rounded-full mt-2 
<?= $r['penting'] ? 'bg-red-500' : 'bg-gray-700' ?>"></div>


<div class="ml-3 bg-gray-50 p-2 rounded w-full">
<div><?= judul($r['judul'],$r['penting']) ?></div>
<p class="text-sm"><?= $r['ket'] ?></p>
</div>

</div>
<?php endforeach; ?>

</div>

<div class="text-center mt-4 space-x-2">
<a href="index.php" class="bg-gray-800 text-white px-3 py-1 rounded">Profil</a>
<a href="blog.php" class="bg-gray-800 text-white px-3 py-1 rounded">Blog</a>
</div>

</div>
</body>
</html>