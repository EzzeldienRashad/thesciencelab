<?php
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    echo "You don't have permission to do this operation.";
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
    <script src="approve.js" defer></script>
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
<body>
<?php
function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}
$questions = array();
$grades = array_values(array_filter(scandir("../../games/choose/"), function ($file) {
    return startsWith($file, "grade");
}));
foreach ($grades as $grade) {
    $gradeAppended = false;
    $lessons = array_values(array_filter(scandir("../../games/choose/" . $grade . "/approval"), function ($file) {
        return isset(pathinfo($file)["extension"]) && pathinfo($file)["extension"] == "json";
    }));
    foreach ($lessons as $lesson) {
        $lessonQuestions = json_decode(file_get_contents("../../games/choose/" . $grade . "/approval/" . $lesson));
        if (!empty($lessonQuestions)) {
            if (!$gradeAppended) {
                $gradeAppended = true;
                $questions[$grade] = array();
            }
            $questions[$grade][$lesson] = $lessonQuestions;
        }
    }
}
if (empty($questions)) {
?>
    <header class="p-3 text-bg-info">
        <h1 class="text-center">No questions need approval.</h1>
    </header>
<?php
} else {
?>
    <header class="p-3 text-bg-warning">
        <h1>Questions needing approval:</h1>
    </header>
    <main class="buttons">
        <?php
        foreach ($questions as $grade => $lessons) {
            echo "<h2 class='display-3 text-bg-dark'>$grade</h2>";
            foreach ($lessons as $title => $content) {
                $title = str_replace(".json", "", $title);
                $questionsNum = count($content);
                echo "<h3 class='m-3 mb-5'>$title:</h3>";
                for ($i = 0; $i < $questionsNum; $i++) {
                    $card = $content[$i];
                    $jsonCard = json_encode(array(
                        "question" => $card[0],
                        "first" => $card[1][0],
                        "second" => $card[1][1],
                        "third" => $card[1][2],
                        "fourth" => $card[1][3],
                        "number" => $card[2],
                        "submit" => "submit"
                    ));
                    echo "
                        <div class='card mb-2 shadow-sm border border-3'>
                            <div class='card-header'>
                                $card[0]
                                <button class='btn btn-danger btn-close float-end' 
                                data-question-num='$i'
                                data-question-grade='$grade'
                                data-question-lesson='$title'
                                onclick='deleteQuestion(this)'></button>
                            </div>
                            <div class='card-body'>
                                <div class='row'>";
                                for ($c = 0; $c < count($card[1]); $c++) {
                                    $answer = $card[1][$c];
                                    echo "<div class='col-12 col-sm-6 col-lg-3 border";
                                    if ($answer == $card[1][$card[2] - 1]) {
                                        echo " text-bg-success";
                                    }
                                    echo "'><div class='h-100'>" . $answer . "</div></div>";
                                }
                                echo "</div>
                            </div>
                            <div class='card-footer p-0'>
                                <button data-question-card='$jsonCard'
                                 data-question-grade='$grade'
                                 data-question-lesson='$title'
                                 data-question-num='$i'
                                 onclick='add(this)'
                                 class='btn btn-success p-3 text-center w-100 bg-gradient'/>Approve</button>
                            </div>
                        </div>
                        <hr>
                    ";
                }
            }
        }
        ?>
    </main>
<?php
}
?>
<footer class="p-3">
    <ul class="pagination">
        <li class="page-item">
            <a href="index.php" class="page-link">go back</a>
        </li>
        <li class="page-item">
            <a href="../../" class="page-link">main page</a>
        </li>
    </ul>
</footer>
</body>
</html>