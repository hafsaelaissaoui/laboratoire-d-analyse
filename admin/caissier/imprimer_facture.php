<?php
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
    echo "Dossier non trouvé.";
    exit;
}

$total = array_sum(array_column($details, 'prix'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #<?php echo $dossier['id_dossier']; ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #2d6486;
            padding-bottom: 20px;
        }

        .company-info h1 {
            color: #2d6486;
            margin: 0 0 10px 0;
            font-size: 28px;
        }

        .company-info p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-number {
            font-size: 24px;
            font-weight: bold;
            color: #2d6486;
            margin-bottom: 10px;
        }

        .invoice-date {
            color: #666;
        }

        .patient-section {
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #f08c00;
            color: white;
            padding: 10px 15px;
            margin: 0 0 15px 0;
            font-weight: bold;
        }

        .patient-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
        }

        .patient-details p {
            margin: 8px 0;
        }

        .analyses-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .analyses-table th {
            background-color: #2d6486;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .analyses-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .analyses-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .category-row {
            background-color: #f08c00 !important;
            color: white;
            font-weight: bold;
        }

        .total-section {
            text-align: right;
            margin-top: 30px;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .total-row.final {
            font-size: 20px;
            font-weight: bold;
            color: #2d6486;
            border-top: 2px solid #2d6486;
            padding-top: 10px;
        }

        .total-label {
            width: 150px;
            text-align: right;
            margin-right: 20px;
        }

        .total-value {
            width: 100px;
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }

        .print-button {
            background-color: #2d6486;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background-color: #1e4a66;
        }

        .status-paid {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-button" onclick="window.print()">🖨️ Imprimer la facture</button>
    </div>

    <div class="invoice">
        <div class="invoice-header">
            <div class="company-info">
                <h1>Laboratoire Chark</h1>
                <p>Laboratoire d'Analyses Médicales</p>
                <p>19 rue Beni Marin, résid. Walim</p>
                <p>av. Mohammed V, Guercif - Maroc</p>
                <p>Tél: +(212) 539 954 000</p>
                <p>Email: labochark@gmail.com</p>
            </div>
            <div class="invoice-details">
                <div class="invoice-number">Facture #<?php echo str_pad($dossier['id_dossier'], 6, '0', STR_PAD_LEFT); ?></div>
                <div class="invoice-date">Date: <?php echo date('d/m/Y', strtotime($dossier['date_dossier'])); ?></div>
                <div class="invoice-date">Heure: <?php echo date('H:i', strtotime($dossier['date_dossier'])); ?></div>
                <?php if ($dossier['service_etat'] === 'validé'): ?>
                    <div class="status-paid">PAYÉ</div>
                <?php else: ?>
                    <div class="status-pending">EN ATTENTE</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="patient-section">
            <h3 class="section-title">INFORMATIONS PATIENT</h3>
            <div class="patient-details">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($dossier['patient_nom'] . ' ' . $dossier['patient_prenom']); ?></p>
                <?php if (!empty($dossier['patient_cin'])): ?>
                    <p><strong>CIN:</strong> <?php echo htmlspecialchars($dossier['patient_cin']); ?></p>
                <?php endif; ?>
                <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($dossier['patient_telephone']); ?></p>
                <?php if (!empty($dossier['patient_email'])): ?>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($dossier['patient_email']); ?></p>
                <?php endif; ?>
                <p><strong>Type de service:</strong> <?php echo ucfirst($dossier['type_service']); ?></p>
            </div>
        </div>

        <div class="analyses-section">
            <h3 class="section-title">DÉTAIL DES ANALYSES</h3>
            
            <?php if (!empty($details)): ?>
                <table class="analyses-table">
                    <thead>
                        <tr>
                            <th>Analyse</th>
                            <th>Catégorie</th>
                            <th>Durée</th>
                            <th style="text-align: right;">Prix (DH)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $current_category = '';
                        foreach ($details as $detail): 
                            if ($detail['nom_categorie'] !== $current_category):
                                $current_category = $detail['nom_categorie'];
                        ?>
                            <tr class="category-row">
                                <td colspan="4"><?php echo htmlspecialchars($current_category); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['nom_analyse']); ?></td>
                            <td><?php echo htmlspecialchars($detail['nom_categorie']); ?></td>
                            <td><?php echo htmlspecialchars($detail['dure_analyse']); ?></td>
                            <td style="text-align: right;"><?php echo number_format($detail['prix'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Sous-total:</div>
                <div class="total-value"><?php echo number_format($total, 2); ?> DH</div>
            </div>
            <div class="total-row">
                <div class="total-label">TVA (0%):</div>
                <div class="total-value">0.00 DH</div>
            </div>
            <div class="total-row final">
                <div class="total-label">TOTAL:</div>
                <div class="total-value"><?php echo number_format($total, 2); ?> DH</div>
            </div>
        </div>

        <div class="footer">
            <p>Merci de votre confiance</p>
            <p>Cette facture est générée automatiquement par le système de gestion du laboratoire</p>
            <p>Pour toute question, contactez-nous au +(212) 539 954 000</p>
        </div>
    </div>

    <script>
        // Auto-print si demandé
        if (window.location.search.includes('auto_print=1')) {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>