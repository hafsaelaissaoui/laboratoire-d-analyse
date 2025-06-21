<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Serveur de résultats - Laboratoire Chark</title>
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
            background-color: #6A9AB0;
            color: white;
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
      position: relative;
      padding: 0.2rem 0.4rem;
      transition: color 0.3s;
      font-weight: 600;
    }

    nav ul li a:hover,
    nav ul li a.active {
      color: var(--orange);
    }
        h2 {
            margin-left: 40px;
            margin-top: 30px;
            font-size: 20px;
        }

        .login-box {
            background-color: #D9E3E7;
            color: black;
            width: 350px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 6px;
        }

        .login-box h3 {
            background-color: #2D6486;
            color: white;
            padding: 8px;
            text-align: center;
            margin-top: 0;
            border-radius: 4px 4px 0 0;
        }

        .login-box label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login-box .forgot {
            font-size: 14px;
            margin-top: 10px;
        }

        .login-box button {
            margin-top: 10px;
            padding: 6px 15px;
            background-color: white;
            border: 1px solid #888;
            cursor: pointer;
        }

        .info-section {
            max-width: 900px;
            margin: 30px auto;
            font-size: 14px;
            line-height: 1.6;
        }

        .info-section ul {
            padding-left: 20px;
        }

        .important {
            color: #F08C00;
            font-weight: bold;
            margin-top: 15px;
        }
  
    </style>
</head>
<body>

   <nav>
    <div class="logo">Laboratoire Chark</div>
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="resultat.php"class="active">Résultat</a></li>
      <li><a href="domicile.php">Services à domicile</a></li>
      <li><a href="compte.php">Compte</a></li>
    </ul>
</nav>

    <h2>Serveut de résultats -Identification</h2>

    <div class="login-box">
        <h3>ACCES PATIENT</h3>
        <form>
            <label>Numéro Patient :</label>
            <input type="text" name="numero" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <div class="forgot">Mot de passe oublié ?</div>

            <button type="submit">Se connecter</button>
        </form>
    </div>

    <div class="info-section">
        <h3>Accès patients : Comment obtenir vos résultats ?</h3>
        <ul>
            <li>Communiquez votre adresse e-mail personnelle au laboratoire à l’occasion de votre prochaine prise de sang.</li>
            <li>Nous vous enverrons alors, dès que vos résultats seront disponibles, un e-mail comportant un mot de passe strictement confidentiel vous permettant de vous identifier.</li>
            <li>Lors de votre première identification, la saisie d’un mot de passe personnel et permanent vous sera demandée pour consulter vos résultats en toute sécurité. Ce mot de passe sera valable 180 jours avant une demande de renouvellement.</li>
        </ul>

        <div class="important">IMPORTANT :</div>
        <ul>
            <li>Vous devez nous fournir une adresse e-mail qui vous est propre.</li>
            <li>Assurez-vous de tenir votre mot de passe à l’abri des regards indiscrets. Nous ne saurions être tenu pour responsable de la divulgation de votre mot de passe.</li>
        </ul>
    </div>

  <!-- Footer -->
 <?php include '../includes/footer.php';?>


</body>
</html>
