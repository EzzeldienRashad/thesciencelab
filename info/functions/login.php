<?php
ini_set('session.cookie_samesite','None');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");

session_start();
if (!count($_POST)) $_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    echo "admin";
} elseif (isset($_POST["password"])) {
    require "password.php";
    $maxLoginAttempts = 20;
    $waitTime = "5 minutes";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $getStmt = $pdo->query("SELECT id, date FROM if0_36665133_TheScienceLab.FailedLogins");
    $dates = $getStmt->fetchAll();
    if (count($dates) >= $maxLoginAttempts) { 
        if (count($dates) > $maxLoginAttempts) {
            $deleteStmt = $pdo->prepare("DELETE FROM if0_36665133_TheScienceLab.FailedLogins WHERE id < ?");
            $deleteStmt->bindParam(1, $dates[count($dates) - $maxLoginAttempts]["id"], PDO::PARAM_INT);
            $deleteStmt->execute();
        }
        if ((int) $dates[count($dates) - $maxLoginAttempts]["date"] > strtotime("-" . $waitTime)) {
            echo "lockout";
            exit;
        } else {
            if (password_verify($_POST["password"], '$2y$10$PWZp0FxpLcwgFDTE4SsBFOfif8K675xMibtCZ/I8VTmmNbdbNLl9q')) {
                $_SESSION["isAdmin"] = true;
                echo "admin";
            } else {
                $pdo->query("INSERT INTO if0_36665133_TheScienceLab.FailedLogins (date) VALUES (" . time() . ")");
            }
        }
    } else {
        if (password_verify($_POST["password"], '$2y$10$PWZp0FxpLcwgFDTE4SsBFOfif8K675xMibtCZ/I8VTmmNbdbNLl9q')) {
            $_SESSION["isAdmin"] = true;
            echo "admin";
        } else {
            $pdo->query("INSERT INTO if0_36665133_TheScienceLab.FailedLogins (date) VALUES (" . time() . ")");
        }
    }
}
?>
