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
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
if (!count($_POST)) $_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION["subject"]) && in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "science"))) {
    echo json_encode([$_SESSION["subject"], $_SESSION["username"]]);
} elseif (isset($_COOKIE["tokenKey"]) && isset($_COOKIE["tokenValue"])) {
    $tokenStmt = $pdo->prepare("SELECT subject, username, tokenValue FROM if0_36665133_TheScienceLab.Members WHERE tokenKey = ? limit 1");
    $tokenStmt->execute([$_COOKIE["tokenKey"]]);
    $userInfo = $tokenStmt->fetch();
    if (password_verify($_COOKIE["tokenValue"], $userInfo["tokenValue"])) {
        $_SESSION["subject"] = $userInfo["subject"];
        $_SESSION["username"] = $userInfo["username"];
        $tokenKey = bin2hex(random_bytes(16));
        $tokenValue = bin2hex(random_bytes(16));
        setcookie("tokenKey", $tokenKey, time() + 60 * 60 * 24 * 30, "/", "thesciencelab.infinityfreeapp.com", true);
        setcookie("tokenValue", $tokenValue, time() + 60 * 60 * 24 * 30, "/", "thesciencelab.infinityfreeapp.com", true);
        $updateTokenStmt = $pdo->prepare("Update if0_36665133_TheScienceLab.Members set tokenKey = '$tokenKey', tokenValue = '" . password_hash($tokenValue, PASSWORD_DEFAULT) . "' where username = ?");
        $updateTokenStmt->execute([$_SESSION["username"]]);
        echo json_encode([$_SESSION["subject"], $_SESSION["username"]]);
    }
} elseif (isset($_POST["password"]) && isset($_POST["username"])) {
    $maxLoginAttempts = 5;
    $waitTime = "5 minutes";
    $getLoginsStmt = $pdo->query("DELETE FROM if0_36665133_TheScienceLab.FailedLogins where date < '" . date('Y-m-d H:i:s', strtotime(date("Y/m/d H:i:s") . " -5 minutes")) . "'");
    $getLoginsStmt = $pdo->prepare("SELECT id, date FROM if0_36665133_TheScienceLab.FailedLogins where username = ?");
    $getLoginsStmt->execute([$_POST["username"]]);
    $dates = $getLoginsStmt->fetchAll();
    if (count($dates) >= $maxLoginAttempts) { 
        if (count($dates) > $maxLoginAttempts) {
            $deleteStmt = $pdo->prepare("DELETE FROM if0_36665133_TheScienceLab.FailedLogins WHERE id < ?");
            $deleteStmt->bindParam(1, $dates[count($dates) - $maxLoginAttempts]["id"], PDO::PARAM_INT);
            $deleteStmt->execute();
        }
        if ((int) strtotime($dates[count($dates) - $maxLoginAttempts]["date"]) > strtotime("-" . $waitTime)) {
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
        if (isset($_POST["rememberme"]) && $_POST["rememberme"] == "true") {
            $tokenKey = bin2hex(random_bytes(16));
            $tokenValue = bin2hex(random_bytes(16));
            setcookie("tokenKey", $tokenKey, time() + 60 * 60 * 24 * 30, "/", "thesciencelab.infinityfreeapp.com", true);
            setcookie("tokenValue", $tokenValue, time() + 60 * 60 * 24 * 30, "/", "thesciencelab.infinityfreeapp.com", true);
            $updateTokenStmt = $pdo->prepare("Update if0_36665133_TheScienceLab.Members set tokenKey = '$tokenKey', tokenValue = '" . password_hash($tokenValue, PASSWORD_DEFAULT) . "' where username = ?");
            $updateTokenStmt->execute([$_SESSION["username"]]);
        }
        echo json_encode([$_SESSION["subject"], $_SESSION["username"]]);
    } else {
        $logStmt = $pdo->prepare("INSERT INTO if0_36665133_TheScienceLab.FailedLogins (date, username) VALUES ('" . date("Y-m-d H:i:s") . "', ?)");
        $logStmt->execute([$_POST["username"]]);
        echo "not allowed";
    }
}
?>
