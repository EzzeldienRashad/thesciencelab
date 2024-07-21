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
require "password.php";
$dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
$pdo = new PDO($dsn, "if0_36665133", $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
if ($_SESSION["subject"] == "admin" && !isset($_GET["username"])) {
    echo json_encode(
        array_map(function ($name) use ($pdo) {
            $getNameStmt = $pdo->prepare("SELECT name FROM if0_36665133_TheScienceLab.Members where username = ?");
            $getNameStmt->execute([$name]);
            return [$name, $getNameStmt->fetchColumn()];        
        }, array_values(
            array_filter(
                scandir("../preparationFiles/"),
                function ($file) {
                    return $file != "." && $file != ".." && is_dir("../preparationFiles/" . $file);
                }
            )
        ))
    );
} else if ($_SESSION["subject"] == "admin" || $_GET["username"] == $_SESSION["username"]) {
    echo json_encode(
        array_values(
            array_filter(
                scandir("../preparationFiles/" . $_GET["username"]),
                function ($file) {
                    return $file != "." && $file != ".." && is_file("../preparationFiles/" . $_GET["username"] . "/" . $file);
                }
            )
        )
    );
}
?>