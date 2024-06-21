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
if (!isset($_GET["name"]) || !isset($_GET["code"]) || !isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["result"])) exit;
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
    case "scientific-term":
        $game = "ScientificTermQuestions";
        break;
}    
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$modifyStmt = $pdo->prepare("UPDATE DevicesInTest SET status = 'completed', result = ? WHERE name = ? and code = ? and grade = ? and game = ?");
$modifyStmt->execute([$_GET["result"], $_GET["name"], $_GET["code"], $_GET["grade"], $game]);
?>