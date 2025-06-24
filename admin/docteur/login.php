<?php
session_start();
include '../../includes/connexion.php';

$erreur = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            // Vérifier d'abord dans la table users (caissier/technicien)
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['user_password'] === $password) {
                // Connexion réussie pour caissier/technicien
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_nom'] = $user['user_nom'];
                $_SESSION['user_prenom'] = $user['user_prenom'];
                $_SESSION['user_status'] = $user['user_status'];
                $_SESSION['user_type'] = 'user';
                
                // Redirection selon le type d'utilisateur
                if ($user['user_status'] === 'caissier') {
                    header("Location: ../caissier/caissier.php");
                } else {
                    header("Location: accueil.php");
                }
                exit;
            } else {
                // Vérifier les comptes admin par défaut
                $admin_accounts = [
                    'admin' => 'admin123',
                    'docteur' => 'docteur123'
                ];
                
                if (isset($admin_accounts[$username]) && $admin_accounts[$username] === $password) {
                    $_SESSION['user_id'] = 1;
                    $_SESSION['user_nom'] = 'Admin';
                    $_SESSION['user_prenom'] = 'Système';
                    $_SESSION['user_status'] = 'admin';
                    $_SESSION['user_type'] = 'admin';
                    
                    header("Location: accueil.php");
                    exit;
                } else {
                    $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            }
        } catch (PDOException $e) {
            $erreur = "Erreur de connexion à la base de données.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Admin - Laboratoire</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      height: 100vh;
      background: linear-gradient(135deg, #2D6486 0%, #6A9AB0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .logo {
      margin-bottom: 30px;
    }

    .logo h1 {
      color: #2D6486;
      margin: 0;
      font-size: 28px;
    }

    .logo p {
      color: #666;
      margin: 5px 0 0 0;
      font-size: 14px;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 600;
    }

    .input-group {
      position: relative;
    }

    .input-group input {
      width: 100%;
      padding: 12px 15px 12px 45px;
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
      box-sizing: border-box;
    }

    .input-group input:focus {
      outline: none;
      border-color: #2D6486;
    }

    .input-group .icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
      font-size: 18px;
    }

    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #f5c6cb;
      font-size: 14px;
    }

    .login-button {
      width: 100%;
      background: linear-gradient(135deg, #2D6486 0%, #6A9AB0 100%);
      color: white;
      border: none;
      padding: 15px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .login-button:hover {
      transform: translateY(-2px);
    }

    .login-button:active {
      transform: translateY(0);
    }

    .footer-links {
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid #e1e1e1;
    }

    .footer-links a {
      color: #2D6486;
      text-decoration: none;
      font-size: 14px;
      margin: 0 10px;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    /* Icons using CSS */
    .icon-user::before {
      content: "👤";
    }

    .icon-lock::before {
      content: "🔒";
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="logo">
      <h1>Laboratoire Chark</h1>
      <p>Administration</p>
    </div>

    <?php if (!empty($erreur)): ?>
      <div class="error-message">
        <?php echo htmlspecialchars($erreur); ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <div class="input-group">
          <span class="icon icon-user"></span>
          <input type="text" id="username" name="username" required 
                 value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                 placeholder="Entrez votre nom d'utilisateur">
        </div>
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <div class="input-group">
          <span class="icon icon-lock"></span>
          <input type="password" id="password" name="password" required 
                 placeholder="Entrez votre mot de passe">
        </div>
      </div>

      <button type="submit" class="login-button">Se connecter</button>
    </form>

    <div class="footer-links">
      <a href="../../utilisateur/index.php">Retour au site</a>
      <a href="../../utilisateur/compte.php">Espace Patient</a>
    </div>
  </div>
</body>
</html>