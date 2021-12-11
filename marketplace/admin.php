<?php
include "../restricted.php";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$title = $content =  "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = test_input($_POST["title"]);
    $content = test_input($_POST["content"]);
    $image = $_FILES["image"];

    $errTitle = $errContent = $errImage = $errMsg = "";

    //validate title
    if (empty($title)) {
        $errTitle = "Bitte füll den Titel aus";
    } elseif (strlen($title) > 32) {
        $errTitle = "Der Titel darf nicht länger als 32 Zeichen sein!";
    }

    //validate Image
    if (!isset($image)) {
        $errImage = "Ein Artikel benötigt ein Bild!";
    } elseif ($image["error"] > 0) {
        $errImage = "Bild konnte nicht geladen werden. Versuche es mit einem anderen Bild!";
    } elseif (!in_array(strtolower(pathinfo($image["name"], PATHINFO_EXTENSION)), array("png", "jpeg", "jpg"))) {
        $errImage = "Die Datei hat ein falsches Format";
    } elseif ($image["size"] > 500000) {
        $errImage = "Die Datei ist zu groß";
    }

    // validate image 

    $uploaddir = "./uploads/" . uniqid("", true) . "." . strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
    if (!move_uploaded_file($image["tmp_name"], $uploaddir)) {
        $errImage = "Datei konnte nicht gespeichert werden.";
    }

    if (!empty($errTitle) || !empty($errContent) || !empty($errImage) || $state->add_article($title, $content, $uploaddir) === false) {
        $errMsg = "Etwas ist schief gelaufen. Versuch es später erneut!";
    } else {
        header("location:marketplace.php");
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/custom.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>


    <title>Webprojekt Nils Patzer</title>
</head>

<body>
    <?php include "../shared/navbar.php" ?>

    <main>
        <div class="container">
            <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])  ?>" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="inputImage" class="col-sm-2 col-form-label">Bild</label>
                    <div class="col-sm-9 offset-sm-1">
                        <input type="file" class="form-control-file" id="inputImage" name="image" placeholder="image">
                        <?php
                        if (!empty($errImage)) {
                            echo "<div class='error'>" . $errImage . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputTitle" class="col-sm-2 col-form-label">Titel</label>
                    <div class="col-sm-9 offset-sm-1">
                        <input type="text" class="form-control" id="inputTitle" name="title" placeholder="Titel" value="<?php echo $title ?>">
                        <?php
                        if (!empty($errTitle)) {
                            echo "<div class='error'>" . $errTitle . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputContent" class="col-sm-2 col-form-label">Beschreibung</label>
                    <div class="col-sm-9 offset-sm-1">
                        <textarea class="form-control" id="inputContent" name="content" cols="30" rows="5" placeholder="Infos zu diesem Artikel"><?php echo $content ?></textarea>
                        <?php
                        if (!empty($errContent)) {
                            echo "<div class='error'>" . $errContent . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <input type="submit" name="submit" class="btn btn-primary mb-2" />
                        <?php
                        if (!empty($errMsg)) {
                            echo "<div class='error'>" . $errMsg . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" id="inputUserId" name="user_id" value="<?php echo $user_id ?>">
            </form>
        </div>
    </main>
</body>

</html>