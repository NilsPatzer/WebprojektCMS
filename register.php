<?php
include "./store.php";

$email = $name = $password = $date_of_birth = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = test_input($_POST["email"]);
    $name = test_input($_POST["name"]);
    $password = test_input($_POST["password"]);
    $date_of_birth = test_input($_POST["date_of_birth"]);

    $errEmail = $errName = $errPass = $errDOB = $errMsg = "";

    // validate Email 
    if (empty($email) || !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/", $email)) {
        $errEmail = "Please enter valid Email!";
    }

    // validate name 
    if (empty($name)) {
        $errName = "Please enter valid username!";
    } elseif (strlen($name) < 4) {
        $errName = "Please enter more than 4 characters!";
    } elseif (!preg_match("/^[a-zA-Z]*$/", $name)) {
        $errName = "Please use only the alphabet!";
    } else {
        if ($state->check_username($name)) {
            $errName = "Dieser Benutzername ist bereits vergeben!";
        }
    }

    // validate password 
    if (empty($password)) {
        $errPass = "Please enter valid Password!";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password)) {
        $errPass = "Password must contain minimum eight characters, at least one letter, one number and one special character!";
    }

    // validate DOB 
    if (preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $date_of_birth)) {
        list($month, $day, $year) = explode("/", $date_of_birth);
        if (!checkdate($month, $day, $year)) {
            $errDOB = "Please enter valid Date of Birth!";
        } elseif (time() < DateTime::createFromFormat('m/d/Y', $date_of_birth)->getTimestamp()) {
            $errDOB = "You are not born yet!!";
        }
    } else {
        $errDOB = "Please enter valid Date of Birth!";
    }

    // no errors
    if (empty($errEmail) && empty($errName) &&  empty($errPass) && empty($errDOB) && $state->register($email, $name, $password, $date_of_birth)) {
        mail("nils.patzer@gmx.de", "Test Email", "Dies ist vollkommen automatisierte eletronische Briefnotifikation!");
        header("Location:marketplace/marketplace.php");
    } else {
        $errMsg = "Etwas ist schief gelaufen!";
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../Webprojekt Nils Patzer/assets/style/style.css"> -->
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
            <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])  ?>">
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">E-Mail</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="inputEmail" name="email" placeholder="Email" value="<?php echo $email ?>">
                        <?php
                        if (!empty($errEmail)) {
                            echo "<div class='error'>" . $errEmail . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Benutzername</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Username" value="<?php echo $name ?>">
                        <?php
                        if (!empty($errName)) {
                            echo "<div class='error'>" . $errName . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Passwort</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="inputPassword" name="password" placeholder="Password" value="<?php echo $password ?>">
                        <?php
                        if (!empty($errPass)) {
                            echo "<div class='error'>" . $errPass . "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputDOB" class="col-sm-2 col-form-label">Geburtstag</label>
                    <div id="datepicker" class="col-sm-3 input-append date">
                        <input type="text" class="form-control" id="inputDOB" name="date_of_birth" placeholder="Geburtstag" value="<?php echo $date_of_birth ?>">
                        <?php
                        if (!empty($errDOB)) {
                            echo "<div class='error'>" . $errDOB . "</div>";
                        }
                        ?>
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
                        <p>Bereits registriert? <a href="login.php">Hier geht's zum Login</a>.</p>
                    </div>
                </div>
            </form>
        </div>


    </main>

    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker();
        });
    </script>
</body>

</html>