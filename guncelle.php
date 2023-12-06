<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ogrenciID = $_POST["ogrenciID"];
    $devamsizlikArtis = $_POST["devamsizlikArtis"];

    // JSON dosyasını oku
    $jsonDosya = file_get_contents('ogrenciler.json');

    // JSON verisini PHP dizisine çevir
    $veri = json_decode($jsonDosya, true);

    // Öğrenci verilerine ulaş
    $ogrenci = $veri['ogrenciler'][$ogrenciID - 1];

    // Devamsızlık sayısını artır
    $ogrenci['devamsizlik'] += $devamsizlikArtis;

    // Güncellenmiş veriyi tekrar diziye ekle
    $veri['ogrenciler'][$ogrenciID - 1] = $ogrenci;

    // Yeniden JSON'a çevir
    $yenilenmisJson = json_encode($veri, JSON_PRETTY_PRINT);

    // JSON dosyasına yaz
    file_put_contents('ogrenciler.json', $yenilenmisJson);

    echo "Devamsızlık güncellendi. Yeni devamsızlık: " . $ogrenci['devamsizlik'];
} else {
    echo "Geçersiz istek.";
}

?>
