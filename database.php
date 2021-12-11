<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=patzerdb',
        'root',
        '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
    );
} catch (PDOException $e) {
    echo "Verbindung fehlgeschlagen: " . $e->getMessage();
    exit;
}
