<?php
$conn = new mysqli("localhost", "root", "", "labonew");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // تأكد أن المستخدم موجود
    $check = $conn->prepare("SELECT id_user FROM users WHERE id_user = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // حذف المستخدم
        $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: docteur.php");
            exit();
        } else {
            echo "Erreur lors de la suppression : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Utilisateur non trouvé.";
    }

    $check->close();
}

$conn->close();
?>
