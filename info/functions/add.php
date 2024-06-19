<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
if (!isset($_SESSION["subject"]) || !in_array($_SESSION["subject"], array("biology", "physics", "chemistry", "admin", "none"))) {
    echo "logout";
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["game"]) || !isset($_GET["unit"]) || empty($_POST)) {
    exit;
}
$isSecondary = str_contains($_GET["grade"], "secondary");
if ($isSecondary && $_GET["game"] != $_SESSION["subject"] && $_SESSION["subject"] != "admin") exit;
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
switch ($_GET["game"]) {
    case "choose":
    case "biology":
    case "physics":
    case "chemistry":
        if (!isset($_POST["question"]) || !isset($_POST["first"]) || !isset($_POST["second"]) || !isset($_POST["third"]) || !isset($_POST["fourth"]) || !isset($_POST["number"])) {
            echo "infoerr";
            exit;
        }
        $addStmt = $pdo->prepare("insert into ChooseQuestions (question, choiceA, choiceB, choiceC, choiceD, answer, image, grade, subject, unit, uploader) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $imgName = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            if ($_FILES["image"]["size"] > 1 * 1024 * 1024) {
                echo "big";
                exit;
            }
            if (!in_array(exif_imagetype($_FILES["image"]["tmp_name"]), array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF))) {
                echo "typeerr";
                exit;
            }
            $imgName = preg_replace('/[^A-Za-z0-9_\-]/', '_', time(). "_" . pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["image"]["tmp_name"], "../images/" . $imgName);
        }
        $addStmt->execute([$_POST["question"], $_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"], ((int) $_POST["number"]), $imgName, $_GET["grade"], ($isSecondary ? $_GET["game"] : "science"), $_GET["unit"], $_SESSION["username"]]);
        break;
    case "right-or-wrong":
        if (!isset($_POST["question"]) || !isset($_POST["answer"])) {
            echo "infoerr";
            exit;
        }
        $addStmt = $pdo->prepare("insert into RightOrWrongQuestions (question, answer, image, grade, unit, uploader) values (?, ?, ?, ?, ?, ?)");
        $imgName = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            if ($_FILES["image"]["size"] > 1 * 1024 * 1024) {
                echo "big";
                exit;
            }
            if (!in_array(exif_imagetype($_FILES["image"]["tmp_name"]), array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF))) {
                echo "typeerr";
                exit;
            }
            $imgName = preg_replace('/[^A-Za-z0-9_\-]/', '_', time(). "_" . pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["image"]["tmp_name"], "../images/" . $imgName);
        }
        $addStmt->execute([trim($_POST["question"]), $_POST["answer"], $imgName, $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
        break;
    case "complete":
        if (!isset($_POST["question"]) || !isset($_POST["right"]) || !isset($_POST["wrong"])) {
            echo "infoerr";
            exit;
        }
        $questionParts = preg_split('/\\.{3,}/', $_POST["question"]);
        if (count($questionParts) != 2 || (!$questionParts[0] && !$questionParts[1])) {
            echo "spacenumerr";
            exit;
        } else {
            $addStmt = $pdo->prepare("insert into CompleteQuestions (part1, part2, rightAnswer, wrongAnswer, grade, unit, uploader) values (?, ?, ?, ?, ?, ?, ?)");
            $addStmt->execute([$questionParts[0], $questionParts[1], $_POST["right"], $_POST["wrong"], $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
        }
        break;
    case "match":
        if (!isset($_POST["questions"]) || !isset($_POST["answers"])) {
            echo "infoerr";
            exit;
        } elseif (count($_POST["questions"]) != count($_POST["answers"])) {
            echo "answernumerr";
            exit;
        }
        $colA = array();
        $colB = array();
        for ($i = 0; $i < count($_POST["questions"]); $i++) {
            array_push($colA, $_POST["questions"][$i]);
            array_push($colB, $_POST["answers"][$i]);
        }
        $addStmt = $pdo->prepare("insert into MatchQuestions (colA, colB, grade, unit, uploader) values (?, ?, ?, ?, ?)");
        $addStmt->execute([json_encode($colA), json_encode($colB), $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
        break;
    case "give-reason":
    case "what-happens-when":
        if (!isset($_POST["question"])) {
            echo "infoerr";
            exit;
        }
        $addStmt = $pdo->prepare("insert into EssayQuestions (question, grade, unit, uploader, type) values (?, ?, ?, ?, ?)");
        $addStmt->execute([trim($_POST["question"]), $_GET["grade"], $_GET["unit"], $_SESSION["username"], $_GET["game"]]);
        break;
    case "scientific-term":
        if (!isset($_POST["question"]) || !isset($_POST["answer"])) {
            echo "infoerr";
            exit;
        }
        $addStmt = $pdo->prepare("insert into ScientificTermQuestions (question, answer, grade, unit, uploader) values (?, ?, ?, ?, ?)");
        $addStmt->execute([trim($_POST["question"]), trim($_POST["answer"]), $_GET["grade"], $_GET["unit"], $_SESSION["username"]]);
        break;
}
echo "successful";
?>
