<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Gestion de Laboratoire APP</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .background {
      background: url('../../photos/background.jpg') no-repeat center center/cover;
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background-color: rgba(255, 165, 0, 0.85); /* orange semi-transparent */
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      text-align: center;
    }

    .login-box h2 {
      color: #003366;
      margin-bottom: 30px;
    }

    .input-group {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .input-group svg {
      width: 24px;
      height: 24px;
      margin-right: 10px;
      fill: black;
    }

    .input-group input {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 5px;
    }

    .login-button {
      background-color: black;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .login-button svg {
      width: 20px;
      height: 20px;
      fill: white;
    }
  </style>
</head>
<body>
  <div class="background">
    <div class="login-box">
      <h2>Gestion de Laboratoire APP</h2>
      <form action="login.php" method="post">
        
        <div class="input-group">
          <!-- Icône email -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M2,4v16h20V4H2z M12,13L4,7h16L12,13z M4,17V9l8,6l8-6v8H4z"/>
          </svg>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        </div>

        <div class="input-group">
          <!-- Icône mot de passe -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm6-7h-1V7a5 5 0 0 0-10 0v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2zM8 7a4 4 0 0 1 8 0v3H8V7z"/>
          </svg>
          <input type="password" name="password" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="login-button">
          <!-- Icône flèche login -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M10 17l5-5-5-5v10zM19 19H5V5h14v14z"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</body>
</html>
