<?php
session_start();
include '../includes/connexion.php'; // Fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Requête pour vérifier les informations du patient
        $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_email = ?");
        $stmt->execute([$email]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient && password_verify($password, $patient['patient_password'])) {
            // Connexion réussie
            $_SESSION['patient_id'] = $patient['id_patient'];
            $_SESSION['patient_nom'] = $patient['patient_nom'];
            $_SESSION['patient_prenom'] = $patient['patient_prenom'];
            header("Location: ../pages/accueil.php");
            exit;
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!-- Affichage de l'erreur si nécessaire -->
<?php if (isset($erreur)): ?>
  <p style="color: red; text-align: center;"><?php echo $erreur; ?></p>
<?php endif; ?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Laboratoire Chark</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Lato', sans-serif;
      margin: 0;
      background-color: #98b9cc;
    }
    header {
      background-color: #2D6486;
      color: white;
      padding: 10px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: 600;
    }
    header nav a:last-child {
      color: #F08C00;
    }
    .container {
      display: flex;
      max-width: 700px;
      margin: 60px auto;
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .login-form {
      flex: 2;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .login-form h2 {
      margin-bottom: 20px;
      text-align: center;
    }
    .social-icons {
      margin-bottom: 20px;
      text-align: center;
    }
    .social-icons i {
      margin: 0 10px;
      font-size: 20px;
      color: #2D6486;
    }
    input[type=email], input[type=password] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #eee;
    }
    .forgot {
      display: block;
      margin-bottom: 20px;
      text-align: center;
      color: #2D6486;
      font-size: 14px;
      text-decoration: none;
    }
    .btn-orange {
      background-color: #F08C00;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
      width: auto;
      min-width: 160px;
      display: block;
      margin: 10px auto 0;
    }
    .side-box {
      flex: 1;
      background-color: #F08C00;
      color: white;
      text-align: center;
      padding: 60px 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .side-box h3 {
      margin-bottom: 30px;
    }
    .side-box .btn-orange {
      background-color: white;
      color: #F08C00;
      padding: 12px 25px;
      border-radius: 25px;
      font-weight: bold;
      border: none;
      min-width: 160px;
      display: inline-block;
    }
  </style>
</head>
<body>
  <header>
    <img src="../photos/logo.png" alt="Logo" style="height: 50px">
    <nav>
      <a href="index.php">Accueil</a>
      <a href="resultat.php">Résultat</a>
      <a href="domicile.php">Services à domicile</a>
      <a href="compte.php">Compte</a>
    </nav>
  </header>

  <div class="container">
    <div class="login-form">
      <h2>Se connecter</h2>
      <div class="social-icons">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-google"></i>
        <i class="fab fa-linkedin-in"></i>
      </div>
      <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <a class="forgot" href="#">Mot de passe oublié ?</a>
        <button type="submit" class="btn-orange">SE CONNECTER</button>
      </form>
    </div>
    <div class="side-box">
      <h3>Hello, Patient</h3>
      <a href="register.php"><button class="btn-orange">S'INSCRIRE</button></a>
    </div>
  </div>

  <!-- inclusion du footer -->
  <?php include '../includes/footer.php'; ?>
</body>
</html>
