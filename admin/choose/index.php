<?php
session_start();
$isAdmin = false;
$isMember = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
} elseif (isset($_SESSION["isMember"]) && $_SESSION["isMember"] == true) {
    $isMember = true;
}
if (!$isAdmin && !$isMember) {
    header("location: ../");
    exit;
}
if (isset($_GET["grade"])) {
    $arr = array();
    foreach (scandir("../../games/choose/" . $_GET["grade"]) as $file) {
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
    <link rel="icon" href="../../images/logo.webp"/>
    <link href="../../assets/css/fontawesome.css" rel="stylesheet"/>
    <link href="../../assets/css/brands.css" rel="stylesheet"/>
    <link href="../../assets/css/solid.css" rel="stylesheet"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
	onerror="this.onerror=null;this.href='../../node_modules/bootstrap/dist/css/bootstrap.min.css';this.removeAttribute('integrity');this.removeAttribute('crossorigin');"
    integrity="..." 
    crossorigin="...">
    <script src="index.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		if (!window.bootstrap) {
			var newScript = document.createElement("script");
			newScript.setAttribute("src", "../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js");
			document.getElementsByTagName("head")[0].appendChild(newScript);
		}
	</script>
</head>
<body class="container">
<header>
    <h1 class="text-center"><i class="fa-solid fa-rotate-left float-start pt-1" role="button"></i>Control Panel</h1>   
</header>
<main>

    <?php 
    if ($isAdmin) {
    ?>
        <a href="approve.php" class="btn btn-warning mb-3">... Approve!</a>
    <?php
    }
    ?>
    <section>
        <div class="buttons row g-2">
            <?php
            function startsWith( $haystack, $needle ) {
                $length = strlen( $needle );
                return substr( $haystack, 0, $length ) === $needle;
            }
            $files = array_values(array_filter(scandir("../../games/choose"), function ($file) {
                return startsWith($file, "grade");
            }));
            for ($i = 0; $i < count($files); $i++) {
                $file = $files[$i];
                echo "
                    <div class='col-md-6'>
                        <button class='btn btn-primary p-3 w-100 h-100' data-grade='" . $file . "'>$file</button>
                    </div>";
            }
            ?>
        </div>
    </section>
</main>
<footer class="pt-3">
    <ul class="pagination">
        <li class="page-item">
            <a href="../" class="page-link">go back</a>
        </li>
        <li class="page-item">
            <a href="../../" class="page-link">main page</a>
        </li>
    </ul>
</footer>
</body>
</html>