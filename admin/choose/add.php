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
} elseif (isset($_GET["grade"]) && isset($_GET["unit"]) && isset($_POST["submit"])) {
    $path = "../../" . $_GET["grade"] . "/choose/" . ($isAdmin ? "" : "approval/") . $_GET["unit"] . ".json";
    $arr = json_decode(file_get_contents($path), true);
    array_push($arr, array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"])));
    file_put_contents($path, json_encode($arr));
    $msg = $isAdmin ? "Added Succecfully!" : "Awating Approval!";
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
    <?php if ($msg != "") echo "<h3 class='" . ($isAdmin ? "text-bg-success" : "text-bg-warning") . " text-center p-3 pt-4 bg-gradient bg-opacity-75'>" . $msg . "</h3>"; ?>
</header>
<main class="container py-3">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?grade=" . $_GET["grade"] . "&unit=" . $_GET["unit"] ?>">
        <div class="row">
            <label class="form-label">
                Question: <input type="text" name="question" class="form-control" required/>
            </label>
        </div>
        <br/>
        <br/>
        <fieldset class="row">
            <legend>Answers:</legend>
            <label class="form-label col-md-6">
                First answer: <input type="text" name="first" class="form-control" required/>
            </label>
            <label class="form-label col-md-6">
                Second answer: <input type="text" name="second" class="form-control" required/>
            </label>
            <label class="form-label col-md-6">
                Third answer: <input type="text" name="third" class="form-control" required/>
            </label>
            <label class="form-label col-md-6">
                Fourth answer: <input type="text" name="fourth" class="form-control" required/>
            </label>
        </fieldset>
        <br/>
        <br/>
        <div class="row">
            <label class="form-label col-6 col-md-5 col-lg-3">
                Number of the right answer: <input type="number" name="number" max="4" min="1" class="form-control" required/>
            </label>
        </div>
        <br/>
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
