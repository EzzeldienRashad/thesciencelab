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
if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) || (isset($_POST["password"]) && password_verify($_POST["password"], '$2y$10$PWZp0FxpLcwgFDTE4SsBFOfif8K675xMibtCZ/I8VTmmNbdbNLl9q'))) {
    $_SESSION["isAdmin"] = true;
    echo "admin";
}
?>