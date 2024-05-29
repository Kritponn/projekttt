<?php
include_once "db.php"; 

// Skontroluje, či je premenná $conn prázdna (neobsahuje pripojenie k databáze)
if (empty($conn)){
    $conn = new stdClass(); // Ak áno, vytvorí novú prázdnu objektovú premennú $conn
}

session_start(); // Spustí session na začiatku skriptu

// Skontroluje, či je premenná 'nick' nastavená v session
if (!isset($_SESSION['nick'])){
    header("location: login.php"); // Ak nie, presmeruje užívateľa na stránku login.php
}

// Skontroluje, či bol odoslaný formulár na pridanie kategórie
if (isset($_POST['add_kategoria'])){
    $kategoria_nazov = $_POST['kategoria_nazov']; // Získa hodnotu 'kategoria_nazov' z formulára
    $kategoria_id = $_POST['id']; // Získa hodnotu 'id' z formulára
    
    // Skontroluje, či sú polia 'kategoria_nazov' a 'id' prázdne
    if (empty($kategoria_nazov) || empty($kategoria_id)){
        echo "naplň polia"; // Ak áno, vypíše správu o chybe
    } else {
        // Vloží nový záznam do tabuľky kategoria v databáze
        $insert = "INSERT INTO kategoria (id, kategoria_nazov) VALUES ('$kategoria_id', '$kategoria_nazov');";
        $upload = mysqli_query($conn, $insert); // Vykoná SQL dotaz
        
        // Skontroluje, či bol záznam úspešne vložený
        if ($upload){
            echo "Nová kategória bola vytvorená"; // Ak áno, vypíše správu o úspechu
        } else {
            echo "Nepodarilo sa vytvoriť novú kategóriu"; // Ak nie, vypíše správu o chybe
        }
    }
}

// Skontroluje, či bol odoslaný požiadavok na vymazanie kategórie
if (isset($_GET['delete'])){
    $id = $_GET['delete']; // Získa ID kategórie z parametra URL
    // Vymaže záznam z tabuľky kategoria podľa ID
    $delete = "DELETE FROM kategoria WHERE id=$id";
    $upload = mysqli_query($conn, $delete); // Vykoná SQL dotaz
    
    // Skontroluje, či bol záznam úspešne vymazaný
    if ($upload) {
        echo "Kategoria s ID $id bola úspešne odstránená."; // Ak áno, vypíše správu o úspechu
    } else {
        echo "Chyba pri odstraňovaní kategórie: "; // Ak nie, vypíše správu o chybe
    }
}
?>

<!DOCTYPE html>
<html>
<!-- Link na externý CSS súbor -->
<link rel="stylesheet" href="../css/admin.css">
<body>
<main>
    <!-- Odkaz na domovskú stránku administrátora -->
    <a href="home.php" class="domov"> <- Admin domov</a>
    <div>
        <form method="post">
            <h3> Pridaj novu kategoriu</h3>
            <!-- Vstupné pole pre názov kategórie -->
            <input type="text" placeholder="zadaj nazov kategorie" name="kategoria_nazov" class="box">

            <h3> zadaj cislo kategorie</h3>
            <!-- Vstupné pole pre číslo kategórie -->
            <input type="number" placeholder="zadaj cislo kategorie" name="id" class="box">
            <br><br>
            <!-- Tlačidlo na odoslanie formulára -->
            <input type="submit" name="add_kategoria" value="pridaj">
        </form>
    </div>
    <br><br>

    <table>
        <tr>
            <th>ID Kategorie</th>
            <th>Nazov Kategorie</th>
            <th>Procedura</th>
        </tr>
        <?php
        // Vyberie všetky záznamy z tabuľky kategoria
        $select = mysqli_query($conn, "SELECT * FROM kategoria");
        
        // Pre každý záznam v tabuľke kategoria
        while ($row = mysqli_fetch_assoc($select)){ ?>
            <tr>
                <td><?php echo $row['id']; ?></td> <!-- Vypíše ID kategórie -->
                <td><?php echo $row['kategoria_nazov']; ?></td> <!-- Vypíše názov kategórie -->
                <td>
                    <!-- Odkaz na stránku pre úpravu kategórie, ktorý prejde ID v URL -->
                    <a href="update_kategoria.php?edit=<?php echo $row['id']; ?>">Edit</a> /
                    <!-- Odkaz na vymazanie kategórie, ktorý prejde ID v URL -->
                    <a href="add_kategoria.php?delete=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php }; ?>
    </table>
</main>
</body>
</html>
