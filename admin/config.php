<?php
function db(){
    $servername = "localhost"; // Názov servera databázy

    $username = "root"; // Používateľské meno pre prístup k databáze

    $password = "root"; // Heslo pre prístup k databáze

    $dbname = "simple_house"; // Názov databázy

    // Vytvorí nové pripojenie k databáze pomocou MySQLi ..$conn je moj názov premnnej pre pripájanie do db
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Skontroluje, či sa pripojenie nepodarilo
    if ($conn->connect_error) {
        // Ak pripojenie zlyhalo, vypíše chybovú správu a ukončí skript
        die("Napojenie zlyhalo: " . $conn->connect_error);
    }
    // Ak pripojenie bolo úspešné, vráti objekt pripojenia
    return $conn;
}
?>
