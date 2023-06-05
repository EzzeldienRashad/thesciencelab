<?php
ini_set('session.cookie_secure', "1"); 
ini_set('session.cookie_httponly', "1"); 
ini_set('session.cookie_samesite','None');
session_start();
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");
$msg = "";
if (isset($_POST["password"]) && password_verify($_POST["password"], '$2y$10$PWZp0FxpLcwgFDTE4SsBFOfif8K675xMibtCZ/I8VTmmNbdbNLl9q')) {
    $_SESSION["isAdmin"] = true;
    $msg = "admin";
} elseif (isset($_POST["password"]) && password_verify($_POST["password"], '$2y$10$TtPHpQGyP3FgbDOiDWj6GOvcY6tNzePMO8iU1TfscfJk/tBjb0pKK')) {
    $_SESSION["isContributor"] = true;
    $msg = "contributor";
} elseif (!isset($_POST["password"])) {
    if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
        $msg = "admin";
    } elseif (isset($_SESSION["isContributor"]) && $_SESSION["isContributor"] == true) {
        $msg = "contributor";
    }
}
echo $msg;
?>