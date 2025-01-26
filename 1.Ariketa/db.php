<?php

function konexioaSortu() // Konexioa sortzeko funtzio definitu
{

    $servername = "localhost:3306"; // Datu basearen zerbitzaria definitu
    $username = "root"; // Datu basearen erabiltzailearen izena definitu
    $password = "1MG2024"; // Datu basearen erabiltzailearen pasahitza definitu
    $dbname = "8.ataza"; // Datu basearen izena definitu

    // Konexioa sortzeko kodea
    $conn = new mysqli($servername, $username, $password, $dbname);


    // Konexioa egiaztatzeko kodea
    if ($conn->connect_error) { // Errorea ematen badu, mezua inprimatu
        die("Connection failed: " . $conn->connect_error); 
    }

    return $conn; // Konexioa bueltatzeko kode zatia
}