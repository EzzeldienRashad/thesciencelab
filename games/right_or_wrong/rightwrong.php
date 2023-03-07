<?php
if (!isset($_GET["filename"], $_GET["grade"])) exit;
$array = json_decode(file_get_contents($_GET["grade"] . "/" . $_GET["filename"] . ".json"), true);
if (isset($_GET["number"])) {
    $max = $_GET["number"] < count($array) ? $_GET["number"] : count($array);
    $keys = array_keys($array); 
    shuffle($keys); 
    $random = array(); 
    for ($i = 0; $i < $max; $i++) { 
        $random[$keys[$i]] = $array[$keys[$i]]; 
    }
    echo json_encode($random);
} else {
    echo count($array);
}
?>