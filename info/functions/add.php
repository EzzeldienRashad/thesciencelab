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
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || empty($_POST)) {
    exit;
}
$path = "../grades/" . $_GET["grade"] . "/" . $_GET["game"] . "/" . $_GET["unit"];
$arr = json_decode(file_get_contents($path), true);
if (in_array($_GET["game"], array("choose", "biology", "physics", "chemistry"))) {
    if (!isset($_POST["question"]) || !isset($_POST["first"]) || !isset($_POST["second"]) || !isset($_POST["third"]) || !isset($_POST["fourth"]) || !isset($_POST["number"])) {
        echo "infoerr";
        exit;
    }
    array_push($arr, array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"])));
} elseif ($_GET["game"] == "right-or-wrong") {
    if (!isset($_POST["question"]) || !isset($_POST["answer"])) {
        echo "infoerr";
        exit;
    }
    $arr[trim($_POST["question"])] = $_POST["answer"];
} elseif ($_GET["game"] == "complete") {
    if (!isset($_POST["question"]) || !isset($_POST["right"]) || !isset($_POST["wrong"])) {
        echo "infoerr";
        exit;
    }
    $questionParts = preg_split('/\\.{3,}/', $_POST["question"]);
    if (count($questionParts) != 2) {
        echo "spacenumerr";
        exit;
    } else {
        array_push($arr, array($questionParts[0], array($_POST["right"], $_POST["wrong"]), $questionParts[1]));
    }
} elseif ($_GET["game"] == "match") {
    if (!isset($_POST["questions"]) || !isset($_POST["answers"])) {
        echo "infoerr";
        exit;
    } elseif (count($_POST["questions"]) != count($_POST["answers"])) {
        echo "answernumerr";
        exit;
    }
    array_push($arr, array());
    for ($i = 0; $i < count($_POST["questions"]); $i++) {
        $arr[count($arr) - 1][$_POST["questions"][$i]] = $_POST["answers"][$i];
    }
}
file_put_contents($path, json_encode($arr));
print("successful");
?>
