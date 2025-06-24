<?php
session_start();
include '../includes/connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['patient_id'])) {
    header("Location: compte.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

// Récupérer les informations du patient
try {
    $stmt = $conn->prepare("SELECT * FROM patient WHERE id_patient = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les dossiers du patient
    $stmt_dossiers = $conn->prepare("
        SELECT d.*, COUNT(dt.id_detail) as nb_analyses 
        FROM dossier d 
        LEFT JOIN details dt ON d.id_dossier = dt.id_dossier 
        WHERE d.id_patient = ? 
        GROUP BY d.id_dossier 
        ORDER BY d.date_dossier DESC
    ");
    $stmt_dossiers->execute([$patient_id]);
    $dossiers = $stmt_dossiers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Laboratoire Chark</title>
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
            background-color: var(--grey-light);
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            color: var(--blue-dark);
            margin-bottom: 1rem;
        }

        .patient-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-card {
            background: var(--grey-light);
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid var(--orange);
        }

        .info-card strong {
            color: var(--blue-dark);
        }

        .dossiers-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dossiers-section h2 {
            color: var(--blue-dark);
            margin-bottom: 1.5rem;
        }

        .dossier-card {
            background: var(--grey-light);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--blue-light);
        }

        .dossier-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .dossier-title {
            font-weight: bold;
            color: var(--blue-dark);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: bold;
        }

        .status-en-attente {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-valide {
            background-color: #d4edda;
            color: #155724;
        }

        .status-refuse {
            background-color: #f8d7da;
            color: #721c24;
        }

        .dossier-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .logout-btn {
            background-color: var(--orange);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #d97600;
        }

        .no-dossiers {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Laboratoire Chark</div>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="resultat.php">Résultat</a></li>
            <li><a href="domicile.php">Services à domicile</a></li>
            <li><a href="dashboard.php" class="active">Mon Compte</a></li>
            <li><a href="logout.php" class="logout-btn">Déconnexion</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <h1>Bienvenue, <?php echo htmlspecialchars($patient['patient_prenom'] . ' ' . $patient['patient_nom']); ?></h1>
            <p>Voici vos informations personnelles et l'historique de vos analyses.</p>
            
            <div class="patient-info">
                <div class="info-card">
                    <strong>Email :</strong><br>
                    <?php echo htmlspecialchars($patient['patient_email']); ?>
                </div>
                <div class="info-card">
                    <strong>Téléphone :</strong><br>
                    <?php echo htmlspecialchars($patient['patient_telephone']); ?>
                </div>
                <div class="info-card">
                    <strong>Date de naissance :</strong><br>
                    <?php echo htmlspecialchars($patient['patient_date_naissance']); ?>
                </div>
                <div class="info-card">
                    <strong>Genre :</strong><br>
                    <?php echo htmlspecialchars($patient['patient_genre']); ?>
                </div>
            </div>
        </div>

        <div class="dossiers-section">
            <h2>Mes Dossiers Médicaux</h2>
            
            <?php if (empty($dossiers)): ?>
                <div class="no-dossiers">
                    Aucun dossier médical trouvé. Contactez le laboratoire pour plus d'informations.
                </div>
            <?php else: ?>
                <?php foreach ($dossiers as $dossier): ?>
                    <div class="dossier-card">
                        <div class="dossier-header">
                            <div class="dossier-title"><?php echo htmlspecialchars($dossier['libelle']); ?></div>
                            <div class="status-badge status-<?php echo str_replace(' ', '-', $dossier['service_etat']); ?>">
                                <?php echo ucfirst($dossier['service_etat']); ?>
                            </div>
                        </div>
                        
                        <div class="dossier-details">
                            <div>
                                <strong>Date :</strong><br>
                                <?php echo date('d/m/Y H:i', strtotime($dossier['date_dossier'])); ?>
                            </div>
                            <div>
                                <strong>Type de service :</strong><br>
                                <?php echo ucfirst($dossier['type_service']); ?>
                            </div>
                            <div>
                                <strong>Nombre d'analyses :</strong><br>
                                <?php echo $dossier['nb_analyses']; ?>
                            </div>
                            <?php if ($dossier['type_service'] == 'domicile'): ?>
                                <div>
                                    <strong>Date demandée :</strong><br>
                                    <?php echo date('d/m/Y H:i', strtotime($dossier['service_date_demande'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>