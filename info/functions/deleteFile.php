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
if (($_SESSION["subject"] == "admin" || $_GET["username"] == $_SESSION["username"]) && isset($_GET["file"]) && isset($_GET["uploader"])) {
    unlink("../preparationFiles/" . $_GET["uploader"] . "/" . $_GET["file"]);
}
?>