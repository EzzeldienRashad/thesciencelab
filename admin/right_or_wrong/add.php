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
    $path = "../../games/right_or_wrong/" . $_GET["grade"] . ($isAdmin ? "/" : "/approval/") . $_GET["unit"] . ".json";
    $arr = json_decode(file_get_contents($path), true);
    $arr[trim($_POST["question"])] = $_POST["answer"];
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
    <?php
    if ($isAdmin) $alert = "success";
    else $alert = "warning";
    if ($msg != "") echo "<div class='alert alert-$alert alert-dismissible fade show text-center h2 msg'><button type='button' class='btn-close' data-bs-dismiss='alert'></button>$msg</div>
    <script>setTimeout(() => document.querySelector('.msg').remove(), 4000);</script>"; 
    ?>
</header>
<main class="container py-3">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?grade=" . $_GET["grade"] . "&unit=" . $_GET["unit"] ?>">
        <div class="row">
            <label class="form-label">
                Question: <input type="text" name="question" class="form-control" required/>
            </label>
        </div>
        <div class="row px-3">
            <div class="col-6 form-check"><input id="right" type="radio" class="form-check-input" name="answer" value="1"/><label for="right" class="form-check-label">right</label></div>
            <div class="col-6 form-check"><input id="wrong" type="radio" class="form-check-input" name="answer" value="0"/><label for="wrong" class="form-check-label">wrong</label></div>
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
