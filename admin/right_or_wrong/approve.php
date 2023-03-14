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
        body {
            background-color: ghostwhite;
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
$grades = array_values(array_filter(scandir("../../games/right_or_wrong/"), function ($file) {
    return startsWith($file, "grade");
}));
foreach ($grades as $grade) {
    $gradeAppended = false;
    $lessons = array_values(array_filter(scandir("../../games/right_or_wrong/" . $grade . "/approval"), function ($file) {
        return isset(pathinfo($file)["extension"]) && pathinfo($file)["extension"] == "json";
    }));
    foreach ($lessons as $lesson) {
        $lessonQuestions = json_decode(file_get_contents("../../games/right_or_wrong/" . $grade . "/approval/" . $lesson), true);
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
    <main>
        <?php
        foreach ($questions as $grade => $lessons) {
            echo "<h2 class='display-3 text-bg-dark'>$grade</h2>";
            foreach ($lessons as $title => $content) {
                $title = str_replace(".json", "", $title);
                echo "<h3 class='m-3 mb-5'>$title:</h3>";
                foreach ($content as $question => $right) {
                    echo "
                    <div class='d-flex'>
                        <button data-question='$question'
                            data-question-right='$right'
                            data-question-grade='$grade'
                            data-question-lesson='$title'
                            onclick='add(this)'
                            class='btn " . ($right ? "btn-success" : "btn-danger") . " p-3 my-3 text-center bg-gradient'/><i class='fa-solid fa-check'></i></button>
                            <div class='p-3 m-3 rounded w-100 " . ($right ? "text-bg-success" : "text-bg-danger") . "'>$question
                            <button class='btn btn-danger btn-close float-end' 
                            data-question='$question' 
                            data-question-grade='$grade'
                            data-question-lesson='$title'
                            onclick='deleteQuestion(this)'></button>
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