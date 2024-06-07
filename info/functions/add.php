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
if (!isset($_SESSION["subject"]) || !in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "none"))) {
    "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || empty($_POST)) {
    exit;
}
$isSecondary = str_contains($_GET["grade"], "secondary");
if ($isSecondary && $_GET["game"] != $_SESSION["subject"] && $_SESSION["subject"] != "admin") exit;
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
if (in_array($_GET["game"], array("choose", "biology", "physics", "chemistry"))) {
    if (!isset($_POST["question"]) || !isset($_POST["first"]) || !isset($_POST["second"]) || !isset($_POST["third"]) || !isset($_POST["fourth"]) || !isset($_POST["number"])) {
        echo "infoerr";
        exit;
    }
    $addStmt = $pdo->prepare("insert into ChooseQuestions (data, grade, subject, unit, uploader) values (?, ?, ?, ?, ?)");
    $addStmt->execute([json_encode(array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"]))), $_GET["grade"], ($isSecondary ? $_GET["game"] : "science"), $_GET["unit"], $_SESSION["username"]]);
} elseif ($_GET["game"] == "right-or-wrong") {
    if (!isset($_POST["question"]) || !isset($_POST["answer"])) {
        echo "infoerr";
        exit;
    }
    $addStmt = $pdo->prepare("insert into RightOrWrongQuestions (data, grade, unit, uploader) values (?, ?, ?, ?)");
    $addStmt->execute([json_encode(array(trim($_POST["question"]), $_POST["answer"])), $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
} elseif ($_GET["game"] == "complete") {
    if (!isset($_POST["question"]) || !isset($_POST["right"]) || !isset($_POST["wrong"])) {
        echo "infoerr";
        exit;
    }
    $questionParts = preg_split('/\\.{3,}/', $_POST["question"]);
    if (count($questionParts) != 2) {
        echo "spacenumerr";
        exit;
    } else {
        $addStmt = $pdo->prepare("insert into CompleteQuestioins (data, grade, unit, uploader) values (?, ?, ?, ?, ?)");
        $addStmt->execute([json_encode(array($questionParts[0], array($_POST["right"], $_POST["wrong"]), $questionParts[1])), $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
    }
} elseif ($_GET["game"] == "match") {
    if (!isset($_POST["questions"]) || !isset($_POST["answers"])) {
        echo "infoerr";
        exit;
    } elseif (count($_POST["questions"]) != count($_POST["answers"])) {
        echo "answernumerr";
        exit;
    }
    $arr = array();
    for ($i = 0; $i < count($_POST["questions"]); $i++) {
        $arr[$_POST["questions"][$i]] = $_POST["answers"][$i];
    }
    $addStmt = $pdo->prepare("insert into MatchQuestions (data, grade, unit, uploader) values (?, ?, ?, ?, ?)");
    $addStmt->execute([json_encode($arr), $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
}
echo "successful";
?>
