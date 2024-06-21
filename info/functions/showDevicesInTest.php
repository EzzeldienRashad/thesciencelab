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
if (!isset($_GET["code"]) || !isset($_GET["grade"]) || !isset($_GET["game"])) exit;
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$getStmt = $pdo->prepare("SELECT name, status, result from if0_36665133_TheScienceLab.DevicesInTest where code = ? and grade = ? and game = ?");
$getStmt->execute([$_GET["code"], $_GET["grade"], $_GET["game"]]);
echo json_encode($getStmt->fetchAll());
?>