<?php
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
$path = "../grades/" . (isset($_GET["grade"]) ? $_GET["grade"] . (isset($_GET["game"]) ? "/" . $_GET["game"] . (isset($_GET["unit"]) && $_GET["unit"] !== "whole" ? "/" . $_GET["unit"] : "") : "") : "");
if (isset($_GET["grade"]) && isset($_GET["game"]) && isset($_GET["unit"])) {
    if ($_GET["unit"] == "whole") {
        $questions = [];
        foreach (array_filter(scandir($path), function($file) {return $file != "." && $file != "..";}) as $unit) {
            $questions = array_merge($questions, json_decode(file_get_contents($path . "/" . $unit), true));
        }
        $questions = json_encode($questions);
    } else {
        $questions = file_get_contents($path);
    }
    echo $questions;
} else {
    print_r(
        json_encode(
            array_values(
                array_filter(
                    scandir($path),
                    function ($file) use ($path) {
                        return $file != "." && $file != ".." && (isset($_GET["game"]) ? is_file($path . "/" . $file) : is_dir($path . "/" . $file));
                    }
                )
            )
        )
    );
}
?>