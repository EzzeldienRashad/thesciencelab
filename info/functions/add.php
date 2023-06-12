<?php
ini_set('session.cookie_samesite','None');
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");

session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || empty($_POST)) {
    exit;
}
$path = "../grades/" . $_GET["grade"] . "/" . $_GET["game"] . "/" . $_GET["unit"];
$arr = json_decode(file_get_contents($path), true);
if ($_GET["game"] == "choose") {
    array_push($arr, array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"])));
} elseif ($_GET["game"] == "right_or_wrong") {
    $arr[trim($_POST["question"])] = $_POST["answer"];
} elseif ($_GET["game"] == "complete") {
    array_push($arr, array());
    $question = preg_split('/\\.{3,}/', $_POST["question"], -1);
    $answersNum = preg_match_all('/\\.{3,}/', $_POST["question"]);
    $answers = preg_split("/,/", $_POST["answers"], -1, PREG_SPLIT_NO_EMPTY);
    if ($question[count($question) - 1] == "") {
        unset($question[count($question) - 1]);
    }
    if ($question[0] == "") {
        $question[0] = " ";
    }
    if ($answersNum * 2 != count($answers)) {
        echo "answernumerror";
        exit;
    } else {
        for ($i = 0; $i < $answersNum; $i++) {
            $arr[count($arr) - 1][$question[$i]] = array($answers[$i * 2], $answers[$i * 2 + 1]);
        }
        if ($answersNum != count($question)) {
            $arr[count($arr) - 1][$question[count($question) - 1]] = array();
        }
    }
}
file_put_contents($path, json_encode($arr));
echo "successful";
?>