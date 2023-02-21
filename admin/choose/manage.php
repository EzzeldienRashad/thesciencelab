<?php
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true || !isset($_GET["grade"]) || !isset($_GET["unit"])) {
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <link href="../../assets/css/fontawesome.css" rel="stylesheet"/>
    <link href="../../assets/css/brands.css" rel="stylesheet"/>
    <link href="../../assets/css/solid.css" rel="stylesheet"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
	onerror="this.onerror=null;this.href='../../node_modules/bootstrap/dist/css/bootstrap.min.css';this.removeAttribute('integrity');this.removeAttribute('crossorigin');"
    integrity="..." 
    crossorigin="...">
    <script src="manage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		if (!window.bootstrap) {
			var newScript = document.createElement("script");
			newScript.setAttribute("src", "../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js");
			document.getElementsByTagName("head")[0].appendChild(newScript);
		}
	</script>
</head>
<body class="container-fluid p-2">
<header>
    <a href="../" class="float-start text-decoration-none">back</a>
    <h1 class="text-center">Control Panel - Choose game</h1>
</header>
<main>
    <a href="add.php?grade=<?php echo $_GET["grade"] ?>&unit=<?php echo $_GET["unit"] ?>" class="btn btn-success mb-3">+ add</a>
    <?php
        $questions = array_reverse(json_decode(file_get_contents("../../" . $_GET["grade"] . "/choose/" . $_GET["unit"] . ".json")));
        $questionsNum = count($questions);
        for ($i = 0; $i < $questionsNum; $i++) {
            $question = $questions[$i];
            echo "
                <div class='card'>
                    <div class='card-header'>
                        $question[0]
                        <button class='btn btn-danger btn-close float-end' data-question-num='" . $questionsNum - $i - 1 . "' onclick='deleteQuestion(this)'></button>
                    </div>
                    <div class='card-body'>
                        <div class='row'>";
                        for ($c = 0; $c < count($question[1]); $c++) {
                            $answer = $question[1][$c];
                            echo "<div class='col-12 col-sm-6 col-lg-3 border";
                            if ($answer == $question[1][$question[2] - 1]) {
                                echo " text-bg-success";
                            }
                            echo "'><div class='h-100'>" . $answer . "</div></div>";
                        }
                        echo "</div>
                    </div>
                </div>
            ";
        }
    ?>
</main>
<footer>
    <a href="../../" class="mt-3 d-inline-block">Go to main page</a>
</footer>
</body>
</html>
