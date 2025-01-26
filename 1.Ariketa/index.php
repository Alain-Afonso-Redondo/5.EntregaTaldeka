<?php

require_once("db.php"); // Datu basearekin konektatzeko kodea

$conn = konexioaSortu(); // Konexioa sortzeko funtzioa aldagaiarekin bateratzea


$bilaketa = ""; // Aldagai bati balio huts eman
if (isset($_GET["bilaketa"])) { // Aldagaiaren barruan testu bat jarrita dagoen konprobaketa
    $bilaketa = $_GET["bilaketa"]; // Testu balin badago aldagaian gordetzea
}

?>


<form method="GET" action="index.php"> <!-- Get en bidez formulario ejekutatzea-->
    <label for="bilaketa">Filtratu </label>
    <input type="text" name="bilaketa" value="<?= $bilaketa ?>" id="bilaketa" placeholder="Sartu izena" /> <!-- Testua jartzeko input-a -->
    <button>Bilatu</button> <!-- Input-en jarri duguna bilatzeko botoia -->
</form>

<button class="taulaReload">Taula birkargatu</button> <!-- Taula ajax-en bidez birkartzeko botoia -->
<br>


<?php

// Datu basetik elementuak erakusteko kontsulta bilatutakoaren arabera
$kontsulta = "SELECT id, postua, dortsala, izena FROM piloto WHERE izena LIKE \"%$bilaketa%\"";
$result = $conn->query($kontsulta); // Kontsulta konexioarekin parekatu
// Elementuen taula inprimatzeko kode zatia
if ($result->num_rows > 0) {
    echo "<table class='taula' border='1' cellspacing='0' cellpadding='10'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Postua</th>";
    echo "<th>Dortsala</th>";
    echo "<th>Izena</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Linea bakotzeko informazioa inprimatu
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['postua']) . "</td>"; // Postua imprimatzeko kode zatia
        echo "<td>" . htmlspecialchars($row['dortsala']) . "</td>"; // Dortsala imprimatzeko kode zatia
        echo "<td>" . htmlspecialchars($row['izena']) . "</td>"; // Izena imprimatzeko kode zatia
        echo "</tr>";
        // }
    }
    echo "</table>";
// Elementurik aurkitzen ez badu, mezua inprimatzea
} else {
    echo "Ez dago informaziorik"; 
}
$conn->close(); //Konexioa itxi

?>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script> <!-- Jquery kodera inplementatzeko script-a -->

<script>
    $(document).ready(function () {
        // Birkargatzeko botoian klikatzean "taulaBirkargatu" funtzioa ejekutazeko kodea
        $(".taulaReload").on("click", function () {
            taulaBirkargatu();
        });
        // Taula automatikoki birkargatzeko behar duen denbora
        setInterval(taulaBirkargatu, 60000);

    });
   // Taula birkargatzeko funtzioa
    function taulaBirkargatu() {
        // Ajax-en bidez datu baseetatik elementuak ekartzeko kodea, GET-en bidez
        $.ajax({
            "url": "lortuPilotoak.php",
            "method": "GET",
            "data": {
                "akzioa": "lortuPilotoak",
            }
        })
            .done(function (bueltanDatorrenInformazioa) {
                // Datu basetik datorren informazioa testura pasatzea
                var info = JSON.parse(bueltanDatorrenInformazioa);
                if (info.kopurua > 0) {
                    $(".taula").html("<tr><th>Postua</th><th>Dortsala</th><th>Izena</th></tr>");
                    // Datu basean dauden elementu guztiak ekartzeko eta inprimatzeko for-a
                    for (var i = 0; i < info.kopurua; i++) {
                        $(".taula").append("<tr><td>"+info[i].postua+"</td><td>"+info[i].dortsala+"</td><td>"+info[i].izena+"</td></tr>");
                    }
                    // Taulan elementurik ez badag, mezua inprimatzeko
                } else {
                    alert("Ez da elementurik kargatu");
                }
            })
            // Errorea ematen badu, mezua inprimatzeko funtzioa
            .fail(function () {
        alert("gaizki joan da");
    })
        .always(function () {
            // alert("aa");
        });
    }
</script>