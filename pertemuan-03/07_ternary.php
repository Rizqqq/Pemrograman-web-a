<?php
    declare(strict_types=1);
    $a = 10;
    $b = 20;
    $c = 50;
    $min = $a < $c ? $a : $c;
    echo "Minimum: $min<br>";
    $maks = $a > $c ? $a : $c;
    echo "Maksimum: $maks<br>";
    $nilai = 80;
    $status = $nilai >= 75 ? 'LULUS' : 'TIDAK LULUS';
    echo "Status: $status<br>";
    // Elvis vs null coalescing
    $input = "12";
    echo "Elvis ?: " . ($input ?: 'default') . "\n"; // default (karena "0" falsy)
    echo "Null ?? : " . ($input ?? 'default') . "\n"; // 0 (karena tidak null)
    $umur = $_GET['umur'] ?? 2;
    echo "<br>Umur: $umur\n";