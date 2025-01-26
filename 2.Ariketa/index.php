<?php

require_once("dbKonexioa.php"); // Datu basearekin konektatzeko kodea
$conn = konexioaSortu(); // Konexioa sortzeko funtzioa aldagaiarekin bateratzea

// Bilaketa aldagaia
$bilaketa = ""; 
if (isset($_GET["bilaketa"])) {
    $bilaketa = $_GET["bilaketa"];
}

// Logika: Pilotoen posizioak eguneratzea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pilotoa'], $_POST['postua'])) {
    $pilotoa = intval($_POST['pilotoa']);
    $postuBerria = intval($_POST['postua']);

    // Egungo posizioa bilatu
    $queryEgungoPostua = "SELECT postua FROM piloto WHERE dortsala = $pilotoa";
    $resultEgungoPostua = $conn->query($queryEgungoPostua);
    if ($resultEgungoPostua->num_rows > 0) {
        $egungoPostua = $resultEgungoPostua->fetch_assoc()['postua'];

        // Aldatutako posizioa kontuan hartu eta besteak eguneratu
        if ($postuBerria < $egungoPostua) {
            // Piloto igotzen bada, besteak desplazatu behera
            $conn->query("UPDATE piloto SET postua = postua + 1 WHERE postua >= $postuBerria AND postua < $egungoPostua");
        } elseif ($postuBerria > $egungoPostua) {
            // Piloto jaisten bada, besteak desplazatu gora
            $conn->query("UPDATE piloto SET postua = postua - 1 WHERE postua <= $postuBerria AND postua > $egungoPostua");
        }

        // Aukeratutako pilotuaren posizioa eguneratu
        $conn->query("UPDATE piloto SET postua = $postuBerria WHERE dortsala = $pilotoa");
        echo "<script>alert('Posizioak eguneratu dira!');</script>";
    } else {
        echo "<script>alert('Pilotoa ez da aurkitu.');</script>";
    }
}
?>

<!-- Formularioak -->
<form method="GET" action="index.php"> 
    <label for="bilaketa">Filtratu: </label>
    <input type="text" name="bilaketa" value="<?= $bilaketa ?>" id="bilaketa" placeholder="Sartu izena" />
    <button>Bilatu</button>
</form>

<form method="POST" action="index.php"> 
    <label for="pilotoa">Dortsala: </label>
    <input type="number" name="pilotoa" id="pilotoa" required />
    <label for="postua">Postu berria: </label>
    <input type="number" name="postua" id="postua" required />
    <button>Posizioa eguneratu</button>
</form>

<button class="taulaReload">Taula birkargatu</button>
<br>

<?php

// Posizioak taula erakusteko kontsulta
$kontsulta = "SELECT id, postua, dortsala, izena FROM piloto WHERE izena LIKE \"%$bilaketa%\" ORDER BY postua ASC";
$result = $conn->query($kontsulta);

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

    while ($row = $result->fetch_assoc()) {
        echo "<tr data-postua='{$row['postua']}'>";
        echo "<td>" . htmlspecialchars($row['postua']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dortsala']) . "</td>";
        echo "<td>" . htmlspecialchars($row['izena']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "Ez dago informaziorik";
}
$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function () {
        // Taula automatikoki birkargatzeko
        $(".taulaReload").on("click", function () {
            taulaBirkargatu(true);
        });
        setInterval(function () {
            taulaBirkargatu(false);
        }, 60000);
    });

    // Taula birkargatzeko funtzioa
    function taulaBirkargatu(showAlert) {
        $.ajax({
            "url": "lortuPilotoak.php",
            "method": "GET",
            "data": {
                "akzioa": "lortuPilotoak"
            }
        })
        .done(function (bueltanDatorrenInformazioa) {
            var info = JSON.parse(bueltanDatorrenInformazioa);

            if (info.kopurua > 0) {
                var taula = $(".taula tbody");
                taula.html(""); // Garbitu taula
                for (var i = 0; i < info.kopurua; i++) {
                    var kolorea = "";
                    var tr = $(".taula tr[data-postua='" + info[i].postua + "']");
                    
                    if (tr.length > 0) {
                        var lehengoPostua = tr.data("postua");
                        if (lehengoPostua > info[i].postua) kolorea = "style='background-color: lightgreen'";
                        if (lehengoPostua < info[i].postua) kolorea = "style='background-color: lightcoral'";
                    }

                    taula.append("<tr " + kolorea + " data-postua='" + info[i].postua + "'>" +
                                 "<td>" + info[i].postua + "</td>" +
                                 "<td>" + info[i].dortsala + "</td>" +
                                 "<td>" + info[i].izena + "</td>" +
                                 "</tr>");
                }
                if (showAlert) alert("Taula posizioak eguneratu dira!");
            } else {
                alert("Ez dago daturik taulan");
            }
        })
        .fail(function () {
            alert("Errorea gertatu da datuak kargatzerakoan");
        });
    }
</script>

