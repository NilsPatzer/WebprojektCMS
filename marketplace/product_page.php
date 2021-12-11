<?php
include "../store.php";

// delete useless chars and spaces before and after string
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$title = $content = "";
$article_id = $rating = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $article = $state->get_article(intval($_GET["article_id"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article = $state->get_article(intval($_POST["article_id"]));

    if (is_bool($article)) {
        header("location:error.php");
    }

    if (isset($_POST["rating"])) {
        $title = test_input($_POST["title"]);
        $content = test_input($_POST["content"]);
        $rating = intval($_POST["rating"]);
        $article_id = intval($_POST["article_id"]);

        $errComment = $errTitle = $errContent = "";

        //validate title
        if (empty($title)) {
            $errTitle = "Bitte füll den Titel aus";
        } elseif (strlen($title) > 32) {
            $errTitle = "Der Titel darf nicht länger als 32 Zeichen sein!";
        }

        if (!empty($errTitle) || !empty($errContent) || $state->add_comment($title, $content, $rating, $article_id) === false) {
            $errComment = "Etwas ist schief gelaufen. Versuch es später erneut!";
        } else {
            header("location:product_page.php?article_id=" . $article_id);
        }
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
    <script src="./rateComment.js" defer></script>

    <title>Webprojekt Nils Patzer</title>
</head>

<body>
    <?php include "../shared/navbar.php" ?>

    <main>
        <div class="container">
            <div class="row mb-5">
                <div class="col-3">
                    <div class="custom-thumbnail thumbnail-big">
                        <img src="<?php echo $article->get_image() ?>" alt="thumbnail" class="card-img-top bg-light" />
                    </div>
                </div>
                <div class="col-8 offset-1">
                    <h2><?php echo $article->get_title() ?></h2>
                    <p class="mb-0"><?php echo $article->get_content() ?></p>
                    <?php
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $article->get_rating()) {
                    ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill text-primary" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                            </svg>
                        <?php
                        } else {
                        ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star text-primary" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                            </svg>
                    <?php
                        }
                    }
                    ?>
                    <br><button class="btn btn-primary mt-5 mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modal">Kommentiere!</button>
                    <?php
                    if (!empty($errComment)) {
                        echo "<div class='error'>" . $errComment . "</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="row pt-5">
                <?php
                $state->fetch_comments();
                foreach ($state->get_comments_by_article_id($article->get_id()) as $comment) {
                ?>
                    <div class="col-4 py-3">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $comment->get_rating()) {
                        ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill text-primary" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                </svg>
                            <?php
                            } else {
                            ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star text-primary" viewBox="0 0 16 16">
                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                </svg>
                        <?php
                            }
                        }
                        ?>
                        <h5 class="mt-1"><?php echo $comment->get_title() ?></h5>
                        <p><?php echo $comment->get_content() ?></p>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </main>
    <!-- model begin -->
    <div id="modal" class="modal fade <?php echo (!empty($errComment) || !empty($errTitle) || !empty($errContent)) ? "show" : "" ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])  ?>">
                    <div class="modal-header">
                        <h5 id="modalLabel" class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div id="stars" class="col-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill text-primary" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star text-primary" viewBox="0 0 16 16">
                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                </svg>
                                <input id="inputRating" type="hidden" name="rating" value="<?php echo $rating ?>" />
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
                            <label for="inputContent" class="col-sm-2 col-form-label">Kommentar</label>
                            <div class="col-sm-9 offset-sm-1">
                                <textarea class="form-control" id="inputContent" name="content" cols="30" rows="5" placeholder="Ich finde den Artikel gut/schlecht, weil..."><?php echo $content ?></textarea>
                                <?php
                                if (!empty($errContent)) {
                                    echo "<div class='error'>" . $errContent . "</div>";
                                }
                                ?>
                            </div>
                        </div>
                        <input type="hidden" id="inputArticleId" name="article_id" value="<?php echo $article->get_id() ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">Kommentieren</button>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- Modal End-->
</body>

</html>