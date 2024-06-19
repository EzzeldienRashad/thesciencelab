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
if (!isset($_SESSION["subject"]) || $_SESSION["subject"] != "admin") exit;
    require "password.php";
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
    $pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $uploadedQuestions = array();
    $grades = array_keys(json_decode(file_get_contents("../units.json"), true));
    foreach ($grades as $grade) {
        $uploadedQuestions[$grade] = 0;
        foreach (["Choose", "RightOrWrong", "Complete", "Match", "Essay", "ScientificTerm"] as $game) {
            $getStmt = $pdo->prepare("SELECT 1 FROM if0_36665133_TheScienceLab." . $game . "Questions where grade = ?");
            $getStmt->execute([$grade]);
            $uploadedQuestions[$grade] += count($getStmt->fetchAll());
        }
    }
    echo json_encode($uploadedQuestions);
?>
