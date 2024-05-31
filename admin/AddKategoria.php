<?php
include_once "Database.php";
$db = new Database();
$conn = $db->getConnection();
$kategoria = new AddKategoria($conn);
class AddKategoria {
    

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        session_start();
        if (!isset($_SESSION['nick'])) {
            header("location: login.php");
            exit();
        }
    }

    public function addKategoria($kategoria_nazov) {
        if (empty($kategoria_nazov)) {
            return "Napĺň polia";
        } else {
            $kategoria_nazov = $this->conn->real_escape_string($kategoria_nazov);
            $insert = "INSERT INTO kategoria (kategoria_nazov) VALUES ('$kategoria_nazov');";
            $upload = $this->conn->query($insert);
            if ($upload) {
                return "Nová kategória bola vytvorená";
            } else {
                return "Nepodarilo sa vytvoriť novú kategóriu";
            }
        }
    }

    public function deleteKategoria($id) {
        $id = $this->conn->real_escape_string($id);
        $delete = "DELETE FROM kategoria WHERE id=$id";
        $upload = $this->conn->query($delete);
        if ($upload) {
            return "Kategoria s ID $id bola úspešne odstránená.";
        } else {
            return "Chyba pri odstraňovaní kategórie";
        }
    }

    public function getKategories() {
        $select = $this->conn->query("SELECT * FROM kategoria");
        return $select->fetch_all(MYSQLI_ASSOC);
    }
}


$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_kategoria'])) {
    $kategoria_nazov = $_POST['kategoria_nazov'];
    $message = $kategoria->addKategoria($kategoria_nazov);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $message = $kategoria->deleteKategoria($id);
}

$kategories = $kategoria->getKategories();

$db->closeConnection();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<main>
    <a href="home.php" class="domov"> <- Admin domov</a>
    <div>
        <form method="post">
            <h3> Pridaj novu kategoriu</h3>
            <input type="text" placeholder="zadaj nazov kategorie" name="kategoria_nazov" class="box">
            <br><br>
            <input type="submit" name="add_kategoria" value="pridaj">
        </form>
        <p><?php echo $message; ?></p>
    </div>
    <br><br>

    <table>
        <tr>
            <th>ID Kategorie</th>
            <th>Nazov Kategorie</th>
            <th>Procedura</th>
        </tr>
        <?php foreach ($kategories as $kategoria): ?>
            <tr>
                <td><?php echo $kategoria['id']; ?></td>
                <td><?php echo $kategoria['kategoria_nazov']; ?></td>
                <td>
                    <a href="update_kategoria.php?edit=<?php echo $kategoria['id']; ?>">Edit</a> /
                    <a href="AddKategoria.php?delete=<?php echo $kategoria['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
</body>
</html>
