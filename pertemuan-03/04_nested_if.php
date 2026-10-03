<?php
declare(strict_types=1);
$sudahLogin = true;
$peran = 'operator';
if ($sudahLogin) {
if ($peran === 'admin') {
echo "Selamat datang, Admin. Akses penuh.\n";
} elseif ($peran === 'operator') {
echo "Selamat datang, Operator. Akses terbatas.\n";
} else {
echo "Peran tidak dikenal.\n";
}
} else {
echo "Silakan login terlebih dahulu.\n";
}
// Alternatif datar dengan &&
$terverifikasi = true;
$saldo = 120000;
if ($sudahLogin && $terverifikasi && $saldo >= 100000) {
echo "<br>Transaksi besar diizinkan.";
}