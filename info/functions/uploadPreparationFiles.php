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
if (!in_array($_SESSION["subject"], array("science", "physics", "chemistry", "biology", "admin")) || 
    !isset($_GET["memberName"]) || !isset($_FILES["preparationFile"])) {
    exit;
}
if (isset($_FILES["preparationFile"]) && $_FILES["preparationFile"]["error"] == 0) {
    if (!in_array(exif_imagetype($_FILES["preparationFile"]["tmp_name"]), array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF)) && 
    !in_array(pathinfo($_FILES["preparationFile"]["name"], PATHINFO_EXTENSION), array("doc", "docx"))) {
        echo "typeerr";
        exit;
    }
    if ($_FILES["preparationFile"]["size"] > 10 * 1024 * 1024) {
        echo "big";
        exit;
    }
    $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', time(). "_" . pathinfo($_FILES["preparationFile"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["preparationFile"]["name"], PATHINFO_EXTENSION);
    if (!is_dir("../preparationFiles/" . $_GET["memberName"])) mkdir("../preparationFiles/" . $_GET["memberName"], 0777);
    if (move_uploaded_file($_FILES["preparationFile"]["tmp_name"], "../preparationFiles/" . $_GET["memberName"] . "/" . $fileName)) echo "successful";
}
?>