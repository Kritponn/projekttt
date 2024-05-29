<?php
// Definovanie parametrov pre pripojenie k databáze
$servername = "localhost"; // Názov servera databázy
$username = "root"; // Používateľské meno pre prístup k databáze
$password = ""; // Heslo pre prístup k databáze (prázdne heslo)
$dbname = "simple_house"; // Názov databázy

// Vytvorenie nového pripojenia k databáze pomocou MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Skontroluje, či sa pripojenie nepodarilo
if ($conn->connect_error) {
    // Ak pripojenie zlyhalo, vypíše chybovú správu a ukončí skript
    die("Napojenie zlyhalo: " . $conn->connect_error);
}

// Ak pripojenie bolo úspešné, kód pokračuje


?>
