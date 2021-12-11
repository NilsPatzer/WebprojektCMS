<?php

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Webprojekt Nils Patzer/assets/style/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500&display=swap" rel="stylesheet">
    <script src="../Webprojekt Nils Patzer/assets/js/main.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>


    <title>Webprojekt Nils Patzer</title>
</head>

<body>
    <?php include "./shared/navbar.php" ?>

    <div class="slider">
        <div class="slider-items">
            <div class="item active">
                <img src="./assets/images/lightbox1.jpg" />
                <div class="caption">
                    Design
                </div>
            </div>
            <div class="item">
                <img src="./assets/images/lightbox2.jpg" />
                <div class="caption">
                    Management
                </div>
            </div>
            <div class="item">
                <img src="./assets/images/lightbox3.jpg" />
                <div class="caption">
                    Flexibilität
                </div>
            </div>

        </div>
        <!-- controls -->
        <div class="left-slide"></div>
        <div class="right-slide">></div>
        <!-- controls -->
    </div>
    <div class="p-tags-gallery">
        <p>Nutze dein Laptop wann und wo du willst. Die Batterieleistung hält bei normaler Nutzung den ganzen Tag.</p>
    </div>

    <div class="first-gallery">
        <img class="gallery-hightlight" src="./assets/images/lightbox1.jpg" alt="image1" />
        <div class="gallery-preview">

            <img src="./assets/images/lightbox1.jpg" class="gallery-active" alt="" />
            <img src="./assets/images/lightbox2.jpg" alt="" />
            <img src="./assets/images/lightbox3.jpg" alt="" />
        </div>
    </div>

</body>

</html>