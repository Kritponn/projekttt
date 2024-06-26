<?php
include_once "Database.php"; // Zahrnie súbor "db.php", ktorý obsahuje pripojenie k databáze

$db = new Database();
$conn = $db->getConnection();

session_start(); // Spustí alebo obnoví existujúcu session

if(!isset( $_SESSION['nick'])){ // Ak nie je užívateľ prihlásený, presmeruje ho na stránku login.php
    header("location: login.php");
}

$id = $_GET['edit']; // Získa ID kategórie, ktorá sa má upraviť, z parametra 'edit' v URL
// echo $id; // Tento riadok je zakomentovaný, ale môže byť použitý na debugovanie

if (isset($_POST['update_category'])){ // Ak bol odoslaný formulár na aktualizáciu kategórie
    $kategoria_nazov = $_POST['kategoria_nazov']; // Získa nový názov kategórie z formulára
    

    if(empty($kategoria_nazov) ){
        $message[]='napln polia'; // Ak sú polia prázdne, pridá chybovú správu do poľa $message
    } else {
        $select = mysqli_query($conn, "SELECT * FROM kategoria WHERE id='$id'"); // Vyberie starú kategóriu z databázy
        $row = mysqli_fetch_assoc($select);
        
        // echo $old_category_id;
        $update = "UPDATE kategoria SET kategoria_nazov='$kategoria_nazov' WHERE id='$id'"; // Aktualizuje kategóriu
        $upload = mysqli_query($conn, $update); // Vykoná aktualizáciu v databáze

        
        

        if ($upload){
            $message[] ='Kategória bola editovaná'; // Ak sa aktualizácia podarila, pridá správu o úspechu do poľa $message
        } else {
            $message[] ='Nepodarilo sa editovať kategóriu'; // Ak sa aktualizácia nepodarila, pridá chybovú správu do poľa $message
        }
    }
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Odkaz na externý CSS súbor -->
<body>
<main>
    <?php
    if(isset($message)){ // Ak existujú správy
        foreach ($message as $message){ // Pre každú správu v poli $message
            echo '<span class="message">'.$message.'</span>'; // Vypíše správu v štýle definovanom triedou .message
        }
    }
    ?>
    <div>
        <?php
        $select = mysqli_query($conn, "SELECT * FROM kategoria WHERE id='$id'"); // Vyberie konkrétnu kategóriu na základe ID
        $row = mysqli_fetch_assoc($select); // Získa dáta o kategórii
        ?>
        <form method="post">
            <h3> Edituj kategoriu</h3>
            <input type="text" name="kategoria_nazov" value="<?php echo $row['kategoria_nazov'];?>"> <!-- Zobrazí starý názov kategórie -->
        
            <br><br>
            <input type="submit" name="update_category" value="edituj kategóriu"> <!-- Tlačidlo na aktualizáciu kategórie -->
            <br>
            <a href="AddKategoria.php"> Naspäť</a> <br> <!-- Odkaz späť na stránku add_kategoria.php -->
            <a href="home.php"> Admin domov</a> <!-- Odkaz späť na hlavnú stránku administrácie -->
        </form>
    </div>
</main>
</body>
</html>
