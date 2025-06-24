<?php
session_start();
include '../includes/connexion.php';

$erreurs = [];
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et validation des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $cin = trim($_POST['cin'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation des champs obligatoires
    if (empty($nom)) $erreurs[] = "Le nom est obligatoire.";
    if (empty($prenom)) $erreurs[] = "Le prénom est obligatoire.";
    if (empty($telephone)) $erreurs[] = "Le téléphone est obligatoire.";
    if (empty($genre)) $erreurs[] = "Le genre est obligatoire.";
    if (empty($email)) $erreurs[] = "L'email est obligatoire.";
    if (empty($date_naissance)) $erreurs[] = "La date de naissance est obligatoire.";
    if (empty($password)) $erreurs[] = "Le mot de passe est obligatoire.";

    // Validation du format email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Format d'email invalide.";
    }

    // Validation du téléphone (10 chiffres)
    if (!empty($telephone) && !preg_match('/^[0-9]{10}$/', $telephone)) {
        $erreurs[] = "Le téléphone doit contenir exactement 10 chiffres.";
    }

    // Validation du mot de passe
    if (strlen($password) < 6) {
        $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if ($password !== $confirm_password) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification de l'unicité de l'email
    if (empty($erreurs)) {
        try {
            $stmt = $conn->prepare("SELECT id_patient FROM patient WHERE patient_email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $erreurs[] = "Cet email est déjà utilisé.";
            }
        } catch (PDOException $e) {
            $erreurs[] = "Erreur de vérification de l'email.";
        }
    }

    // Insertion en base de données
    if (empty($erreurs)) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $date_inscription = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("
                INSERT INTO patient (
                    patient_nom, patient_prenom, patient_cin, patient_telephone, 
                    patient_date_naissance, patient_genre, patient_date_inscription, 
                    patient_password, patient_email
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $nom, $prenom, $cin, $telephone, $date_naissance, 
                $genre, $date_inscription, $password_hash, $email
            ]);

            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            
            // Redirection après 3 secondes
            header("refresh:3;url=compte.php");
            
        } catch (PDOException $e) {
            $erreurs[] = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Laboratoire Chark</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: linear-gradient(to bottom, #6a9ab0, #2d6486);
        }

        .header {
            background-color: #2d6486;
            padding: 1rem 1.5rem;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 3rem;
            height: 3rem;
            background-color: #ff8c00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .logo-text {
            color: white;
        }

        .logo-title {
            font-weight: bold;
            font-size: 1.125rem;
        }

        .logo-subtitle {
            font-size: 0.875rem;
            letter-spacing: 0.1em;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #ff8c00;
        }

        .nav a.active {
            color: #ff8c00;
            font-weight: 600;
        }

        .main-content {
            max-width: 1152px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .content-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .welcome-panel {
            background-color: #ff8c00;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .welcome-title {
            color: white;
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .connect-btn {
            border: 2px solid white;
            color: white;
            background: transparent;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .connect-btn:hover {
            background: white;
            color: #ff8c00;
        }

        .form-panel {
            padding: 3rem;
            background-color: #f5f5f5;
        }

        .form-title {
            font-size: 1.875rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #000000;
        }

        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-input {
            background-color: #d9d9d9;
            border: none;
            padding: 0.75rem 1rem;
            height: 3rem;
            border-radius: 0.375rem;
            font-size: 1rem;
        }

        .form-input::placeholder {
            color: #a6a3a3;
        }

        .form-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #ff8c00;
        }

        .form-input.error {
            border: 2px solid #dc3545;
        }

        .submit-section {
            padding-top: 1rem;
            display: flex;
            justify-content: center;
        }

        .submit-btn {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 0.75rem 3rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1.125rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #f08c00;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
            }

            .content-card {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">🔬</div>
                <div class="logo-text">
                    <div class="logo-title">Laboratoire</div>
                    <div class="logo-subtitle">CHARK</div>
                </div>
            </div>
            <nav class="nav">
                <a href="index.php">Accueil</a>
                <a href="resultat.php">Resultat</a>
                <a href="domicile.php">Services a domicile</a>
                <a href="compte.php" class="active">Contact</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="content-card">
            <div class="welcome-panel">
                <h2 class="welcome-title">Bienvenue à nouveau !</h2>
                <a href="compte.php" class="connect-btn">SE CONNECTER</a>
            </div>

            <div class="form-panel">
                <h2 class="form-title">Créer un compte</h2>

                <?php if (!empty($erreurs)): ?>
                    <div class="error-messages">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach ($erreurs as $erreur): ?>
                                <li><?php echo htmlspecialchars($erreur); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form class="form" method="POST">
                    <div class="form-row">
                        <input type="text" name="prenom" placeholder="Prénom" class="form-input" required 
                               value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                        <input type="text" name="nom" placeholder="Nom" class="form-input" required
                               value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                    </div>

                    <div class="form-row">
                        <input type="tel" name="telephone" placeholder="Téléphone (10 chiffres)" class="form-input" required
                               pattern="[0-9]{10}" maxlength="10"
                               value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                        <select name="genre" class="form-input" required>
                            <option value="">-- Genre --</option>
                            <option value="Homme" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                            <option value="Femme" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Femme') ? 'selected' : ''; ?>>Femme</option>
                        </select>
                    </div>

                    <input type="email" name="email" placeholder="Email" class="form-input" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <input type="text" name="cin" placeholder="CIN (optionnel)" class="form-input"
                           value="<?php echo isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : ''; ?>">
                    <input type="date" name="date_naissance" placeholder="Date de naissance" class="form-input" required
                           value="<?php echo isset($_POST['date_naissance']) ? htmlspecialchars($_POST['date_naissance']) : ''; ?>">
                    <input type="password" name="password" placeholder="Mot de passe (min 6 caractères)" class="form-input" required>
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="form-input" required>

                    <div class="submit-section">
                        <button type="submit" class="submit-btn">S'INSCRIRE</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Validation du téléphone en temps réel
        document.querySelector('input[name="telephone"]').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });

        // Validation des mots de passe
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
            }
        });
    </script>
</body>
</html>