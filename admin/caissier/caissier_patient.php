<?php
$pdo = new PDO("mysql:host=localhost;dbname=labonew", "root", "");

// Récupération des patients
$stmt = $pdo->query("SELECT * FROM patient ORDER BY id_patient DESC");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des patients</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
  <style>
  body {
  margin: 0;
  font-family: Arial;
  background: #f4f4f4;
}

.sidebar {
  width: 200px;
  background: #2D6486;
  color: white;
  height: 100vh;
  position: fixed;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar li {
  padding: 15px;
}

.sidebar a {
  color: white;
  text-decoration: none;
}

.main-content {
  margin-left: 220px;
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: center;
}

th {
  background: #F08C00;
  color: white;
}

.actions {
  text-align: right;
  margin-bottom: 10px;
}

.actions button {
  background: #F08C00;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 5px;
}

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 10;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
}

.modal-content {
  background: white;
  width: 400px;
  margin: 10% auto;
  padding: 20px;
  border-radius: 8px;
}

.modal-content form input,
.modal-content form select {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
}

.modal-content input[type="submit"] {
  background: #2D6486;
  color: white;
  border: none;
  cursor: pointer;
}

.close {
  float: right;
  font-size: 20px;
  cursor: pointer;
}
</style>
</head>
<body>
  <div class="sidebar">
    <ul>
      <li><a href="#">Accueil</a></li>
      <li><a href="#">Tarifs des analyses</a></li>
      <li><a href="#">Notifications 🔔</a></li>
      <li><a href="#">Déconnexion</a></li>
    </ul>
  </div>

  <div class="main-content">
    <h2>Gestion des patients</h2>
    <div class="actions">
      <button id="btn-ajouter" title="Ajouter">➕</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>CIN</th>
          <th>Âge</th>
          <th>Date de naissance</th>
          <th>Téléphone</th>
          <th>Email</th>
          <th>Genre</th>
          <th>Créé le</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($patients as $p): 
          $birthdate = new DateTime($p['patient_date_naissance']);
          $today = new DateTime();
          $age = $today->diff($birthdate)->y;
        ?>
        <tr>
          <td><?= htmlspecialchars($p['patient_nom']) ?></td>
          <td><?= htmlspecialchars($p['patient_prenom']) ?></td>
          <td><?= $p['patient_telephone'] == '' ? '---' : htmlspecialchars($p['patient_telephone']) ?></td>
          <td><?= $age ?></td>
          <td><?= $p['patient_date_naissance'] ?></td>
          <td><?= htmlspecialchars($p['patient_telephone']) ?></td>
          <td><?= htmlspecialchars($p['patient_email']) ?></td>
          <td><?= $p['patient_genre'] ?></td>
          <td><?= $p['patient_date_inscription'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Fenêtre modale -->
  <div class="modal" id="modal-ajouter">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Ajouter un patient</h3>
      <form action="ajouter_patient.php" method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="cin" placeholder="CIN (facultatif)">
        <input type="date" name="date_naissance" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="email" name="email" placeholder="Email" (facultatif)>
        <select name="genre" required>
          <option value="">-- Genre --</option>
          <option value="Homme">Homme</option>
          <option value="Femme">Femme</option>
        </select>
        <input type="submit" value="Enregistrer">
      </form>
    </div>
  </div>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modal-ajouter");
  const btn = document.getElementById("btn-ajouter");
  const closeBtn = document.querySelector(".modal .close");

  btn.onclick = () => modal.style.display = "block";
  closeBtn.onclick = () => modal.style.display = "none";
  window.onclick = (e) => {
    if (e.target == modal) modal.style.display = "none";
  };
});
</script>

</body>
</html>
