<!DOCTYPE html>
<html>
<head>
<title>Grade 4 games</title>
<meta name="description" content="Games for grade 4 on science lessons"/>
<meta name="author" content="Ali Ebn Abi Taleb science group"/>
<meta name="keywords" content="4th grade, games, science, problems"/>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="icon" href="../../images/mainImages/logo.webp"/>
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
        <a href="../../">home</a><!--
     --><a href="../">Grade 4</a><!--
     --><a href="../../grade5">Grade 5</a>
    </nav>

</header>
<main>
<section>
<h1>
    Welcome to the CHOOSE game!
</h1>
    <p>
        Choose a unit:
    </p>
    <div class="buttons">
        <?php
        $files = scandir("./");
        foreach ($files as $file) {
            $fileInfo = pathinfo($file);
            if (isset($fileInfo["extension"]) && $fileInfo["extension"] == "json") {
                echo "<button class='button' data-unit='" . $fileInfo["filename"] . "'>" . $fileInfo["filename"] . "</button>";
            }
        }
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