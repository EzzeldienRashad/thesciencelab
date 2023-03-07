<?php
if (!isset($_GET["grade"])) {
    header("location: ../");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>The Science Lab</title>
<meta name="description" content="Games for all grades on science lessons"/>
<meta name="author" content="Ali Ebn Abi Taleb science group"/>
<meta name="keywords" content="grade, games, science, problems, thesciencelab, laboratory"/>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="icon" href="../images/logo.webp"/>
<link rel="stylesheet" href="index.css"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
<script src="index.js" defer></script>
<link rel="stylesheet" href="../assets/css/fontawesome.css"/>
<link href="../assets/css/brands.css" rel="stylesheet"/>
<link href="../assets/css/solid.css" rel="stylesheet"/>
</head>
<body>

<header>
    <nav>
        <a href="../">home</a>
    </nav>
</header>
<main>
<h1>Welcome to <?php echo $_GET["grade"]; ?>!</h1>
<h2>Please choose a game:</h2>
<section class="games">
<?php
function startsWith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}
foreach (array_values(array_filter(scandir("./"), function($file) {
    return is_dir("./" . $file) && $file != "." && $file != "..";
})) as $game) {
    echo "
    <a href='$game?grade=" . $_GET["grade"] . "'>
        <figure>
            <img src='../images/$game.webp' alt='" . str_replace("_", " ", $game) . "'/>
            <figcaption>" . str_replace("_", " ", $game) . "</figcaption>
        </figure>
    </a>
    ";
}
?>
</section>
</main>

<footer>
    All rights reserved for The Science Lab <span class="current-date">2022</span><span class="date"></span>
</footer>

</body>
</html>