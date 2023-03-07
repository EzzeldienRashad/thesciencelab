<?php
session_start();
$isAdmin = false;
$isMember = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
} elseif (isset($_SESSION["isMember"]) && $_SESSION["isMember"] == true) {
    $isMember = true;
}
if ((!$isAdmin && !$isMember) || !isset($_GET["grade"]) || !isset($_GET["unit"])) {
    header("location: index.php");
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
    <script src="manage.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		if (!window.bootstrap) {
			var newScript = document.createElement("script");
			newScript.setAttribute("src", "../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js");
			document.getElementsByTagName("head")[0].appendChild(newScript);
		}
	</script>
    <style>
        html {
            scroll-behavior: auto !important;
        }
    </style>
</head>
<body class="container-fluid p-2">
<header>
    <a href="../" class="float-start text-decoration-none">back</a>
    <h1 class="text-center">Control Panel - right or wrong game</h1>
</header>
<main>
    <a href="add.php?grade=<?php echo $_GET["grade"] ?>&unit=<?php echo $_GET["unit"] ?>" class="btn btn-success mb-3">+ add</a>
    <?php
        $questions = json_decode(file_get_contents("../../games/right_or_wrong/" . $_GET["grade"] . "/" . $_GET["unit"] . ".json"), true);
        foreach ($questions as $question => $right) {
            echo "
                <div class='p-3 m-3 rounded " . ($right ? "text-bg-success" : "text-bg-danger") . "'>$question" . 
                ($isAdmin ? "<button class='btn btn-danger btn-close float-end' data-question='" . $question . "' onclick='deleteQuestion(this)'></button>" : "") .
                "</div>";
        }
    ?>
</main>
<footer>
    <a href="../../" class="mt-3 d-inline-block">Go to main page</a>
</footer>
</body>
</html>
