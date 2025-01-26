<?php

function konexioaSortu()
{
    $servername = "localhost:3306";
    $username = "root";
    $password = "1WMG2023";
    $dbname = "karrera";

    // Konexioa sortu
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Konexioa egiaztatu
    if ($conn->connect_error) {
        die("Konexioa errorea: " . $conn->connect_error);
    }

    return $conn;
}
?>