<?php
include_once "db.php"; 

// Skontroluje, či je premenná $conn prázdna (neobsahuje pripojenie k databáze)
if (empty($conn)){
    $conn=new stdClass(); // Ak áno, vytvorí novú prázdnu objektovú premennú $conn
}

session_start(); // Spustí session na začiatku skriptu

// Skontroluje, či je premenná 'nick' nastavená v session
if(!isset($_SESSION['nick'])){
    header("location: login.php"); // Ak nie tak ma presmeruje na login.php
}

// Skontroluje, či bol odoslaný formulár na pridanie administrátora
if (isset($_POST['add_admin'])){
    $nick = $_POST['nick']; // Získa hodnotu 'nick' z formulára
    $heslo = $_POST['heslo']; // Získa hodnotu 'heslo' z formulára
    
    // Skontroluje, či sú polia 'nick' a 'heslo' prázdne
    if(empty($nick) || empty($heslo)){
        echo "Vyplň všetky polia"; // Ak áno, vypíše správu o chybe
    } else {
        // Vloží nový záznam do tabuľky login v databáze
        $insert = "INSERT INTO login (meno, heslo) VALUES ('$nick', '$heslo');";
        $upload = mysqli_query($conn, $insert); // Vykoná SQL dotaz
        
        // Skontroluje, či bol záznam úspešne vložený
        if ($upload){
            echo "Nový používateľ bol vytvorený"; // Ak áno, vypíše správu že bol vytvorený 
        } else {
            echo "Nepodarilo sa vytvoriť nového používateľa"; // Ak nie, vypíše správu že nebol vytvoreny 
        }
    }
}

// Skontroluje, či bol odoslaný požiadavok na vymazanie používateľa
if (isset($_GET['delete'])){
    $id = $_GET['delete']; // Získa ID používateľa z parametra URL
    // Vymaže záznam z tabuľky login podľa ID
    $delete = "DELETE FROM login WHERE id=$id";
    $upload = mysqli_query($conn, $delete); // Vykoná SQL dotaz
    
    // Skontroluje, či bol záznam úspešne vymazaný
    if ($upload) {
        echo "Používateľ s ID $id bol úspešne odstránený."; // Ak áno, vypíše správu o úspechu
    } else {
        echo "Chyba pri odstraňovaní používateľa: "; // Ak nie, vypíše správu o chybe
    }
}
?>

<!DOCTYPE html>
<html>

<link rel="stylesheet" href="../css/admin.css">
<body>
<main>
    <!-- Odkaz na domovskú stránku administrátora -->
    <a href="home.php" class="domov"> <- Admin domov</a>
    <div>
        <form method="post">
            <h3> Pridaj nového Admina </h3>
            <!-- Vstupné pole pre užívateľské meno -->
            <input type="text" placeholder="Používateľské meno" name="nick">
            <h3> Zadaj heslo </h3>
            <!-- Vstupné pole pre heslo -->
            <input type="password" placeholder="zadaj heslo" name="heslo">
            <br><br>
            <!-- Tlačidlo na odoslanie formulára -->
            <input type="submit" name="add_admin" value="pridaj">
        </form>
    </div>
    <br><br>

    <table>
        <tr>
            <th>Nick</th>
            <th>Zmazanie používateľa</th>
            <th>Zmena mena používateľa</th>
            <th>Zmena hesla používateľa</th>
        </tr>
        <?php
        // Vyberie všetky záznamy z tabuľky login
        $select = mysqli_query($conn, "SELECT * FROM login");
        
        // Pre každý záznam v tabuľke login
        while ($row = mysqli_fetch_assoc($select)){ ?>
            <tr>
                <td><?php echo $row['meno']; ?></td> <!-- Vypíše meno používateľa -->
                <td>
                    <!-- Odkaz na vymazanie používateľa, ktorý prejde ID v URL -->
                    <a href="add_admin.php?delete=<?php echo $row['id']; ?>">Zmazať</a>
                </td>
                <td>
                    <!-- Odkaz na stránku pre zmenu mena používateľa -->
                    <a href="update_admin_meno.php?edit=<?php echo $row['id']; ?>">Zmena mena</a>
                </td>
                <td>
                    <!-- Odkaz na stránku pre zmenu hesla používateľa -->
                    <a href="update_admin_heslo.php?edit=<?php echo $row['id']; ?>">Zmena hesla</a>
                </td>
            </tr>
        <?php }; ?>
    </table>
</main>
</body>
</html>
