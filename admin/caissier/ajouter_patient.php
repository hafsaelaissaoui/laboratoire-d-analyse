<?php
$pdo = new PDO("mysql:host=localhost;dbname=labonew", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$cin = $_POST['cin'] ?? '';
$date_naissance = $_POST['date_naissance'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$genre = $_POST['genre'];
$date_inscription = date("Y-m-d H:i:s");

$stmt = $pdo->prepare("INSERT INTO patient (
    patient_nom, patient_prenom, patient_telephone, patient_date_naissance,
    patient_genre, patient_date_inscription, patient_password, patient_email
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$password = password_hash("123456", PASSWORD_DEFAULT); // mot de passe par défaut

$stmt->execute([
    $nom, $prenom, $telephone, $date_naissance,
    $genre, $date_inscription, $password, $email
]);

header("Location: ../admin/gestion_patient.php");
exit;
