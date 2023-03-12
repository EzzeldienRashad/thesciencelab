<?php
    session_start();
    if (isset($_POST["password"]) && $_POST["password"] == "1t2h3e4s5c@G") {
        $_SESSION["isAdmin"] = true;
    } elseif (isset($_POST["password"]) && $_POST["password"] == "1r2e3s4t5r!F") {
        $_SESSION["isMember"] = true;
    }
    //logout
    if (isset($_GET["logout"])) {
        unset($_SESSION["isAdmin"]);
        unset($_SESSION["isMember"]);
        header("location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
	onerror="this.onerror=null;this.href='../node_modules/bootstrap/dist/css/bootstrap.min.css';this.removeAttribute('integrity');this.removeAttribute('crossorigin');"
    integrity="..." 
    crossorigin="...">
	<script>
		if (!window.bootstrap) {
			var newScript = document.createElement("script");
			newScript.setAttribute("src", "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js");
			document.getElementsByTagName("head")[0].appendChild(newScript);
		}
	</script>
    <style>
        img {
            height: 23vh;
        }
    </style>
</head>
<body class="container p-3">

<?php
$isAdmin = false;
$isMember = false;
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
    $isAdmin = true;
} elseif (isset($_SESSION["isMember"]) && $_SESSION["isMember"] == true) {
    $isMember = true;
}
if (!$isAdmin && !$isMember) {
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <label class="form-label">
        Password: <input type="password" name="password" class="form-control"/>
    </label>
    <input type="submit" value="submit" class="btn btn-info"/>
</form>

<?php
} else {
?>
<header>
    <h1 class="text-center pb-3">Choose a Game</h1>
</header>
<main>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4">
            <a href="choose" class="text-decoration-none text-dark">
                <figure class="text-center">
                    <img src="../images/choose.webp" alt="choose" class="w-100 h-auto border border-3"/>
                    <figcaption>choose</figcaption>
                </figure>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <a href="right_or_wrong" class="text-decoration-none text-dark">
                <figure class="text-center">
                    <img src="../images/right_or_wrong.webp" alt="right or wrong" class="w-100 h-auto border border-3"/>
                    <figcaption>right or wrong</figcaption>
                </figure>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <a href="complete" class="text-decoration-none text-dark">
                <figure class="text-center">
                    <img src="../images/complete.webp" alt="complete" class="w-100 h-auto border border-3"/>
                    <figcaption>complete</figcaption>
                </figure>
            </a>
        </div>
    </div>
</main>
<?php
}
?>
<footer class="pt-3">
    <ul class="pagination">
        <li class="page-item">
            <a href="../" class="page-link">main page</a>
        </li>
        <?php if ($isAdmin || $isMember) { ?>
            <li class="page-item">
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>?logout=true" class="page-link">logout</a>
            </li>
        <?php } ?>
    </ul>
</footer>
</body>
</html>