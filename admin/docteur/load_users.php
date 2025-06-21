<?php
$conn = new mysqli("localhost", "root", "", "labonew");
$result = $conn->query("SELECT * FROM users");

while ($row = $result->fetch_assoc()) {
    // حساب العمر من تاريخ الولادة
    $dob = new DateTime($row['user_date_naissance']);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    $user_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

    echo "<tr>
            <td>{$row['user_nom']}</td>
            <td>{$row['user_prenom']}</td>
            <td>{$row['user_cin']}</td>
            <td>{$row['user_telephone']}</td>
            <td>{$row['user_email']}</td>
            <td>{$row['user_date_naissance']}</td>
            <td>$age</td>
            <td>{$row['user_genre']}</td>
            <td>{$row['user_status']}</td>
            <td>{$row['username']}</td>
            <td>********</td> <!-- كلمة السر مخفية -->
            <td class='actions'>
                <button class='edit' onclick='openEditModal(this)' data-user='{$user_json}' title='Modifier'>&#9998;</button>
                <a href='delete_user.php?id={$row['id_user']}' class='delete' title='Supprimer' onclick='return confirm(\"Voulez-vous vraiment supprimer cet utilisateur ?\")'>&#128465;</a>
            </td>
          </tr>";
}
?>
