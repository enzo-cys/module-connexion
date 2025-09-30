<?php
// Fichier: config/db.php
// (localhost ou Plesk).
$DB_HOST = 'localhost';
$DB_NAME = 'moduleconnexion';
$DB_USER = 'root';
$DB_PASS = ''; // Mets ici ton mot de passe MySQL si nécessaire

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // En production, ne pas afficher le message complet
    die("Erreur connexion BDD : " . htmlspecialchars($e->getMessage()));
}
