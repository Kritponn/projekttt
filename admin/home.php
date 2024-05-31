<?php
session_start(); // Spustí alebo obnoví existujúcu session
if(!isset($_SESSION['nick'])){ // Skontroluje, či je premenná session 'nick' nastavená
    header("location: login.php"); // Ak nie je nastavená, presmeruje užívateľa na stránku login.php
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/admin.css"> <!-- Link na externý CSS súbor -->
<body>

<h1>Admin home</h1>
<ul class="home">
    <li> <a href="AddProduct.php"> Editovanie - Jedlo </a> </li> <!-- Odkaz na stránku pre editáciu jedál -->
    <li> <a href="AddKategoria.php">Editovanie - Kategória jedla  </a> </li> <!-- Odkaz na stránku pre editáciu kategórií jedál -->
</ul>
<h3> prihlaseny - <?php echo $_SESSION['nick']?> </h3> <!-- Zobrazenie prihláseného používateľského mena -->
<br>
<li> <a href="AddAdmin.php"> Editovanie - Admin </a> </li> <!-- Odkaz na stránku pre editáciu adminov -->
<br>
<!-- Formulár na odhlásenie -->
<form method="post">
    <input type="submit" name="out" value="Odhlásiť sa"> <!-- Tlačidlo na odhlásenie -->
</form>

<?php
if(isset($_POST['out'])){ // Skontroluje, či bolo stlačené tlačidlo na odhlásenie
    session_destroy(); // Zničí všetky údaje session
    header("location: login.php"); // Presmeruje užívateľa na stránku login.php
}
?>
</body>
</html>
