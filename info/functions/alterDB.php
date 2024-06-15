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
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$getStmt = $pdo->query("select id, data from ChooseQuestions");
while ($dataJson = $getStmt->fetch()) {
    $id = $dataJson["id"];
    $data = json_decode($dataJson["data"], true);
    $pdo->prepare("update ChooseQuestions set question = ?, choiceA = ?, choiceB = ?, choiceC=?, choiceD=?, answer=?, image=? where id = ?")->execute([$data[0], $data[1][0], $data[1][1], $data[1][2], $data[1][3], $data[2], $data[3], $id]);
}
$getStmt = $pdo->query("select id, data from RightOrWrongQuestions");
while ($dataJson = $getStmt->fetch()) {
    $id = $dataJson["id"];
    $data = json_decode($dataJson["data"], true);
    $pdo->prepare("update RightOrWrongQuestions set question=?, answer=?, image=? where id = ?")->execute([$data[0], $data[1], $data[2], $id]);
}
$getStmt = $pdo->query("select id, data from CompleteQuestions");
while ($dataJson = $getStmt->fetch()) {
    $id = $dataJson["id"];
    $data = json_decode($dataJson["data"], true);
    $pdo->prepare("update CompleteQuestions set part1=?, part2=?, rightAnswer=?, wrongAnswer=? where id = ?")->execute([$data[0], $data[2], $data[1][0], $data[1][1], $id]);
}
$getStmt = $pdo->query("select id, data from MatchQuestions");
while ($dataJson = $getStmt->fetch()) {
    $id = $dataJson["id"];
    $data = json_decode($dataJson["data"], true);
    $pdo->prepare("update MatchQuestions set colA=?, colB=? where id = ?")->execute([json_encode(array_keys($data)), json_encode(array_values($data)), $id]);
}
echo "successful";
?>
