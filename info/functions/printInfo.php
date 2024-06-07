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
if (!isset($_GET["grade"]) || !isset($_GET["game"])) exit;
$isSecondary = str_contains($_GET["grade"], "secondary");
if (!isset($_GET["unit"])) {
    $units = json_decode(file_get_contents("../units.json"), true)[$_GET["grade"]];
    if ($isSecondary) $units = $units[$_GET["game"]];
    echo json_encode($units);
} else {
    require "password.php";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $questions = [];
    if ($_GET["game"] == "right-or-wrong") $_GET["game"] = "RightOrWrong";
    if ($_GET["unit"] == "whole") {
        $getStmt = $pdo->prepare("SELECT id, data FROM if0_36665133_TheScienceLab." . 
            ($isSecondary ? "Choose" : ucfirst($_GET["game"])) . "Questions where grade = ?" .
            ($isSecondary ? " and subject = ?" : ""));
        if (!$isSecondary) $getStmt->execute([$_GET["grade"]]);
        else $getStmt->execute([$_GET["grade"], $_GET["game"]]);
        $questions = array();
        while ($data = $getStmt->fetch()) {
            $questions[$data["id"]] = json_decode($data["data"]);
        };
    } else {
        $getStmt = $pdo->prepare("SELECT id, data FROM if0_36665133_TheScienceLab." . 
            ($isSecondary ? "Choose" : ucfirst($_GET["game"])) . "Questions where grade = ?" .
            ($isSecondary ? " and subject = ?" : ""));
        if (!$isSecondary) $getStmt->execute([$_GET["grade"]]);
        else $getStmt->execute([$_GET["grade"], $_GET["game"]]);
        $questions = array();
        while ($data = $getStmt->fetch()) {
            $questions[$data["id"]] = json_decode($data["data"]);
        };
    }
    echo json_encode($questions);
}
?>
