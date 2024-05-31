<?php
include_once "Database.php"; // Vloží súbor s pripojením do databázy

// Skontroluje, či je premenná $conn prázdna (neobsahuje pripojenie k databáze)
$db = new Database();
$conn = $db->getConnection();

session_start(); // Spustí session na začiatku skriptu

// Skontroluje, či je premenná 'nick' nastavená v session
if (!isset($_SESSION['nick'])){
    header("location: login.php"); // Ak nie, presmeruje užívateľa na stránku login.php
}

// Skontroluje, či bol odoslaný formulár na pridanie produktu
if (isset($_POST['AddProduct'])){
    $jedlo_nazov = $_POST['nazov']; // Získa hodnotu 'nazov' z formulára
    $jedlo_popis = $_POST['popis']; // Získa hodnotu 'popis' z formulára
    $kategoria_id = $_POST['kategoria_id']; // Získa hodnotu 'kategoria_id' z formulára
    $jedlo_cena1 = $_POST['jedlo_cena1']; // Získa hodnotu 'jedlo_cena1' z formulára
    $jedlo_photo_tmp_name  = $_FILES['jedlo_photo_url']['tmp_name']; // Dočasná cesta k nahratému súboru
    $jedlo_photo_folder = "uploaded_img/" . basename($_FILES['jedlo_photo_url']['name']); // Cieľová cesta na uloženie súboru

    // Skontroluje, či sú polia 'nazov' a 'kategoria_id' prázdne
    if (empty($jedlo_nazov) || empty($kategoria_id)){
        echo "Zadaj názov produktu a zvol kategoriu"; // Ak áno, vypíše správu o chybe
    } else {
        // Skontroluje, či adresár 'uploaded_img' existuje, ak nie, vytvorí ho
        if (!is_dir('uploaded_img')) {
            mkdir('uploaded_img');
        }
        // Presunie nahratý súbor do cieľovej zložky
        if (move_uploaded_file($jedlo_photo_tmp_name, $jedlo_photo_folder)) {
            // Vloží nový záznam do tabuľky jedlo v databáze
            $insert = "INSERT INTO jedlo(jedlo_nazov, jedlo_popis, kategoria_id, jedlo_cena1, jedlo_photo_url) VALUES ('$jedlo_nazov', '$jedlo_popis', '$kategoria_id', '$jedlo_cena1', '$jedlo_photo_folder')";
            $upload = mysqli_query($conn, $insert); // Vykoná SQL dotaz

            // Skontroluje, či bol záznam úspešne vložený
            if ($upload){
                echo "Nový produkt nahraný"; // Ak áno, vypíše správu o úspechu
            } else {
                echo "Nepodarilo sa nahrať nový produkt"; // Ak nie, vypíše správu o chybe
            }
        } else {
            echo "Nepodarilo sa nahrať obrázok"; // Ak presun súboru zlyhal, vypíše správu o chybe
        }
    }
}

// Skontroluje, či bol odoslaný požiadavok na vymazanie produktu
if (isset($_GET['delete'])){
    $id = $_GET['delete']; // Získa ID produktu z parametra URL
    $delete = mysqli_query($conn, "DELETE FROM jedlo WHERE id=$id"); // Vykoná SQL dotaz na vymazanie záznamu

    // Skontroluje, či bol záznam úspešne vymazaný
    if ($delete) {
        echo "Produkt s ID $id bol úspešne odstránený."; // Ak áno, vypíše správu o úspechu
    } else {
        echo "Chyba pri odstraňovaní produktu: "; // Ak nie, vypíše správu o chybe
    }
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Link na externý CSS súbor -->
<body>
<main>
    <a href="home.php"> <- Admin domov</a> <!-- Odkaz na domovskú stránku administrátora -->
    <h1>Admin Stránka produktov</h1>

    <div>
        <form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data"> <!-- Formulár na pridanie produktu -->
            <h3> Pridaj nový produkt</h3>
            <input type="text" placeholder="zadaj nazov produktu" name="nazov"> <!-- Vstupné pole pre názov produktu -->
            <br><br><br>
            <input type="text" placeholder="zadaj popis produktu" name="popis"> <!-- Vstupné pole pre popis produktu -->
            <br><br><br>
            <label for="kategoria">Kategória:</label>
            <select id="kategoria" name="kategoria" onchange="document.getElementById('kategoria_id').value=this.value"> <!-- Výberové pole pre kategóriu -->
                <option value="" disabled selected>Vyber Kategoriu</option>
                <?php
                $sql = "SELECT id, kategoria_nazov FROM kategoria"; // SQL dotaz na získanie kategórií
                $result = mysqli_query($conn, $sql); // Vykoná SQL dotaz

                // Pre každý záznam v tabuľke kategoria
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id'] . '">' . $row['kategoria_nazov'] . '</option>'; // Vypíše možnosti pre výber
                }
                ?>
            </select>
            <input type="hidden" id="kategoria_id" name="kategoria_id"> <!-- Skryté pole pre ID kategórie -->
            <br><br><br>
            <h3> Zadaj cenu</h3>
            <input type="number" placeholder="zadaj cenu produktu" name="jedlo_cena1"> <!-- Vstupné pole pre cenu produktu -->
            <br><br><br>
            <h3> Nahraj fotku </h3>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="jedlo_photo_url"> <!-- Vstupné pole pre nahratie fotky -->
            <br><br><br>
            <input type="submit" name="add_product" value="pridaj"> <!-- Tlačidlo na odoslanie formulára -->
        </form>
    </div>

    <div>
        <table>
            <thead>
            <tr>
                <th>Fotka jedla</th>
                <th>Názov jedla</th>
                <th>Popis jedla</th>
                <th>Cena jedla</th>
                <th>Kategória jedla</th>
                <th>Akcia</th>
            </tr>
            </thead>
            <?php
            // Vyberie všetky záznamy z tabuľky jedlo spolu s názvami kategórií
            $select = mysqli_query($conn, "SELECT j.*, k.kategoria_nazov FROM jedlo j JOIN kategoria k ON j.kategoria_id = k.id");

            // Pre každý záznam v tabuľke jedlo
            while ($row = mysqli_fetch_assoc($select)){
                ?>
            <tr>
                <td><img src="<?php echo $row['jedlo_photo_url']; ?>" height="100"></td> <!-- Vypíše fotku jedla -->
                <td><?php echo $row['jedlo_nazov']; ?></td> <!-- Vypíše názov jedla -->
                <td><?php echo $row['jedlo_popis']; ?></td> <!-- Vypíše popis jedla -->
                <td><?php echo $row['jedlo_cena1']; ?></td> <!-- Vypíše cenu jedla -->
                <td><?php echo $row['kategoria_nazov']; ?></td> <!-- Vypíše názov kategórie -->
                <td>
                    <a href="update_product.php?edit=<?php echo $row['id']; ?>">Edit</a> / <!-- Odkaz na úpravu produktu -->
                    <a href="AddProduct.php?delete=<?php echo $row['id']; ?>">Delete</a> <!-- Odkaz na vymazanie produktu -->
                </td>
            </tr>
            <?php }; ?>
        </table>
    </div>
</main>
</body>
</html>
