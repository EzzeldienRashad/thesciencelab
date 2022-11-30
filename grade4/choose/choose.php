<?php
if (!$_GET["filename"]) exit;
$array = json_decode(file_get_contents($_GET["filename"] . ".json"));
if (isset($_GET["number"])) {
    $newArray = array();
    $max = $_GET["number"] < count($array) ? $_GET["number"] : count($array);
    for ($i = 0; $i < $max; $i++) {
        $randomNumber = floor(rand(0, count($array) - 1));
        array_push($newArray, $array[$randomNumber]);
        array_splice($array, $randomNumber, 1);
    }
    echo json_encode($newArray);
} else {
    echo count($array);
}
?>