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
if (!isset($_SESSION["subject"]) || $_SESSION["subject"] != "admin") exit;
    require "password.php";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $uploaders = array();
    $membersStmt = $pdo->query("SELECT username FROM if0_36665133_TheScienceLab.Members");
    $members = $membersStmt->fetchAll(PDO::FETCH_COLUMN);
    $membersArabicStmt = $pdo->query("SELECT name FROM if0_36665133_TheScienceLab.Members");
    $membersArabic = $membersArabicStmt->fetchAll(PDO::FETCH_COLUMN);
    for ($i = 0; $i < count($members); $i++) {
        $getStmt = $pdo->prepare("SELECT 1 FROM if0_36665133_TheScienceLab.ChooseQuestions where uploader = ?");
        $getStmt->execute([$members[$i]]);
        $uploaders[$membersArabic[$i]] = count($getStmt->fetchAll());
        $getStmt = $pdo->prepare("SELECT 1 FROM if0_36665133_TheScienceLab.RightOrWrongQuestions where uploader = ?");
        $getStmt->execute([$members[$i]]);
        $uploaders[$membersArabic[$i]] += count($getStmt->fetchAll());
        $getStmt = $pdo->prepare("SELECT 1 FROM if0_36665133_TheScienceLab.CompleteQuestions where uploader = ?");
        $getStmt->execute([$members[$i]]);
        $uploaders[$membersArabic[$i]] += count($getStmt->fetchAll());
        $getStmt = $pdo->prepare("SELECT 1 FROM if0_36665133_TheScienceLab.MatchQuestions where uploader = ?");
        $getStmt->execute([$members[$i]]);
        $uploaders[$membersArabic[$i]] += count($getStmt->fetchAll());
    }
    echo json_encode($uploaders);
?>
