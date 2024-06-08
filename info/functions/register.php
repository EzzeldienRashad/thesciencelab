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
if (!isset($_SESSION["subject"]) || !in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "none"))) {
    echo "logout";
    exit;
}
if (!isset($_POST["username"]) || !isset($_POST["arabicname"]) || !isset($_POST["phone"]) || !isset($_POST["subject"])) {
    echo "error";
    exit;
}
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$registerStmt = $pdo->prepare("insert into if0_36665133_TheScienceLab.Members (name, username, phone, subject, password) values (?, ?, ?, ?, 'none')");
$registerStmt->execute([$_POST["arabicname"], $_POST["username"], $_POST["phone"], $_POST["subject"]]);
echo "successful";
?>
