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
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true || !isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || !isset($_GET["questionnum"])) {
    exit;
}
$file = "../grades/" . $_GET["grade"] . "/" . $_GET["game"] . (isset($_GET["approval"]) && $_GET["approval"] == "true" ? "/approval/" : "/") . $_GET["unit"];
$arr = json_decode(file_get_contents($file));
if ($_GET["game"] == "choose") {
    if ((int) $_GET["questionnum"] < count($arr)) {
        array_splice($arr, $_GET["questionnum"], 1);
        file_put_contents($file, json_encode($arr));
    }
}
?>