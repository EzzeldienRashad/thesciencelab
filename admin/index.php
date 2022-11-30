<?php
    session_start();
    if (isset($_POST["password"]) && $_POST["password"] == "1t2h3e4s5c@G") {
        $_SESSION["isAdmin"] = true;
        header("location: choose");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
</head>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <label>
        Password: <input type="password" name="password"/>
    </label>
    <input type="submit" value="submit"/>
</form>
<br/>
<br/>
<a href="../">Go Back</a>
</body>
</html>