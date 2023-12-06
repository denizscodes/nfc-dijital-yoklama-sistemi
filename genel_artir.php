<?php

// JSON dosyasını oku
$jsonDosya = file_get_contents('ogrenciler.json');

// JSON verisini PHP dizisine çevir
$veri = json_decode($jsonDosya, true);

// Bütün öğrencilerin devamsızlık sayısını artır
foreach ($veri['ogrenciler'] as &$ogrenci) {
    $ogrenci['devamsizlik']++;
}

// Yeniden JSON'a çevir
$yenilenmisJson = json_encode($veri, JSON_PRETTY_PRINT);

// JSON dosyasına yaz
file_put_contents('ogrenciler.json', $yenilenmisJson);

echo "Tüm öğrencilerin devamsızlığı artırıldı.";
?>
