<?php
include_once "Database.php";

class AddAdmin {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        session_start();
        if (!isset($_SESSION['nick'])) {
            header("location: login.php");
            exit();
        }
    }

    public function addAdmin($nick, $heslo) {
        if (empty($nick) || empty($heslo)) {
            return "Vyplň všetky polia";
        } else {
            $nick = $this->conn->real_escape_string($nick);
            $heslo = $this->conn->real_escape_string($heslo);
            $insert = "INSERT INTO login (meno, heslo) VALUES ('$nick', '$heslo');";
            $upload = $this->conn->query($insert);
            if ($upload) {
                return "Nový používateľ bol vytvorený";
            } else {
                return "Nepodarilo sa vytvoriť nového používateľa";
            }
        }
    }

    public function deleteAdmin($id) {
        $id = $this->conn->real_escape_string($id);
        $delete = "DELETE FROM login WHERE id=$id";
        $upload = $this->conn->query($delete);
        if ($upload) {
            return "Používateľ s ID $id bol úspešne odstránený.";
        } else {
            return "Chyba pri odstraňovaní používateľa: ";
        }
    }

    public function getAdmins() {
        $select = $this->conn->query("SELECT * FROM login");
        return $select->fetch_all(MYSQLI_ASSOC);
    }
}

$db = new Database();
$conn = $db->getConnection();
$admin = new AddAdmin($conn);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $nick = $_POST['nick'];
    $heslo = $_POST['heslo'];
    $message = $admin->addAdmin($nick, $heslo);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $message = $admin->deleteAdmin($id);
}

$admins = $admin->getAdmins();

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
            <h3> Pridaj nového Admina </h3>
            <input type="text" placeholder="Používateľské meno" name="nick">
            <h3> Zadaj heslo </h3>
            <input type="password" placeholder="zadaj heslo" name="heslo">
            <br><br>
            <input type="submit" name="add_admin" value="pridaj">
        </form>
        <p><?php echo $message; ?></p>
    </div>
    <br><br>

    <table>
        <tr>
            <th>Nick</th>
            <th>Zmazanie používateľa</th>
            <th>Zmena mena používateľa</th>
            <th>Zmena hesla používateľa</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?php echo $admin['meno']; ?></td>
                <td><a href="AddAdmin.php?delete=<?php echo $admin['id']; ?>">Zmazať</a></td>
                <td><a href="update_admin_meno.php?edit=<?php echo $admin['id']; ?>">Zmena mena</a></td>
                <td><a href="update_admin_heslo.php?edit=<?php echo $admin['id']; ?>">Zmena hesla</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
</body>
</html>
