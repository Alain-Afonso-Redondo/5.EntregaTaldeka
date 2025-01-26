<?php

require_once("db.php"); // Datu baserekin konexioa egiteko php-rekin lotzeko kodea

if ($_GET["akzioa"] == "lortuPilotoak") { // GET-en bidez akzioa lortuPilotoarekin koinziditzen badu aurrera egitea

    $conn = konexioaSortu(); // Konexioa sortzeko funtzioarekin lotu

    $sql = "SELECT * FROM piloto"; // // Produktuak datu-basetik lortzeko eta hauek inprimatzko kontsulta
    $result = $conn->query($sql); // Kontsulta exekutatu eta emaitza gorde
    $piloto = [];

    if ($result->num_rows > 0) {
        $counter = 0; // Lerroak zenbatzeko aldagaia
        // Lerro bakoitzeko informazioa inprimatzeko kode zatia
        while ($row = $result->fetch_assoc()) {
            $piloto[$counter] = ["postua" => $row["Postua"], "dortsala" => $row["Dortsala"], "izena" => $row["Izena"]]; // Lerro bakoitzan dagoen informazioa definitu
            $counter++; // Lerroak gehitzeko kode zatia
        }
        
        $piloto["kopurua"] = $counter;  // Piloto taulan dagoen kopuru guztia, kontagailuarekin bateratzea
        echo json_encode($piloto); // Piloto taula inprimatzeko kodea
        die;

    } else {
        $piloto["kopurua"] = 0; // Piloto taulan dagoen kopurua huts dela konprobatzean, taula huts inprimatzea
        echo json_encode($piloto);
        die;
    }

}
