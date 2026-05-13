<?php
$hasil=null;$pesan="";$fwArr=[];$tools=[];$exp="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
$n=$_POST['nama']??'';$id=$_POST['id']??'';$ttl=$_POST['ttl']??'';
$e=$_POST['email']??'';$wa=$_POST['wa']??'';$fw=$_POST['framework']??'';
$exp=$_POST['pengalaman']??'';$tools=$_POST['tools']??[];
$m=$_POST['minat']??'';$s=$_POST['skill']??'';

if(!$n||!$id||!$ttl||!$e||!$wa||!$fw||!$exp||!$m||!$s){
$pesan="Lengkapi semua data!";
}else{
$fwArr=explode(",",$fw);
$hasil=["Nama"=>$n,"ID"=>$id,"TTL"=>$ttl,"Email"=>$e,"WA"=>$wa,"Minat"=>$m,"Skill"=>$s];
}}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profil</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<div class="max-w-md mx-auto bg-white p-4 mt-6 rounded">

<h2 class="text-center font-bold">Profil Developer</h2>
<p class="text-red-500 text-center"><?= $pesan ?></p>

<form method="POST" class="space-y-2">

<input name="nama" placeholder="Nama" class="w-full border p-1">
<input name="id" placeholder="ID" class="w-full border p-1">
<input name="ttl" placeholder="TTL" class="w-full border p-1">
<input name="email" type="email" placeholder="Email" class="w-full border p-1">
<input name="wa" placeholder="WA" class="w-full border p-1">

<input name="framework" placeholder="Laravel,Vue" class="w-full border p-1">

<textarea name="pengalaman" class="w-full border p-1 resize-none" placeholder="Pengalaman"></textarea>

<div class="text-sm ">
Tools:
<label class = "p-2"><input type="checkbox" name="tools[]" value="VS Code">VSCode</label>
<label class = "p-2"><input type="checkbox" name="tools[]" value="GitHub">GitHub</label>
<label class = "p-2"><input type="checkbox" name="tools[]" value="Figma">Figma</label>
</div>

<div class="text-sm">
Minat:
<label class = "p-2"><input type="radio" name="minat" value="Frontend">Front-End</label>
<label class = "p-2"><input type="radio" name="minat" value="Backend">Back-end</label>
<label class = "p-2"><input type="radio" name="minat" value="Fullstack">Full-Stack</label>
</div>

<select name="skill" class="w-full border p-1">
<option value="">Skill</option>
<option>Dasar</option>
<option>Cukup</option>
<option>Profesional</option>
</select>

<button class="w-full bg-gray-800 text-white p-1 rounded-full">Simpan</button>
</form>

<div class="text-center mt-2 bg-gray-800 text-white p-1 rounded-full">
<a href="timeline.php">Timeline</a> |
<a href="blog.php">Blog</a>
</div>

<?php if($hasil): ?>
<h3 class="mt-3 font-semibold">Hasil</h3>
<table class="w-full border text-sm">
<?php foreach($hasil as $k=>$v): ?>
<tr><td class="border p-1"><?= $k ?></td><td class="border p-1"><?= $v ?></td></tr>
<?php endforeach; ?>
</table>

<ul class="list-disc ml-4 mt-2">
<?php foreach($fwArr as $f): ?><li><?= trim($f) ?></li><?php endforeach; ?>
</ul>

<?php if($tools): ?>
<ul class="list-disc ml-4">
<?php foreach($tools as $t): ?><li><?= $t ?></li><?php endforeach; ?>
</ul>
<?php endif; ?>

<p class="mt-2"><?= nl2br($exp) ?></p>
<?php endif; ?>

</div>
</body>
</html>