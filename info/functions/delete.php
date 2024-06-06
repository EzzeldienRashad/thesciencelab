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
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || (!isset($_GET["questionnum"]) && !isset($_GET["question"]))) {
    exit;
}
$file = "../grades/" . $_GET["grade"] . "/" . $_GET["game"] . "/" . $_GET["unit"];
$arr = json_decode(file_get_contents($file), true);
if (isset($_GET["questionnum"])) {
    if ((int) $_GET["questionnum"] < count($arr)) {
        array_splice($arr, $_GET["questionnum"], 1);
    }
} elseif (isset($_GET["question"])) {
    unset($arr[$_GET["question"]]);
}
file_put_contents($file, json_encode($arr));
?>
