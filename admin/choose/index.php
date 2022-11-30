<?php
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    header("location: ../");
    exit;
}
if (isset($_GET["grade"])) {
    $dir = opendir("../../" . $_GET["grade"] . "/choose");
    $arr = array();
    while (($file = readdir($dir)) !== false) {
        $fileInfo = pathinfo($file);
        if (isset($fileInfo["extension"]) && $fileInfo["extension"] == "json") {
            array_push($arr, $fileInfo["filename"]);
        }
    }
    echo json_encode($arr);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <link rel="stylesheet" href="index.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <script src="index.js" defer></script>
</head>
<body>
<main>
    <h1>Control Panel</h1>
    <section>
        <div class="buttons">
            <?php
            function startsWith( $haystack, $needle ) {
                $length = strlen( $needle );
                return substr( $haystack, 0, $length ) === $needle;
            }
            $files = scandir("../../");
            foreach ($files as $file) {
                if (startsWith($file, "grade")) {
                     echo "<button class='button' data-grade='" . $file . "'>$file</button>";
                }
            }
            ?>
        </div>
    </section>
</main>
<footer>
    <a href="../">Go Back</a>
</footer>
</body>
</html>
