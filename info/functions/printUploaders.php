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
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || !isset($_SESSION["subject"]) || $_SESSION["subject"] != "admin") exit;
$isSecondary = str_contains($_GET["grade"], "secondary");
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$game = "";
switch ($_GET["game"]) {
    case "choose":
    case "biology":
    case "physics":
    case "chemistry":
        $game = "ChooseQuestions";
        break;
    case "right-or-wrong":
        $game = "RightOrWrongQuestions";
        break;
    case "complete":
        $game = "CompleteQuestions";
        break;
    case "match":
        $game = "MatchQuestions";
        break;
    case "give-reason":
    case "what-happens-when":
        $game = "EssayQuestions";
        break;
}    
$getStmt = $pdo->prepare("SELECT id, uploader FROM if0_36665133_TheScienceLab.$game where grade = ?" .
    ($isSecondary ? " and subject = ?" : ""));
if (!$isSecondary) $getStmt->execute([$_GET["grade"]]);
else $getStmt->execute([$_GET["grade"], $_GET["game"]]);
$uploaders = array();
while ($data = $getStmt->fetch()) {
    $getNameStmt = $pdo->prepare("SELECT name FROM if0_36665133_TheScienceLab.Members where username = ?");
    $getNameStmt->execute([$data["uploader"]]);
    $name = $getNameStmt->fetchColumn();
    $uploaders[$data["id"]] = $name;
};
echo json_encode($uploaders);
?>