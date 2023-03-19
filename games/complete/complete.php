<?php
if (!isset($_GET["filename"], $_GET["grade"])) exit;
if ($_GET["filename"] == "all") {
    $allQuestions = array();
    foreach (scandir($_GET["grade"]) as $file) {
        $fileInfo = pathinfo($file);
        if (isset($fileInfo["extension"]) && $fileInfo["extension"] == "json") {
            array_push($allQuestions, ...json_decode(file_get_contents($_GET["grade"] . "/" . $file), true));
        }
    }
    if (isset($_GET["number"])) {
        $newQuestions = array();
        $max = $_GET["number"] < count($allQuestions) ? $_GET["number"] : count($allQuestions);
        for ($i = 0; $i < $max; $i++) {
            $randomNumber = floor(rand(0, count($allQuestions) - 1));
            array_push($newQuestions, $allQuestions[$randomNumber]);
            array_splice($allQuestions, $randomNumber, 1);
        }
        echo json_encode($newQuestions);
    } else {
        echo count($allQuestions);
    }
    exit;
}
$array = json_decode(file_get_contents($_GET["grade"] . "/" . $_GET["filename"] . ".json"), true);
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