<?php
session_start();
$isAdmin = false;
$isMember = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
}
if (!$isAdmin || !isset($_GET["grade"]) || !isset($_GET["unit"]) || !isset($_GET["question"])) {
    exit;
}
$file = "../../games/right_or_wrong/" . $_GET["grade"] . (isset($_GET["approval"]) && $_GET["approval"] == "true" ? "/approval/" : "/") . $_GET["unit"] . ".json";
$arr = json_decode(file_get_contents($file), true);
unset($arr[$_GET["question"]]);
file_put_contents($file, json_encode($arr));
?>