<?php
include_once "Database.php"; // Zahrnie súbor "db.php", ktorý obsahuje pripojenie k databáze

$db = new Database();
$conn = $db->getConnection();
session_start(); // Spustí alebo obnoví existujúcu session
if (!isset($_SESSION['nick'])){ // Skontroluje, či je premenná session 'nick' nastavená
    header("location: login.php"); // Ak nie je nastavená, presmeruje užívateľa na stránku login.php
}
$id = $_GET['edit']; // Získa hodnotu parametra 'edit' z URL
// echo $id; // Tento riadok je zakomentovaný, ale môže byť použitý na debugovanie
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Link na externý CSS súbor -->
<body>
<main>
    <div>
        <!-- Formulár na zmenu hesla -->
        <form method="post">
            <label>Používateľské meno</label>
            <input type="text" name="meno" placeholder="Používateľské meno"><br>
            <label>Zadaj staré heslo</label>
            <input type="password" name="stare" placeholder="Staré heslo"><br>
            <label>Zadaj nové heslo</label>
            <input type="password" name="nove" placeholder="Nové heslo"><br>
            <br><br>
            <input type="submit" name="sub" value="zmeň heslo"><br>
            <a href="AddAdmin.php">Naspäť</a> <br> <!-- Odkaz späť na stránku add_admin.php -->
            <a href="home.php">Admin domov</a> <!-- Odkaz späť na hlavnú stránku administrácie -->
        </form>
    </div>

    <?php
    if (isset($_POST['sub'])){ // Skontroluje, či bolo stlačené tlačidlo na odoslanie formulára
        // echo "stlaceny"
        $nove = $_POST['nove']; // Získa hodnotu z poľa 'nove' formulára
        $sql = "SELECT * FROM `login` WHERE meno='$_POST[meno]' AND heslo='$_POST[stare]'"; // SQL dotaz na overenie používateľa
        $upload = mysqli_query($conn, $sql); // Vykoná SQL dotaz
        if (mysqli_num_rows($upload) == 1){ // Skontroluje, či bol nájdený jeden záznam
            $update = "UPDATE `login` SET `heslo` = '$nove' WHERE `login`.`id` = '$id'"; // SQL dotaz na aktualizáciu hesla
            $upload = mysqli_query($conn, $update); // Vykoná SQL dotaz na aktualizáciu
            echo "heslo zmenené"; // Vypíše správu o úspešnej zmene hesla
        } else {
            echo "chybné údaje"; // Vypíše správu o chybnom používateľskom mene alebo hesle
        }
    }
    ?>
</main>
</body>
</html>
