<?php
$conn = new mysqli("localhost", "root", "", "labonew");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id_user"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $cin = $_POST["cin"];
    $telephone = $_POST["telephone"];
    $email = $_POST["email"];
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"];
    $statut = $_POST["statut"];
    $username = $_POST["username"];

    $stmt = $conn->prepare("UPDATE users SET user_nom=?, user_prenom=?, user_cin=?, user_telephone=?, user_email=?, user_date_naissance=?, user_genre=?, user_status=?, username=? WHERE id_user=?");
    $stmt->bind_param("sssssssssi", $nom, $prenom, $cin, $telephone, $email, $date_naissance, $genre, $statut, $username, $id);

    if ($stmt->execute()) {
        header("Location: docteur.php");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
