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
if (isset($_SESSION["subject"]) && in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "none"))) {
    echo json_encode([$_SESSION["subject"], $_SESSION["username"]]);
} elseif (isset($_POST["password"]) && isset($_POST["username"])) {
    require "password.php";
    $maxLoginAttempts = 5;
    $waitTime = "5 minutes";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $getLoginsStmt = $pdo->prepare("SELECT id, date FROM if0_36665133_TheScienceLab.FailedLogins where username = ?");
    $getLoginsStmt->execute([$_POST["username"]]);
    $dates = $getLoginsStmt->fetchAll();
    if (count($dates) >= $maxLoginAttempts) { 
        if (count($dates) > $maxLoginAttempts) {
            $deleteStmt = $pdo->prepare("DELETE FROM if0_36665133_TheScienceLab.FailedLogins WHERE id < ?");
            $deleteStmt->bindParam(1, $dates[count($dates) - $maxLoginAttempts]["id"], PDO::PARAM_INT);
            $deleteStmt->execute();
        }
        if ((int) $dates[count($dates) - $maxLoginAttempts]["date"] > strtotime("-" . $waitTime)) {
            echo "blocked";
            exit;
        } else {
            login($pdo);
        }
    } else {
        login($pdo);
    }
}

function login($pdo) {
    $getPasswdStmt = $pdo->prepare("SELECT subject, password FROM if0_36665133_TheScienceLab.Members where username = ?");
    $getPasswdStmt->execute([$_POST["username"]]);
    $userInfo = $getPasswdStmt->fetch();
    if (password_verify($_POST["password"], $userInfo["password"])) {
        $_SESSION["subject"] = $userInfo["subject"];
        $_SESSION["username"] = $_POST["username"];
        echo json_encode([$_SESSION["subject"], $_SESSION["username"]]);
    } else {
        $logStmt = $pdo->prepare("INSERT INTO if0_36665133_TheScienceLab.FailedLogins (date, username) VALUES (" . time() . ", ?)");
        $logStmt->execute([$_POST["username"]]);
        echo "not allowed";
    }
}
?>
