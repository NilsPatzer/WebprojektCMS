<?php
include_once dirname(__FILE__) . "/../store.php";

$url = "http://localhost/php/Webprojekt%20Nils%20Patzer/";
?>

<header class="container">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="<?php echo $url . "assets/images/logo.svg" ?>" alt="logo" width="30" height="24" class="d-inline-block align-middle" />
                Nils Patzer Notebooks
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item mx-5">
                        <a class="nav-link" href="<?php echo $url . "index.php" ?>">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link" href="<?php echo $url . "gallery.php" ?>">Gallerie</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link" href="<?php echo $url . "marketplace/marketplace.php" ?>">Markt</a>
                    </li>
                    <?php
                    if ($state->is_logged_in()) {
                    ?>
                        <li class="nav-item mx-5">
                            <a class="nav-link link-danger" href="<?php echo $url . "logoff.php" ?>">Logoff</a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item mx-5">
                            <a class="nav-link" href="<?php echo $url . "login.php" ?>">Login</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>