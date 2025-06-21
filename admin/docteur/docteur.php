<!-- docteur.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des utilisateurs</title>
  <style>
    /* نفس الستايل السابق + modal و buttons */
    body { margin: 0; font-family: 'Segoe UI', sans-serif; display: flex; }
    .sidebar { width: 220px; background-color: #2D6486; padding: 20px; height: 100vh; }
    .sidebar h2 { margin-top: 0; color: white; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar li { padding: 10px 0; }
    .sidebar li a { text-decoration: none; color: white; display: block; }
    .sidebar .active a { font-weight: bold; color:rgb(234, 183, 15); }
    .main { flex: 1; padding: 20px; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; }
    #openModal { background-color: #2196F3; color: white; border: none; font-size: 24px; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table thead { background-color: #2196F3; color: white; }
    table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 25px; width: 400px; border-radius: 10px; position: relative; }
    .modal-content h2 { margin-top: 0; }
    .modal-content input, .modal-content select { width: 100%; padding: 8px; margin-bottom: 10px; }
    .modal-content button { width: 100%; padding: 10px; background-color: #2196F3; color: white; border: none; cursor: pointer; }
    #closeModal, #closeEditModal { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; }
    .actions { display: flex; gap: 10px; }
    .actions a { text-decoration: none; font-size: 18px; padding: 4px 8px; border-radius: 5px; }
    .actions .edit { color: #2196F3; background-color: #e3f2fd; }
    .actions .delete { color: #f44336; background-color: #ffebee; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Docteur</h2>
    <ul>
      <li><a href="#">Accueil</a></li>
      <li><a href="#">Patient</a></li>
      <li><a href="#">Analyses</a></li>
      <li class="active"><a href="#">Caissiers / Techniciens</a></li>
      <li><a href="#">Statistiques</a></li>
      <li><a href="#">Déconnexion</a></li>
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <h1>Caissiers / Techniciens</h1>
      <button id="openModal">+</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>CIN</th>
          <th>Téléphone</th>
          <th>Email</th>
          <th>Date Naissance</th>
          <th>age</th>
          <th>Genre</th>
          <th>Statut</th>
          <th>Username</th>
          <th>Password</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php include 'load_users.php'; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal d'ajout -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span id="closeModal">&times;</span>
      <h2>Ajouter un utilisateur</h2>
      <form action="add_users.php" method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="cin" placeholder="CIN" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="date" name="date_naissance" required>
        <select name="genre" required>
          <option value="">Genre</option>
          <option value="homme">Homme</option>
          <option value="femme">Femme</option>
        </select>
        <select name="statut" required>
          <option value="">Statut</option>
          <option value="caissier">Caissier</option>
          <option value="technicien">Technicien</option>
        </select>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Enregistrer</button>
      </form>
    </div>
  </div>

  <!-- Modal de modification -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span id="closeEditModal">&times;</span>
      <h2>Modifier l'utilisateur</h2>
      <form id="editForm" action="edit_user.php" method="POST">
        <input type="hidden" name="id_user" id="edit_id">
        <input type="text" name="nom" id="edit_nom" required>
        <input type="text" name="prenom" id="edit_prenom" required>
        <input type="text" name="cin" id="edit_cin" required>
        <input type="text" name="telephone" id="edit_telephone" required>
        <input type="email" name="email" id="edit_email" required>
        <input type="date" name="date_naissance" id="edit_date_naissance" required>
        <select name="genre" id="edit_genre" required>
          <option value="homme">Homme</option>
          <option value="femme">Femme</option>
        </select>
        <select name="statut" id="edit_statut" required>
          <option value="caissier">Caissier</option>
          <option value="technicien">Technicien</option>
        </select>
        <input type="text" name="username" id="edit_username" required>
        <input type="password" name="password" id="edit_password" placeholder="Mot de passe (laisser vide si inchangé)">

        <button type="submit">Modifier</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("modal");
    const editModal = document.getElementById("editModal");
    document.getElementById("openModal").onclick = () => modal.style.display = "flex";
    document.getElementById("closeModal").onclick = () => modal.style.display = "none";
    document.getElementById("closeEditModal").onclick = () => editModal.style.display = "none";

    window.onclick = (e) => {
      if (e.target === modal) modal.style.display = "none";
      if (e.target === editModal) editModal.style.display = "none";
    };

    function openEditModal(button) {
      const user = JSON.parse(button.getAttribute('data-user'));
      document.getElementById("edit_id").value = user.id_user;
      document.getElementById("edit_nom").value = user.user_nom;
      document.getElementById("edit_prenom").value = user.user_prenom;
      document.getElementById("edit_cin").value = user.user_cin;
      document.getElementById("edit_telephone").value = user.user_telephone;
      document.getElementById("edit_email").value = user.user_email;
      document.getElementById("edit_date_naissance").value = user.user_date_naissance;
      document.getElementById("edit_genre").value = user.user_genre;
      document.getElementById("edit_statut").value = user.user_status;
      document.getElementById("edit_username").value = user.username;
      editModal.style.display = "flex";
    }

    
  </script>
</body>
</html>
