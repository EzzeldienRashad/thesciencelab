<?php
$msg = "";
session_start();
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != true) {
    header("location: ../");
    exit;
}
if (!isset($_GET["grade"]) || !isset($_GET["unit"])) {
    // header("location: index.php");
    // exit;
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
    <style>
        h3 {
            text-align: center;
            background-color: darkgreen;
            color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
<header>
    <?php if ($msg != "") echo "<h3>" . $msg . "</h3>"; ?>
</header>
<main>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?grade=" . $_GET["grade"] . "&unit=" . $_GET["unit"] ?>">
        <label>
            Question: <input type="text" name="question" required/>
        </label>
        <br/>
        <br/>
        <label>
            First answer: <input type="text" name="first" required/>
        </label>
        <br/>
        <br/>
        <label>
            Second answer: <input type="text" name="second" required/>
        </label>
        <br/>
        <br/>
        <label>
            Third answer: <input type="text" name="third" required/>
        </label>
        <br/>
        <br/>
        <label>
            Fourth answer: <input type="text" name="fourth" required/>
        </label>
        <br/>
        <br/>
        <label>
            Number of the right answer: <input type="number" name="number" max="4" min="1" required/>
        </label>
        <br/>
        <br/>
        <input type="submit" name="submit" value="submit"/>
    </form>
    <br/>
    <br/>
    <a href="index.php">Go Back</a>
</main>
</body>
</html>
