<?php
include "store.php";

try {
    $user = $state->get_current_user();

    if ($user === false) {
        echo "Nicht berechtigter Nutzer!";
        exit;
    }
} catch (PDOException $e) {
    echo "Datenbankzugriff fehlgeschlagen: " . $e->getMessage();
    exit;
}
