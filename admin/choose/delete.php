<?php
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true || !isset($_GET["grade"]) || !isset($_GET["unit"]) || !isset($_GET["questionnum"])) {
    header("location: index.php");
    exit;
}
$file = "../../" . $_GET["grade"] . "/choose/" . $_GET["unit"] . ".json";
$arr = json_decode(file_get_contents($file));
if ((int) $_GET["questionnum"] < count($arr)) {
    array_splice($arr, $_GET["questionnum"], 1);
    file_put_contents($file, json_encode($arr));
}

?>