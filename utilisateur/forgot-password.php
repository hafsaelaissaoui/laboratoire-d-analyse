<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié - Laboratoire Chark</title>
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
      max-width: 600px;
      margin: 60px auto;
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-container {
      flex: 1;
      padding: 40px;
      text-align: center;
    }
    .form-container h2 {
      margin-bottom: 20px;
      color: #2D6486;
    }
    .form-container p {
      margin-bottom: 30px;
      color: #666;
      line-height: 1.6;
    }
    input[type=email] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #eee;
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
      margin: 20px auto 0;
    }
    .back-link {
      display: block;
      margin-top: 20px;
      color: #2D6486;
      text-decoration: none;
      font-weight: 600;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .info-box {
      background-color: #e7f3ff;
      border: 1px solid #b3d9ff;
      border-radius: 5px;
      padding: 15px;
      margin: 20px 0;
      color: #0066cc;
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
    <div class="form-container">
      <h2><i class="fas fa-key"></i> Mot de passe oublié</h2>
      <p>Entrez votre adresse email pour recevoir les instructions de réinitialisation de votre mot de passe.</p>
      
      <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <strong>Fonctionnalité en développement</strong><br>
        Cette fonctionnalité sera bientôt disponible. En attendant, contactez le laboratoire pour réinitialiser votre mot de passe.
      </div>
      
      <form>
        <input type="email" placeholder="Votre adresse email" required disabled>
        <button type="button" class="btn-orange" disabled>Envoyer les instructions</button>
      </form>
      
      <a href="compte.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Retour à la connexion
      </a>
      
      <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <p><strong>Besoin d'aide ?</strong></p>
        <p>Contactez le laboratoire :</p>
        <p>📞 +(212) 539 954 000</p>
        <p>📧 labochark@gmail.com</p>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>
</html>