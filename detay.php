<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Detay</title>
    <style>
        body {
          align-items: center;
          display: flex;
          flex-direction: column;
          justify-content: center;
          font-family: Arial, sans-serif;
          margin: 20px;
          background-color: #06283d;
        }

        h1, p {
          color: #dff6ff;
        }

        button {
          padding: 15px;
          margin: 20px;
          margin-left: 0px;
          cursor: pointer;
          background-color: #4caf50;
          color: #fff;
          border: none;
          border-radius: 4px;
        }

        button:hover {
          background-color: #45a049;
        }

        .container {
          display: flex;
          flex-direction: column;
          justify-content: center;
          background-color: #1363DF;
          border-radius: 10px;
          padding: 50px;
        }
    </style>
</head>
<body>
  <div class="container">
    <h1>Dijital Yoklama Sistemi</h1>

    <?php
    // Öğrenci ID'sini al
    $ogrenciID = $_GET["id"];

    // JSON dosyasını oku
    $jsonDosya = file_get_contents('ogrenciler.json');

    // JSON verisini PHP dizisine çevir
    $veri = json_decode($jsonDosya, true);

    // Belirli öğrenciyi bul
    $ogrenci = $veri['ogrenciler'][$ogrenciID - 1];

    // Kontrol için PHP'de de saklanacak bir değişken
    $gizleButton = false;

    // Eğer geçen süre varsa ve 30 saniyeden az ise
    if (isset($_SESSION['last_click']) && time() - $_SESSION['last_click'] < 30) {
        $gizleButton = true;
    }

    // Öğrenci detaylarını göster
    echo "<p>ID: " . $ogrenci['id'] . "<br>";
    echo "Ad: " . $ogrenci['ad'] . "<br>";
    echo "Soyad: " . $ogrenci['soyad'] . "<br>";
    echo "Devamsızlık: <span id='devamsizlik'>" . $ogrenci['devamsizlik'] . "</span></p>";

    // Devamsızlık azaltma seçeneği
    echo "<p> <button id='yoklamaButton' onclick='readTag(" . $ogrenci['id'] . ", -1)'";
    
    // Eğer gizleButton true ise, button'u gizle
    if ($gizleButton) {
        echo " style='display:none;'";
    }

    echo ">Yoklama Al</button></p>";
    ?>
  </div>

  <script>
    function devamsizlikGuncelle(ogrenciID, artis) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "guncelle.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var devamsizlikElement = document.getElementById('devamsizlik');
          devamsizlikElement.innerHTML = parseInt(devamsizlikElement.innerHTML) + artis;

          // Set a flag in local storage to hide the button for 30 seconds
          localStorage.setItem('hideButton', JSON.stringify({ hidden: true, timestamp: Date.now() }));

          // Hide the button
          document.getElementById('yoklamaButton').style.display = 'none';

          setTimeout(function () {
            // Remove the flag after 30 seconds
            localStorage.removeItem('hideButton');
            // Show the button
            document.getElementById('yoklamaButton').style.display = 'block';
          }, 6000000);
        }
      };
      xhr.send("ogrenciID=" + ogrenciID + "&devamsizlikArtis=" + artis);
    }

    async function readTag(ogrenciID, artis) {
      if ("NDEFReader" in window) {
        const ndef = new NDEFReader();
        try {
          await ndef.scan();
          ndef.onreading = (event) => {
            const decoder = new TextDecoder();
            devamsizlikGuncelle(ogrenciID, artis);

            for (const record of event.message.records) {
              console.log("Record :  " + record);

              console.log("Record type:  " + record.recordType);
              console.log("MIME type:    " + record.mediaType);
              console.log("=== data ===\n" + decoder.decode(record.data));
            }
          };
        } catch (error) {
          console.log(error);
        }
      } else {
        console.log("Web NFC is not supported.");
      }
    }

    // Check if the button should be hidden based on the local storage flag
    document.addEventListener("DOMContentLoaded", function () {
      var hideButton = localStorage.getItem('hideButton');
      var button = document.getElementById('yoklamaButton');

      if (hideButton) {
        var { hidden, timestamp } = JSON.parse(hideButton);
        if (hidden && Date.now() - timestamp < 6000000) {
          button.style.display = 'none';
          setTimeout(function () {
            button.style.display = 'block';
          }, 6000000 - (Date.now() - timestamp));
        }
      }
    });
  </script>
</body>
</html>
