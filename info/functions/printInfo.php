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
    $requiredData = [];
    $game = "";
    switch ($_GET["game"]) {
        case "choose":
        case "biology":
        case "physics":
        case "chemistry":
            $requiredData = ["question", "choiceA", "choiceB", "choiceC", "choiceD", "answer"];
            $game = "ChooseQuestions";
            break;
        case "complete":
            $requiredData = ["part1", "part2", "rightAnswer", "wrongAnswer"];
            $game = "CompleteQuestions";
            break;
        case "match":
            $requiredData = ["colA", "colB"];
            $game = "MatchQuestions";
            break;
        case "right-or-wrong":
            $requiredData = ["question", "answer"];
            $game = "RightOrWrongQuestions";
            break;
        case "give-reason":
        case "what-happens-when":
            $requiredData = ["question", "answer"];
            $game = "EssayQuestions";
            break;
        case "scientific-term":
            $requiredData = ["question", "answer"];
            $game = "ScientificTermQuestions";
            break;
    }
        $queryString = "SELECT id, level, uploader";
    foreach ($requiredData as $requiredItem) {
        $queryString .= ", " . $requiredItem;
    }
    if ($game == "ChooseQuestions" || $game == "RightOrWrongQuestions") $queryString .= ", image";
    if (isset($_SESSION["subject"]) && $_SESSION["subject"] == "admin") $queryString .= ", uploader";
    $queryString .= " FROM if0_36665133_TheScienceLab.$game where grade = ?";
    if ($game == "EssayQuestions") $queryString .= " and type = '" . $_GET["game"] . "'";
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
