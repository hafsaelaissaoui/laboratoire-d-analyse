<?php
session_start();
include '../../includes/connexion.php';

// Récupération des dossiers avec détails pour facturation
$stmt = $conn->query("
    SELECT 
        d.id_dossier,
        d.date_dossier,
        d.libelle,
        p.patient_nom,
        p.patient_prenom,
        p.patient_telephone,
        COUNT(dt.id_detail) as nb_analyses,
        SUM(dt.prix) as total_prix
    FROM dossier d
    JOIN patient p ON d.id_patient = p.id_patient
    LEFT JOIN details dt ON d.id_dossier = dt.id_dossier
    WHERE d.service_etat = 'en attente'
    GROUP BY d.id_dossier
    ORDER BY d.date_dossier DESC
");
$dossiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// Traitement de la validation d'un dossier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'valider_dossier') {
        $id_dossier = $_POST['id_dossier'] ?? '';
        
        if (!empty($id_dossier)) {
            try {
                $stmt = $conn->prepare("UPDATE dossier SET service_etat = 'validé' WHERE id_dossier = ?");
                $stmt->execute([$id_dossier]);
                $message = "Dossier validé et facturé avec succès.";
                
                // Recharger la page pour actualiser la liste
                header("refresh:2;url=" . $_SERVER['PHP_SELF']);
            } catch (PDOException $e) {
                $message = "Erreur lors de la validation du dossier.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturation - Caissier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            display: flex;
        }

        .sidebar {
            width: 256px;
            background-color: #2d6486;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 24px;
        }

        .sidebar h2 {
            margin-bottom: 32px;
            font-size: 20px;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav li {
            margin-bottom: 16px;
        }

        .sidebar nav a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            display: block;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: #f08c00;
        }

        .main-content {
            margin-left: 256px;
            padding: 32px;
            flex: 1;
        }

        .page-title {
            font-size: 28px;
            color: #2d6486;
            margin-bottom: 32px;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .dossiers-container {
            display: grid;
            gap: 20px;
        }

        .dossier-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .dossier-header {
            background-color: #2d6486;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dossier-title {
            font-size: 18px;
            font-weight: 600;
        }

        .dossier-date {
            font-size: 14px;
            opacity: 0.9;
        }

        .dossier-body {
            padding: 20px;
        }

        .patient-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #f08c00;
        }

        .info-label {
            font-weight: 600;
            color: #2d6486;
            font-size: 14px;
        }

        .info-value {
            color: #333;
            margin-top: 4px;
        }

        .analyses-summary {
            background: #e9ecef;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            font-weight: bold;
            font-size: 18px;
            color: #2d6486;
            border-top: 2px solid #2d6486;
            padding-top: 8px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #2d6486;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e4a66;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .no-dossiers {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .patient-info {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Caissier</h2>
        <nav>
            <ul>
                <li><a href="caissier.php">Accueil</a></li>
                <li><a href="caissier_patient.php">Patients</a></li>
                <li><a href="caissier_analyses.php">Analyses</a></li>
                <li><a href="caissier_factures.php" class="active">Facturation</a></li>
                <li><a href="caissier_tarifs.php">Tarifs</a></li>
                <li><a href="notification.php">Notifications</a></li>
                <li><a href="../docteur/interface.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <h1 class="page-title">Facturation et Validation</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="dossiers-container">
            <?php if (empty($dossiers)): ?>
                <div class="no-dossiers">
                    Aucun dossier en attente de facturation.
                </div>
            <?php else: ?>
                <?php foreach ($dossiers as $dossier): ?>
                    <div class="dossier-card">
                        <div class="dossier-header">
                            <div>
                                <div class="dossier-title">Dossier #<?php echo $dossier['id_dossier']; ?></div>
                                <div class="dossier-date"><?php echo date('d/m/Y H:i', strtotime($dossier['date_dossier'])); ?></div>
                            </div>
                        </div>

                        <div class="dossier-body">
                            <div class="patient-info">
                                <div class="info-item">
                                    <div class="info-label">Patient</div>
                                    <div class="info-value"><?php echo htmlspecialchars($dossier['patient_nom'] . ' ' . $dossier['patient_prenom']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value"><?php echo htmlspecialchars($dossier['patient_telephone']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Libellé</div>
                                    <div class="info-value"><?php echo htmlspecialchars($dossier['libelle']); ?></div>
                                </div>
                            </div>

                            <div class="analyses-summary">
                                <div class="summary-row">
                                    <span>Nombre d'analyses :</span>
                                    <span><?php echo $dossier['nb_analyses']; ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Total à payer :</span>
                                    <span><?php echo number_format($dossier['total_prix'], 2); ?> DH</span>
                                </div>
                            </div>

                            <div class="actions">
                                <a href="dossier_details.php?id=<?php echo $dossier['id_dossier']; ?>" class="btn btn-info">
                                    Voir détails
                                </a>
                                <a href="imprimer_facture.php?id=<?php echo $dossier['id_dossier']; ?>" class="btn btn-primary" target="_blank">
                                    Imprimer facture
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Confirmer la validation et le paiement de ce dossier ?');">
                                    <input type="hidden" name="action" value="valider_dossier">
                                    <input type="hidden" name="id_dossier" value="<?php echo $dossier['id_dossier']; ?>">
                                    <button type="submit" class="btn btn-success">Valider et encaisser</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>