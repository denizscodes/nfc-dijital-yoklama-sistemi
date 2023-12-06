<?php

// JSON dosyasını oku
$jsonDosya = file_get_contents('ogrenciler.json');

// JSON verisini PHP dizisine çevir
$veri = json_decode($jsonDosya, true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ogrenciID = $_POST["ogrenciID"];

    // Öğrenci verilerine ulaş
    $ogrenci = $veri['ogrenciler'][$ogrenciID - 1];

    // Devamsızlık sayısını azalt
    $ogrenci['devamsizlik']--;

    // Güncellenmiş veriyi tekrar diziye ekle
    $veri['ogrenciler'][$ogrenciID - 1] = $ogrenci;

    // Yeniden JSON'a çevir
    $yenilenmisJson = json_encode($veri, JSON_PRETTY_PRINT);

    // JSON dosyasına yaz
    file_put_contents('ogrenciler.json', $yenilenmisJson);

    echo "Devamsızlık azaltıldı. Yeni devamsızlık: " . $ogrenci['devamsizlik'];
} else {
    echo "Geçersiz istek.";
}

?>
