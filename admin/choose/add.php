<?php
$msg = "";
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    header("location: ../");
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["unit"])) {
    header("location: index.php");
    exit;
} elseif (isset($_GET["grade"]) && isset($_GET["unit"]) && isset($_POST["submit"])) {
    $path = "../../" . $_GET["grade"] . "/choose/" . $_GET["unit"] . ".json";
    $arr = json_decode(file_get_contents($path));
    array_push($arr, array($_POST["question"], array($_POST["first"], $_POST["second"], $_POST["third"], $_POST["fourth"]), ((int) $_POST["number"])));
    file_put_contents($path, json_encode($arr));
    $msg = "Added Succecfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
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
    <?php if ($msg != "") echo "<h3 class='text-bg-success text-center p-3 pt-4 bg-gradient bg-opacity-75'>" . $msg . "</h3>"; ?>
</header>
<main class="container pt-3">
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
    <a href="index.php">Go Back</a>
</main>
</body>
</html>
