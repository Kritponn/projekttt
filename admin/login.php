<?php
include_once "db.php"; // Vloží súbor s pripojením do databázy

// Skontroluje, či je premenná $conn prázdna (neobsahuje pripojenie k databáze)
if (empty($conn)){
    $conn = new stdClass(); // Ak áno, vytvorí novú prázdnu objektovú premennú $conn
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Link na externý CSS súbor -->

<body>
<!-- Formulár na prihlásenie -->
<form method="POST">
    <h2>Prihlásenie</h2>
    <label>Používateľské meno</label>
    <input type="text" name="meno" placeholder="Meno"><br> <!-- Vstupné pole pre používateľské meno -->
    <label>Používateľské heslo</label>
    <input type="password" name="heslo" placeholder="Heslo"><br> <!-- Vstupné pole pre heslo -->
    <input type="submit" name="sub" value="Prihlásiť sa"> <!-- Tlačidlo na odoslanie formulára -->
</form>
<a href="../index.php">Reštaurička</a> <!-- Odkaz na domovskú stránku -->

<?php
// Skontroluje, či bol odoslaný formulár
if (isset($_POST['sub'])){
    //vypíše správu, že tlačidlo bolo stlačené
    // SQL dotaz na overenie používateľského mena a hesla
    $sql = "SELECT * FROM `login` WHERE meno='$_POST[meno]' AND heslo='$_POST[heslo]'";
    $upload = mysqli_query($conn, $sql); // Vykoná SQL dotaz

    // Skontroluje, či bol nájdený presne jeden záznam
    if (mysqli_num_rows($upload) == 1){
        session_start(); // Spustí session
        $_SESSION['nick'] = $_POST['meno']; // Nastaví premennú session 'nick' na hodnotu 'meno'
        header("location: home.php"); // Presmeruje užívateľa na stránku home.php
    }
    else{
        echo "chyba prihlasenia"; // Ak prihlásenie zlyhalo, vypíše správu o chybe
    }
}
?>
</body>
</html>
