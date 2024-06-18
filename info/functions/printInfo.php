<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
if (!isset($_GET["grade"]) || !isset($_GET["game"])) exit;
$isSecondary = str_contains($_GET["grade"], "secondary");
if (!isset($_GET["unit"])) {
    $units = json_decode(file_get_contents("../units.json"), true)[$_GET["grade"]];
    if ($isSecondary) $units = $units[$_GET["game"]];
    echo json_encode($units);
} else {
    require "password.php";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $questions = [];
    if ($_GET["game"] == "right-or-wrong") $_GET["game"] = "rightOrWrong";
    $requiredData = [];
    switch ($_GET["game"]) {
        case "choose":
        case "biology":
        case "physics":
        case "chemistry":
            $requiredData = ["question", "choiceA", "choiceB", "choiceC", "choiceD", "answer"];
            break;
        case "complete":
            $requiredData = ["part1", "part2", "rightAnswer", "wrongAnswer"];
            break;
        case "match":
            $requiredData = ["colA", "colB"];
            break;
        case "rightOrWrong":
            $requiredData = ["question", "answer"];
            break;
    }
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
    $queryString = "SELECT id, level, uploader";
    foreach ($requiredData as $requiredItem) {
        $queryString .= ", " . $requiredItem;
    }
    if ($game == "ChooseQuestions" || $game == "RightOrWrongQuestions") $queryString .= ", image";
    if (isset($_SESSION["member"]) && $_SESSION["member"] == "admin") $queryString .= ", uploader";
    $queryString .= " FROM if0_36665133_TheScienceLab.$game where grade = ?";
    if ($game == "EssayQuestions") $queryString .= " and type = " . $_GET["game"];
    if ($_GET["unit"] != "whole") $queryString .= " and unit = ?";
    if ($isSecondary) $queryString .= " and subject = ?";
    $getStmt = $pdo->prepare($queryString);
    if ($_GET["unit"] == "whole") {
        if (!$isSecondary) $getStmt->execute([$_GET["grade"]]);
        else $getStmt->execute([$_GET["grade"], $_GET["game"]]);
    } else {
        if (!$isSecondary) $getStmt->execute([$_GET["grade"], $_GET["unit"]]);
        else $getStmt->execute([$_GET["grade"], $_GET["unit"], $_GET["game"]]);
    };
    echo json_encode($getStmt->fetchAll());
}
?>
