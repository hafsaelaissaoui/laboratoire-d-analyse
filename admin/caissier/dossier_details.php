<?php
session_start();
include '../../includes/connexion.php';

$id_dossier = $_GET['id'] ?? '';
$dossier = null;
$details = [];

if (!empty($id_dossier)) {
    // Récupérer les informations du dossier
    $stmt = $conn->prepare("
        SELECT d.*, p.patient_nom, p.patient_prenom, p.patient_telephone, p.patient_email, p.patient_cin
        FROM dossier d
        JOIN patient p ON d.id_patient = p.id_patient
        WHERE d.id_dossier = ?
    ");
    $stmt->execute([$id_dossier]);
    $dossier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dossier) {
        // Récupérer les détails des analyses
        $stmt_details = $conn->prepare("
            SELECT dt.*, s.nom_analyse, s.dure_analyse, c.nom_categorie
            FROM details dt
            JOIN services s ON dt.id_service = s.id_service
            JOIN categorie c ON s.id_categorie = c.id_categorie
            WHERE dt.id_dossier = ?
            ORDER BY c.nom_categorie, s.nom_analyse
        ");
        $stmt_details->execute([$id_dossier]);
        $details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!$dossier) {
    header("Location: caissier_factures.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Dossier #<?php echo $dossier['id_dossier']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background-color: #2d6486;
            color: white;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .header p {
            opacity: 0.9;
        }

        .content {
            padding: 24px;
        }

        .section {
            margin-bottom: 32px;
        }

        .section-title {
            color: #2d6486;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            border-bottom: 2px solid #f08c00;
            padding-bottom: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid #f08c00;
        }

        .info-label {
            font-weight: 600;
            color: #2d6486;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .info-value {
            color: #333;
            font-size: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-en-attente {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-valide {
            background-color: #d4edda;
            color: #155724;
        }

        .analyses-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .analyses-table th,
        .analyses-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .analyses-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2d6486;
        }

        .analyses-table tr:hover {
            background-color: #f8f9fa;
        }

        .category-row {
            background-color: #2d6486 !important;
            color: white;
        }

        .category-row td {
            font-weight: 600;
            border-bottom: 2px solid #1e4a66;
        }

        .total-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 6px;
            text-align: right;
            margin-top: 20px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #2d6486;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 12px 24px;
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

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .ordonnance-section {
            margin-top: 16px;
        }

        .ordonnance-link {
            color: #2d6486;
            text-decoration: none;
            font-weight: 600;
        }

        .ordonnance-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0;
                border-radius: 0;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .analyses-table {
                font-size: 14px;
            }

            .analyses-table th,
            .analyses-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dossier Médical #<?php echo $dossier['id_dossier']; ?></h1>
            <p>Créé le <?php echo date('d/m/Y à H:i', strtotime($dossier['date_dossier'])); ?></p>
        </div>

        <div class="content">
            <!-- Informations du patient -->
            <div class="section">
                <h2 class="section-title">Informations du Patient</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value"><?php echo htmlspecialchars($dossier['patient_nom'] . ' ' . $dossier['patient_prenom']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CIN</div>
                        <div class="info-value"><?php echo htmlspecialchars($dossier['patient_cin'] ?: 'Non renseigné'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value"><?php echo htmlspecialchars($dossier['patient_telephone']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($dossier['patient_email'] ?: 'Non renseigné'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Informations du dossier -->
            <div class="section">
                <h2 class="section-title">Informations du Dossier</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Libellé</div>
                        <div class="info-value"><?php echo htmlspecialchars($dossier['libelle']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Type de service</div>
                        <div class="info-value"><?php echo ucfirst($dossier['type_service']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="status-badge status-<?php echo str_replace(' ', '-', $dossier['service_etat']); ?>">
                                <?php echo ucfirst($dossier['service_etat']); ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($dossier['type_service'] === 'domicile'): ?>
                        <div class="info-item">
                            <div class="info-label">Date demandée</div>
                            <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($dossier['service_date_demande'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Genre infirmier</div>
                            <div class="info-value"><?php echo ucfirst($dossier['service_genre_infirmier']); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($dossier['ordonnance_path'])): ?>
                    <div class="ordonnance-section">
                        <div class="info-label">Ordonnance</div>
                        <a href="../../<?php echo htmlspecialchars($dossier['ordonnance_path']); ?>" 
                           target="_blank" class="ordonnance-link">
                            📄 Voir l'ordonnance
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Liste des analyses -->
            <div class="section">
                <h2 class="section-title">Analyses Demandées</h2>
                
                <?php if (empty($details)): ?>
                    <p style="text-align: center; color: #666; font-style: italic;">Aucune analyse associée à ce dossier.</p>
                <?php else: ?>
                    <table class="analyses-table">
                        <thead>
                            <tr>
                                <th>Analyse</th>
                                <th>Catégorie</th>
                                <th>Durée</th>
                                <th>Prix</th>
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $current_category = '';
                            $total = 0;
                            foreach ($details as $detail): 
                                $total += $detail['prix'];
                                
                                if ($detail['nom_categorie'] !== $current_category):
                                    $current_category = $detail['nom_categorie'];
                            ?>
                                <tr class="category-row">
                                    <td colspan="5"><?php echo htmlspecialchars($current_category); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail['nom_analyse']); ?></td>
                                <td><?php echo htmlspecialchars($detail['nom_categorie']); ?></td>
                                <td><?php echo htmlspecialchars($detail['dure_analyse']); ?></td>
                                <td><?php echo number_format($detail['prix'], 2); ?> DH</td>
                                <td><?php echo !empty($detail['resultat']) ? '✅ Disponible' : '⏳ En attente'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="total-section">
                        <div class="total-amount">Total : <?php echo number_format($total, 2); ?> DH</div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="caissier_factures.php" class="btn btn-secondary">Retour</a>
                <a href="imprimer_facture.php?id=<?php echo $dossier['id_dossier']; ?>" 
                   class="btn btn-primary" target="_blank">Imprimer facture</a>
            </div>
        </div>
    </div>
</body>
</html>