<?php


// Eskualde bakoitza array bat da, bakoitza dagozkion herriekin

$zonak = [
    "Goierri" => ["Altzaga", "Araga", "Ataun", "Beasain"],
    "Donostialdea" => ["Andoain", "Donostia", "Hernani", "Usurbil"],
    "Urola" => ["Azkoitia", "Azpeitia", "Loiola", "Zumaia"],
    "Iruña" => ["Ansoain", "Barañain", "Berriozar", "Burlada"],
];

//POST-a bidali bada ikusi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eskualdea = $_POST['eskualdea'] ?? "";//Eskaera POST metodoa erabiltzen duen zihurtatzeko
    //Eskualderik bidali ez den zihurtatzeko
    if (empty($eskualdea)) {
        echo json_encode(["error" => "Ez da eskualderik aurkitu."]);
        exit;
    }

    if (array_key_exists($eskualdea, $zonak)) {
        echo json_encode($zonak[$eskualdea]);
    } 
    }
    exit;




?>
