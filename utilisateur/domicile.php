<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=labonew;charset=utf8", "root", "");

// Traitement du formulaire
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $cin = $_POST["cin"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"] ?? '';
    $infirmier_genre = $_POST["genre_inf"] ?? '';
    $date_service = $_POST["date_service"];

    // Vérifier si le patient existe déjà
    $stmt = $pdo->prepare("SELECT id_patient FROM patient WHERE patient_email = ?");
    $stmt->execute([$email]);
    $patient = $stmt->fetch();

    if ($patient) {
        $id_patient = $patient['id_patient'];
    } else {
        // Nouveau patient
        $stmt = $pdo->prepare("INSERT INTO patient (patient_nom, patient_prenom, patient_telephone, patient_date_naissance, patient_genre, patient_date_inscription, patient_password, patient_email)
                               VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->execute([$nom, $prenom, $telephone, $date_naissance, $genre, password_hash("123456", PASSWORD_DEFAULT), $email]);
        $id_patient = $pdo->lastInsertId();
    }

    // Upload ordonnance
    $filePath = "";
    if (!empty($_FILES["ordonnance"]["name"])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filePath = $targetDir . uniqid() . '_' . basename($_FILES["ordonnance"]["name"]);
        move_uploaded_file($_FILES["ordonnance"]["tmp_name"], $filePath);
    }

    // Insérer la demande dans la table dossier
    $stmt = $pdo->prepare("INSERT INTO dossier (date_dossier, libelle, id_patient, type_service, service_genre_infirmier, service_date_demande, ordonnance_path, service_etat)
                           VALUES (NOW(), 'Demande domicile', ?, 'domicile', ?, ?, ?, 'en attente')");
    $stmt->execute([$id_patient, $infirmier_genre, $date_service, $filePath]);

    $message = "✅ Demande envoyée avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Formulaire à domicile</title>
  <style>
    :root {
      --orange: #F08C00;
      --blue-light: #6A9AB0;
      --blue-dark: #2D6486;
      --white: #fff;
      --grey-light: #f7f7f7;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: var(--white);
    }
    nav {
      background-color: var(--blue-dark);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      color: var(--white);
    }

    nav .logo {
      font-weight: bold;
      font-size: 1.2rem;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      color: var(--white);
      text-decoration: none;
      font-weight: 600;
    }

    nav ul li a:hover,
    nav ul li a.active {
      color: var(--orange);
    }

    .container {
      max-width: 900px;
      background: white;
      padding: 20px 30px;
      margin: 40px auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      color: #2D6486;
    }

    .success-message {
      text-align: center;
      color: green;
      font-weight: bold;
      margin-bottom: 15px;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: space-between;
    }

    .form-group {
      flex: 1 1 30%;
      display: flex;
      flex-direction: column;
    }

    .form-group-full {
      flex: 1 1 100%;
      text-align: center;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    input, select {
      padding: 8px;
      border: 1px solid #2D6486;
      border-radius: 4px;
    }

    .form-group-full input[type="file"] {
      margin-top: 10px;
    }

    button {
      background-color: #F08C00;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #d97600;
    }
  </style>
</head>
<body>
  <nav>
    <div class="logo">Laboratoire Chark</div>
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="resultat.php">Résultat</a></li>
      <li><a href="domicile.php" class="active">Services à domicile</a></li>
      <li><a href="compte.php">Compte</a></li>
    </ul>
  </nav>

  <div class="container">
    <h2>Formulaire - Prélèvement à domicile</h2>
    <p style="text-align: center;">Pour toute demande de RDV, merci de nous contacter par email ou WhatsApp ou remplir ce formulaire :</p>

    <?php if (!empty($message)): ?>
      <div class="success-message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" required>
      </div>
      <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom" required>
      </div>
      <div class="form-group">
        <label>CIN</label>
        <input type="text" name="cin" required>
      </div>
      <div class="form-group">
        <label>Date de naissance</label>
        <input type="date" name="date_naissance" required>
      </div>
      <div class="form-group">
        <label>Genre</label>
        <select name="genre" required>
          <option value="">-- Choisir --</option>
          <option value="Homme">Homme</option>
          <option value="Femme">Femme</option>
        </select>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="telephone">Téléphone :</label>
        <input type="tel" name="telephone" id="telephone" required pattern="[0-9]{10}" maxlength="10" title="Entrez exactement 10 chiffres">
      </div>
      <div class="form-group">
        <label>Date de service</label>
        <input type="datetime-local" name="date_service" required>
      </div>
      <div class="form-group">
        <label>Genre souhaité d’infirmier</label>
        <select name="genre_inf" required>
          <option value="">-- Choisir --</option>
          <option value="Infirmier">Infirmier</option>
          <option value="Infirmière">Infirmière</option>
        </select>
      </div>
      <div class="form-group-full">
        <label>Joindre l’ordonnance : *</label>
        <input type="file" name="ordonnance" accept=".pdf,.jpg,.png,.jpeg" required>
      </div>
      <div class="form-group-full">
        <button type="submit">Envoyer</button>
      </div>
    </form>
  </div>

  <?php include '../includes/footer.php'; ?>

  <script>
    const telInput = document.getElementById("telephone");
    telInput.addEventListener("input", function () {
      this.value = this.value.replace(/\D/g, '');
      if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
      }
    });
  </script>
</body>
</html>
