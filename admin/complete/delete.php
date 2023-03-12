<?php
session_start();
$isAdmin = false;
$isMember = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
}
if (!$isAdmin || !isset($_GET["grade"]) || !isset($_GET["unit"]) || !isset($_GET["questionnum"])) {
    exit;
}
$file = "../../games/complete/" . $_GET["grade"] . (isset($_GET["approval"]) && $_GET["approval"] == "true" ? "/approval/" : "/") . $_GET["unit"] . ".json";
$arr = json_decode(file_get_contents($file));
if ((int) $_GET["questionnum"] < count($arr)) {
    array_splice($arr, $_GET["questionnum"], 1);
    file_put_contents($file, json_encode($arr));
}
?>