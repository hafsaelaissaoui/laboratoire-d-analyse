<?php
$conn = new mysqli("localhost", "root", "", "labonew");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $cin = $_POST["cin"];
    $telephone = $_POST["telephone"];
    $email = $_POST["email"];
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"];
    $statut = $_POST["statut"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (user_nom, user_prenom, user_cin, user_telephone, user_email, user_date_naissance, user_genre, user_status, username, user_password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nom, $prenom, $cin, $telephone, $email, $date_naissance, $genre, $statut, $username, $password);

    if ($stmt->execute()) {
        header("Location: ../admin/docteur.php");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>