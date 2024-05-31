<?php
include_once "Database.php"; // Zahrnie súbor "db.php", ktorý obsahuje pripojenie k databáze

$db = new Database();
$conn = $db->getConnection();
session_start(); // Spustí alebo obnoví existujúcu session

if(!isset( $_SESSION['nick'])){ // Ak nie je užívateľ prihlásený, presmeruje ho na stránku login.php
    header("location: login.php");
}

$id = $_GET['edit']; // Získa ID používateľa, ktorého meno sa má zmeniť z parametra 'edit' v URL
// echo $id; // Tento riadok je zakomentovaný, ale môže byť použitý na debugovanie
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Odkaz na externý CSS súbor -->
<body>
<main>
    <div>
        <form method="post">
        
            <label>Nové používateľské meno</label>
            <input type="text" name="nove" placeholder="Nové meno"><br>
            <br><br>
            <input type="submit" name="sub" value="zmeň meno"><br>
            <a href="AddAdmin.php">Naspäť</a> <br> <!-- Odkaz späť na stránku add_admin.php -->
            <a href="home.php">Admin domov</a> <!-- Odkaz späť na hlavnú stránku administrácie -->
        </form>
    </div>
    <?php
    if (isset($_POST['sub'])){ // Ak bola stlačená tlačidlo na odoslanie formulára
        // echo "stlaceny"; // Tento riadok je zakomentovaný, ale môže byť použitý na debugovanie
        $nove = $_POST['nove']; // Získa hodnotu z poľa 'nove' formulára
        
            $update = "UPDATE `login` SET `meno` = '$nove' WHERE `login`.`id` = '$id'"; // SQL dotaz na aktualizáciu mena
            $upload = mysqli_query($conn, $update); // Vykoná SQL dotaz na aktualizáciu
        if ($update) { echo "meno zmenené"; // Vypíše správu o úspešnej zmene mena
        } else {
            echo "chyba mena"; // Vypíše správu o chybe pri zmene mena
        }
    }
    ?>
</main>
</body>
</html>
