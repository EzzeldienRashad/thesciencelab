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
if (!count($_POST)) $_POST = json_decode(file_get_contents("php://input"), true);
if (!isset($_GET["game"]) || !isset($_POST["ids"])) exit;
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$questions = [];
$requiredData = [];
switch ($_GET["game"]) {
    case "ChooseQuestions":
        $requiredData = ["question", "choiceA", "choiceB", "choiceC", "choiceD", "answer"];
        break;
    case "CompleteQuestions":
        $requiredData = ["part1", "part2", "rightAnswer", "wrongAnswer"];
        break;
    case "MatchQuestions":
        $requiredData = ["colA", "colB"];
        break;
    case "RightOrWrongQuestions":
        $requiredData = ["question", "answer"];
        break;
    case "EssayQuestions":
        $requiredData = ["question"];
        break;
    default:
        exit;
}
$queryString = "SELECT level";
foreach ($requiredData as $requiredItem) {
    $queryString .= ", " . $requiredItem;
}
if ($_GET["game"] == "ChooseQuestions" || $_GET["game"] == "RightOrWrongQuestions") $queryString .= ", image";
$queryString .= " FROM if0_36665133_TheScienceLab." . $_GET["game"] . " where id = ?";
foreach ($_POST["ids"] as $questionId) {
    $getStmt = $pdo->prepare($queryString);
    $getStmt->bindParam(1, $questionId, PDO::PARAM_INT);
    $getStmt->execute();
    array_push($questions, $getStmt->fetch());
}
echo json_encode($questions);
?>
