<?php
$host = 'localhost'; // ou 127.0.0.1
$dbname = 'labonew'; // nom de ta base de données
$username = 'root';  // nom d'utilisateur MySQL (souvent 'root' en local)
$password = '';      // mot de passe MySQL (vide par défaut en local XAMPP/WAMP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activer les erreurs PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>