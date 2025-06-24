<?php
$pdo = new PDO("mysql:host=localhost;dbname=labonew", "root", "");

$message = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $cin = trim($_POST['cin'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';

    // Validation
    if (empty($nom) || empty($prenom) || empty($telephone) || empty($genre) || empty($date_naissance)) {
        $erreur = "Tous les champs obligatoires doivent être remplis.";
    } elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $erreur = "Le téléphone doit contenir exactement 10 chiffres.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Format d'email invalide.";
    } else {
        try {
            // Vérifier si l'email existe déjà (si fourni)
            if (!empty($email)) {
                $stmt = $pdo->prepare("SELECT id_patient FROM patient WHERE patient_email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $erreur = "Cet email est déjà utilisé.";
                }
            }

            if (empty($erreur)) {
                // Insérer le nouveau patient
                $stmt = $pdo->prepare("
                    INSERT INTO patient (
                        patient_nom, patient_prenom, patient_cin, patient_telephone, 
                        patient_email, patient_genre, patient_date_naissance, 
                        patient_date_inscription, patient_password
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)
                ");

                $default_password = password_hash('123456', PASSWORD_DEFAULT);
                
                $stmt->execute([
                    $nom, $prenom, $cin, $telephone, $email, 
                    $genre, $date_naissance, $default_password
                ]);

                $message = "Patient ajouté avec succès !";
                
                // Redirection après 2 secondes
                header("refresh:2;url=caissier_patient.php");
            }
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'ajout du patient.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h2 {
            color: #2D6486;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #2D6486;
        }
        
        .required {
            color: red;
        }
        
        .btn {
            background-color: #2D6486;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .btn:hover {
            background-color: #1e4a66;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un nouveau patient</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($erreur)): ?>
            <div class="message error"><?php echo htmlspecialchars($erreur); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" required 
                           value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom <span class="required">*</span></label>
                    <input type="text" id="prenom" name="prenom" required
                           value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="cin">CIN</label>
                    <input type="text" id="cin" name="cin"
                           value="<?php echo isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone <span class="required">*</span></label>
                    <input type="tel" id="telephone" name="telephone" required 
                           pattern="[0-9]{10}" maxlength="10"
                           value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="genre">Genre <span class="required">*</span></label>
                    <select id="genre" name="genre" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Homme" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                        <option value="Femme" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Femme') ? 'selected' : ''; ?>>Femme</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_naissance">Date de naissance <span class="required">*</span></label>
                    <input type="date" id="date_naissance" name="date_naissance" required
                           value="<?php echo isset($_POST['date_naissance']) ? htmlspecialchars($_POST['date_naissance']) : ''; ?>">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn">Ajouter le patient</button>
                <a href="caissier_patient.php" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
    
    <script>
        // Validation du téléphone en temps réel
        document.getElementById('telephone').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    </script>
</body>
</html>