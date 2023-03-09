<?php
if (!isset($_GET["grade"])) {
    header("location: ../../");
    exit;
}
$grade = $_GET["grade"];
?>
<!DOCTYPE html>
<html>
<head>
<title>Grade <?php echo $_GET["grade"]; ?> right or wrong game</title>
<meta name="description" content="Games for <?php echo $grade; ?> on science lessons"/>
<meta name="author" content="Ali Ebn Abi Taleb science group"/>
<meta name="keywords" content="grade, games, science, problems, thesciencelab, laboratory"/>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="icon" href="../../images/logo.webp"/>
<link rel="stylesheet" href="index.css"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
<script src="index.js" defer></script>
<link rel="stylesheet" href="../../assets/css/fontawesome.css"/>
<link href="../../assets/css/brands.css" rel="stylesheet"/>
<link href="../../assets/css/solid.css" rel="stylesheet"/>
</head>
<body>
<header>

    <nav>
        <a href="../../">home</a>
    </nav>

</header>
<main>
<section>
<h1>
    Welcome to the RIGHT OR WRONG game!
</h1>
    <p>
        Please choose a unit:
    </p>
    <div class="buttons">
        <?php
        $files = scandir($grade);
        foreach ($files as $file) {
            $fileInfo = pathinfo($file);
            if (isset($fileInfo["extension"]) && $fileInfo["extension"] == "json") {
                echo "<button class='button' data-unit='" . $fileInfo["filename"] . "'>" . $fileInfo["filename"] . "</button>";
            }
        }
        echo "<button class='button' data-unit='all'>The whole term</button>"
        ?>
    </div>
</section>
<section>
    <p>
        How many questions do you want?
    </p>
    <div class="buttons">
        
    </div>
</section>

</main>
<footer>
    All rights reserved for The Science Lab <span class="current-date">2022</span><span class="date"></span>
</footer>
<audio src="../../audio/right.mp3" id="rightAudio"></audio>
<audio src="../../audio/wrong.mp3" id="wrongAudio"></audio>
</body>
</html>