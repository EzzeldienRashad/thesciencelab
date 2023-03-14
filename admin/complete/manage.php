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
        body {
            background-color: ghostwhite;
        }
    </style>
</head>
<body class="container-fluid p-2">
<header>
    <a href="../" class="float-start text-decoration-none">back</a>
    <h1 class="text-center">Control Panel - Complete game</h1>
</header>
<main>
    <a href="add.php?grade=<?php echo $_GET["grade"] ?>&unit=<?php echo $_GET["unit"] ?>" class="btn btn-success mb-3">+ add</a>
    <?php
        $questions = array_reverse(json_decode(file_get_contents("../../games/complete/" . $_GET["grade"] . "/" . $_GET["unit"] . ".json"), true));
        $questionsNum = count($questions);
        for ($i = 0; $i < $questionsNum; $i++) {
            $question = $questions[$i];
            echo "<div class='border border-2 d-flex p-2'><div class='w-100'>";
            foreach (array_keys($question) as $questionPart) {
                echo $questionPart;
                if (!empty($question[$questionPart])) echo " <span class='badge text-bg-success'>" . $question[$questionPart][0] . "</span>" .
                " <span class='badge text-bg-danger'>" . $question[$questionPart][1] . "</span>";
            }
            echo "</div>";
            if ($isAdmin) {
                echo "<button class='btn btn-danger btn-close float-end' data-question-num='" . ($questionsNum - $i - 1) . "' onclick='deleteQuestion(this)'></button>";
            }
            echo "</div>";
        }
    ?>
</main>
<footer>
    <a href="../../" class="mt-3 d-inline-block">Go to main page</a>
</footer>
</body>
</html>
