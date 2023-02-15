<?php
    session_start();
    if (isset($_POST["password"]) && $_POST["password"] == "1t2h3e4s5c@G") {
        $_SESSION["isAdmin"] = true;
        header("location: choose");
    }
    if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
        header("location: choose");
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
</head>
<body class="container">

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <label class="form-label">
        Password: <input type="password" name="password" class="form-control"/>
    </label>
    <input type="submit" value="submit" class="btn btn-info"/>
</form>
<br/>
<br/>
<a href="../">Go to main page</a>
</body>
</html>