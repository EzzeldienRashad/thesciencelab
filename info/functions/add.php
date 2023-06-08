<?php
ini_set('session.cookie_secure', "1"); 
ini_set('session.cookie_httponly', "1"); 
ini_set('session.cookie_samesite','None');
session_start();
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");
$msg = "";
$isAdmin = false;
$isContributor = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
}
if (isset($_SESSION["isContributor"]) && $_SESSION["isContributor"] == true) {
    $isContributor = true;
}
if (!$isAdmin && !$isContributor) {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || empty($_POST)) {
    exit;
}
$path = "../grades/" . $_GET["grade"] . "/" . $_GET["game"] . ($isAdmin ? "/" : "/approval/") . $_GET["unit"];
$arr = json_decode(file_get_contents($path), true);
if ($_GET["game"] == "choose") {
    array_push($arr, array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"])));
} elseif ($_GET["game"] == "right_or_wrong") {
    $arr[trim($_POST["question"])] = $_POST["answer"];
}
file_put_contents($path, json_encode($arr));
$msg = $isAdmin ? "Added Succecfully!" : "Awating Approval!";
echo $msg;
?>