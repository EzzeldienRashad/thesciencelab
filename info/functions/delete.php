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
if (!isset($_SESSION["subject"]) || !in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "science"))) {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || !isset($_GET["questionnum"])) {
    exit;
}
$isSecondary = str_contains($_GET["grade"], "secondary");
if ($isSecondary && $_GET["game"] != $_SESSION["subject"] && $_SESSION["subject"] != "admin") exit;
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
if (in_array($_GET["game"], ["give-reason", "what-happens-when"])) $_GET["game"] = "Essay";
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$getStmt = $pdo->prepare("Select uploader FROM if0_36665133_TheScienceLab.$game WHERE id = ?");
$getStmt->bindParam(1, $_GET["questionnum"], PDO::PARAM_INT);
$getStmt->execute();
if ($getStmt->fetchColumn() != $_SESSION["username"] && $_SESSION["subject"] != "admin") exit;
$deleteStmt = $pdo->prepare("DELETE FROM if0_36665133_TheScienceLab.$game WHERE id = ?");
$deleteStmt->bindParam(1, $_GET["questionnum"], PDO::PARAM_INT);
$deleteStmt->execute();
?>
