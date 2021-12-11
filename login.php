<?php
include "./store.php";

$name = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    if ($state->login($name, $password)) {
        header("Location:marketplace/marketplace.php");
    } else {
        $errMsg = "Benutzername oder Passwort war ungültig";
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../Webprojekt Nils Patzer/assets/style/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <title>Webprojekt Nils Patzer</title>
</head>

<body>
    <?php include "./shared/navbar.php" ?>

    <main>
        <div class="container">
            <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Benutzername</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Username" value="<?php echo $name ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Passwort</label>
                    <div class="col-sm-3">
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" value="<?php echo $password ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <input type="submit" value="Sign in" name="submit" class="btn btn-primary" />
                        <?php
                        if (!empty($errMsg)) {
                            echo "<div class='error'>" . $errMsg . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class=" col-sm-5">
                        <p>Noch keinen Account? <a href="register.php">Hier registrieren</a>.</p>
                    </div>
                </div>
            </form>
        </div>
    </main>
    </div>
</body>

</html>