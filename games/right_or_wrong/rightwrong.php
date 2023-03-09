<?php
if (!isset($_GET["filename"], $_GET["grade"])) exit;
if ($_GET["filename"] == "all") {
    $allQuestions = array();
    foreach (scandir($_GET["grade"]) as $file) {
        $fileInfo = pathinfo($file);
        if (isset($fileInfo["extension"]) && $fileInfo["extension"] == "json") {
            foreach (json_decode(file_get_contents($_GET["grade"] . "/" . $file), true) as $question => $true) {
                $allQuestions[$question] = $true;
            };
        }
    }
    if (isset($_GET["number"])) {
        $newQuestions = array();
        $max = $_GET["number"] < count($allQuestions) ? $_GET["number"] : count($allQuestions);
        $keys = array_keys($allQuestions);
        shuffle($keys);
        for ($i = 0; $i < $max; $i++) {
            $newQuestions[$keys[$i]] = $allQuestions[$keys[$i]];
        }
        echo json_encode($newQuestions);
    } else {
        echo count($allQuestions);
    }
    exit;
}
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