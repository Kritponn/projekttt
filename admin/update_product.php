<?php
include_once "Database.php"; // Zahrnutie súboru "db.php", ktorý obsahuje pripojenie k databáze

$db = new Database();
$conn = $db->getConnection();
session_start(); // Spustí alebo obnoví existujúcu session

if(!isset( $_SESSION['nick'])){ // Ak užívateľ nie je prihlásený, presmeruje ho na stránku login.php
    header("location: login.php");
}

$id =$_GET['edit']; // Získa ID produktu, ktorý sa má upraviť, z parametra 'edit' v URL
//echo $id; // Tento riadok je zakomentovaný, ale môže byť použitý na debugovanie

if (isset($_POST['update_product'])){ // Ak bol odoslaný formulár na aktualizáciu produktu
    $jedlo_nazov = $_POST['nazov']; // Získa nový názov produktu z formulára
    $jedlo_popis = $_POST['popis']; // Získa nový popis produktu z formulára
    $jedlo_cena1 = $_POST['jedlo_cena1']; // Získa novú cenu produktu z formulára
    $kategoria_id = $_POST['kategoria_id']; // Získa nové ID kategórie produktu z formulára
    $jedlo_photo_tmp_name = $_FILES['jedlo_photo_url']['tmp_name']; // Získa dočasný názov súboru obrázka z formulára
    $jedlo_photo_folder = "uploaded_img/".basename($_FILES['jedlo_photo_url']['name']); // Zloží cestu k uploadovanej fotke

    if(empty($jedlo_nazov) || empty($kategoria_id)  ){
        echo "Zadaj názov produktu a zvol kategoriu "; // Ak nie sú vyplnené povinné polia, vypíše chybu
    } else {
        if (!is_dir('uploaded_img')) { // Ak adresár pre obrázky neexistuje, vytvorí ho
            mkdir('uploaded_img');
        }
        // Ak bolo vyplnené pole input file a nahral sa súbor
        if(!empty($jedlo_photo_tmp_name)){
            $jedlo_photo_folder = "uploaded_img/".basename($_FILES['jedlo_photo_url']['name']);
            if (move_uploaded_file($jedlo_photo_tmp_name, $jedlo_photo_folder)) { // Presunie nahraný súbor do cieľového adresára
                $update = "UPDATE jedlo SET kategoria_id='$kategoria_id', jedlo_nazov='$jedlo_nazov', jedlo_popis='$jedlo_popis', jedlo_cena1='$jedlo_cena1', jedlo_photo_url='$jedlo_photo_folder'  WHERE id='$id'";
                $upload = mysqli_query($conn,$update); // Aktualizuje záznam o produkte v databáze
                if ($upload){
                    echo "Produkt bol aktualizovaný"; // Ak sa aktualizácia podarila, vypíše správu o úspechu
                } else {
                    echo "Aktualizácia sa nepodarila"; // Ak sa aktualizácia nepodarila, vypíše chybovú správu
                }
            } else {
               echo "Nepodarilo sa nahrať obrázok"; // Ak sa nepodarilo nahrať obrázok, vypíše chybovú správu
            }
        } else {
            $update = "UPDATE jedlo SET kategoria_id='$kategoria_id', jedlo_nazov='$jedlo_nazov', jedlo_popis='$jedlo_popis', jedlo_cena1='$jedlo_cena1' WHERE id='$id'";
            $upload = mysqli_query($conn,$update); // Aktualizuje záznam o produkte v databáze
            if ($upload){
                echo "Produkt bol aktualizovaný"; // Ak sa aktualizácia podarila, vypíše správu o úspechu
            } else {
                echo "Aktualizácia sa nepodarila"; // Ak sa aktualizácia nepodarila, vypíše chybovú správu
            }
        }
    }

}

?>
<!doctype html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update stránka</title>
</head>
<body>
<div>
    <?php // vyberie záznam z tabulky jedlo podla Iid
    $select = mysqli_query($conn, "SELECT * FROM jedlo WHERE id='$id'");
    if (mysqli_num_rows($select) == 0) {
        echo "Záznam s id = $id neexistuje";
    } else {// Načítanie ďalšieho riadku z výsledku dotazu a uloženie ho do asociatívneho poľa ($row)
        $row = mysqli_fetch_assoc($select); // associativny slovník
        ?>
        <form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
    <!-- Začiatok formulára na editáciu produktu -->
    <h3> edituj produkt</h3>
    
    <!-- Pole pre názov produktu, predvyplnené hodnotou z databázy -->
    <input type="text"  value="<?php echo $row['jedlo_nazov'];?>" name="nazov">
    <br> <br> <br>
    
    <!-- Pole pre popis produktu, predvyplnené hodnotou z databázy -->
    <input type="text"  value="<?php echo $row['jedlo_popis'];?>" name="popis" >
    <br> <br> <br>
    
    <!-- Pole pre cenu produktu, predvyplnené hodnotou z databázy -->
    <h3> Zadaj cenu</h3>
    <input type="number"name="jedlo_cena1" value="<?php echo $row['jedlo_cena1'];?>" >
    <br> <br> <br>
    
    <!-- Pole pre nahranie fotky produktu -->
    <h3> Nahraj fotku </h3>
    <input type="file" accept="image/png, image/jpeg, image/jpg" name="jedlo_photo_url" >
    <br><br>
    
    <!-- Zobrazenie aktuálnej fotky produktu -->
    <img src="<?php echo $row['jedlo_photo_url']; ?>" alt="Aktuálny obrázok">
    <br> <br> <br>
    
    <!-- Rozbaľovacie menu pre výber kategórie -->
    <label for="kategoria">Kategória:</label>
    <select id="kategoria" name="kategoria" onchange="document.getElementById('kategoria_id').value=this.value">
        <option value="" disabled selected>Vyber Kategoriu</option>
        <?php
        // Načítanie kategórií z databázy a vytvorenie možností pre rozbaľovacie menu
        $sql = "SELECT id, kategoria_nazov FROM kategoria";
        $result = mysqli_query($conn, $sql);
        while ($categoryRow = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $categoryRow['id'] . '">' . $categoryRow['kategoria_nazov'] . '</option>';
        }
        ?>
    </select>
            <input type="hidden" id="kategoria_id" name="kategoria_id">
            <br> <br>
            <input type="submit"  name="update_product" value="edituj">
            <a href="AddProduct.php"> Naspäť</a>
            <a href="home.php"> Admin domov</a>
        </form>
        <?php
    }
    ?>

</div>

</body>
</html>
