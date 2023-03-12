<?php
$msg = "";
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
if (isset($_GET["sender"]) && $_GET["sender"] == "js") {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
if (!isset($_GET["grade"]) || !isset($_GET["unit"])) {
    header("location: index.php");
    exit;
} elseif (isset($_POST["submit"])) {
    $path = "../../games/complete/" . $_GET["grade"] . ($isAdmin ? "/" : "/approval/") . $_GET["unit"] . ".json";
    $arr = json_decode(file_get_contents($path), true);
    array_push($arr, array());
    $question = preg_split('/\\.{3,}/', $_POST["question"], -1);
    $answersNum = preg_match_all('/\\.{3,}/', $_POST["question"]);
    $answers = preg_split("/,/", $_POST["answers"], -1, PREG_SPLIT_NO_EMPTY);
    if ($question[count($question) - 1] == "") {
        unset($question[count($question) - 1]);
    }
    if ($question[0] == "") {
        $question[0] = " ";
    }
    if ($answersNum * 2 != count($answers)) {
        $msg = "Wrong number of answers!";
        $_SESSION["question"] = $_POST["question"];
        $_SESSION["answers"] = $_POST["answers"];
    } else {
        if ($isAdmin) {
            for ($i = 0; $i < $answersNum; $i++) {
                $arr[count($arr) - 1][$question[$i]] = array($answers[$i * 2], $answers[$i * 2 + 1]);
            }
            if ($answersNum != count($question)) {
                $arr[count($arr) - 1][$question[count($question) - 1]] = array();
            }
        } else {
            $arr[count($arr) - 1] = array($_POST["question"], $_POST["answers"]);
        }
        file_put_contents($path, json_encode($arr));
        $msg = $isAdmin ? "Added Succecfully!" : "Awating Approval!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <link rel="icon" href="../../images/logo.webp"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
	onerror="this.onerror=null;this.href='../node_modules/bootstrap/dist/css/bootstrap.min.css';this.removeAttribute('integrity');this.removeAttribute('crossorigin');"
    integrity="..." 
    crossorigin="...">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		if (!window.bootstrap) {
			var newScript = document.createElement("script");
			newScript.setAttribute("src", "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js");
			document.getElementsByTagName("head")[0].appendChild(newScript);
		}
	</script>
</head>
<body>
<header>
    <?php
    if ($isAdmin) $alert = "success";
    else $alert = "warning";
    if ($msg != "") echo "<div class='alert alert-$alert alert-dismissible fade show text-center h2 msg'><button type='button' class='btn-close' data-bs-dismiss='alert'></button>$msg</div>
    <script>setTimeout(() => document.querySelector('.msg').remove(), 4000);</script>"; 
    ?>
</header>
<main class="container py-3">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?grade=" . $_GET["grade"] . "&unit=" . $_GET["unit"] ?>">
        <label class="form-label w-100">
            Question: <input type="text" name="question" class="form-control" value="<?php if (isset($_SESSION["question"])) {echo $_SESSION["question"]; unset($_SESSION["question"]);} ?>" required/>
        </label>
        <br/>
        <label class="form-label col-md-6 w-100">
            answers: <input type="text" name="answers" class="form-control" value="<?php if (isset($_SESSION["answers"])) {echo $_SESSION["answers"]; unset($_SESSION["answers"]);} ?>" required/>
        </label>
        <br/>
        <div class="row">
        </div>
        <br/>
        <input type="submit" name="submit" value="submit" class="btn btn-info"/>
    </form>
    <br/>
    <br/>
</main>
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
