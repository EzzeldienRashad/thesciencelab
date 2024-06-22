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
if (!isset($_GET["name"]) || !isset($_GET["code"]) || !isset($_GET["grade"]) || !isset($_GET["game"])) exit;
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$pdo->query("DELETE FROM Tests WHERE validFor < '" . date("Y-m-d H:i:s") . "'");
$pdo->query("DELETE FROM DevicesInTest WHERE validFor < '" . date("Y-m-d H:i:s") . "'");
$isSecondary = str_contains($_GET["grade"], "secondary");
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
$checkStmt = $pdo->prepare("SELECT 1 FROM DevicesInTest where name = ? and grade = ? and game = ? and code = ?");
$checkStmt->execute([$_GET["name"], $_GET["grade"], $game, $_GET["code"]]);
$deviceInTest = $checkStmt->fetch();
if (is_array($deviceInTest) && count($deviceInTest)) {
    echo "taken";
    exit;
}
$getStmt = $pdo->prepare("SELECT questionId, validFor FROM Tests where code = ? and grade = ? and game = ?");
$getStmt->execute([$_GET["code"], $_GET["grade"], $game]);
$questionsInfo = $getStmt->fetchAll();
if (!is_array($questionsInfo) || !count($questionsInfo)) {
    echo "notfound";
    exit;
}
$enterTestStmt = $pdo->prepare("INSERT INTO if0_36665133_TheScienceLab.DevicesInTest (name, grade, game, code, validFor) values (?, ?, ?, ?, ?)");
$enterTestStmt->execute([$_GET["name"], $_GET["grade"], $game, $_GET["code"], $questionsInfo[0]["validFor"]]);
$queryString = "SELECT id";
foreach ($requiredData as $requiredItem) {
    $queryString .= ", " . $requiredItem;
}
if ($game == "ChooseQuestions" || $game == "RightOrWrongQuestions") $queryString .= ", image";
$queryString .= " FROM if0_36665133_TheScienceLab.$game where grade = ? and id in (" . str_repeat("?, ", count($questionsInfo) - 1) . "?)";
$getStmt = $pdo->prepare($queryString);
$getStmt->execute(array_merge([$_GET["grade"]], array_map(function ($arr) {return $arr["questionId"];}, $questionsInfo)));
echo json_encode($getStmt->fetchAll());
?>
