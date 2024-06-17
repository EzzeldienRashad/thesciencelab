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
if (!isset($_SESSION["subject"]) || $_SESSION["subject"] != "admin") {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"])) {
    exit;
}
if (!isset($_POST["testCode"]) || !isset($_POST["testDuration"]) || !isset($_POST["chosenQuestions"])) {
    echo "infoerr";
    exit;
}
$isSecondary = str_contains($_GET["grade"], "secondary");
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$testStmt = $pdo->prepare("insert into Tests (questionId, grade, game, validFor, code) values (?, ?, ?, ?, ?)");
$game = in_array($_GET["game"], array("choose", "biology", "physics", "chemistry")) ? "ChooseQuestions" : 
    ($_GET["game"] == "right-or-wrong" ? "RightOrWrongQuestions" : 
    ($_GET["game"] == "complete" ? "CompleteQuestions" : 
    ($_GET["game"] == "match" ? "MatchQuestions" : "")));
foreach (json_decode($_POST["chosenQuestions"]) as $questionId) {
    $testStmt->execute([$questionId, $_GET["grade"], $game, date('Y-m-d H:i:s', strtotime(date("Y/m/d H:i:s") . "+" . $_POST["testDuration"] . " minutes")), $_POST["testCode"]]);
}
echo "successful";
?>
