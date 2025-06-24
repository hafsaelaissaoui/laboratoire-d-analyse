<?php
session_start();
include '../../includes/connexion.php';

// Récupération des analyses avec leurs catégories
$stmt = $conn->query("
    SELECT s.*, c.nom_categorie 
    FROM services s 
    JOIN categorie c ON s.id_categorie = c.id_categorie 
    ORDER BY c.nom_categorie, s.nom_analyse
");
$analyses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des patients pour le formulaire
$stmt_patients = $conn->query("SELECT id_patient, patient_nom, patient_prenom FROM patient ORDER BY patient_nom");
$patients = $stmt_patients->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$erreur = '';

// Traitement de l'ajout d'analyses à un patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_analyses') {
    $id_patient = $_POST['id_patient'] ?? '';
    $analyses_selectionnees = $_POST['analyses'] ?? [];
    
    if (empty($id_patient) || empty($analyses_selectionnees)) {
        $erreur = "Veuillez sélectionner un patient et au moins une analyse.";
    } else {
        try {
            $conn->beginTransaction();
            
            // Créer un nouveau dossier
            $stmt = $conn->prepare("
                INSERT INTO dossier (date_dossier, libelle, id_patient, type_service, service_genre_infirmier, service_date_demande, ordonnance_path, service_etat)
                VALUES (NOW(), 'Analyses laboratoire', ?, 'LOCAL', 'infirmière', NOW(), '', 'en attente')
            ");
            $stmt->execute([$id_patient]);
            $id_dossier = $conn->lastInsertId();
            
            // Ajouter les analyses sélectionnées
            $stmt_detail = $conn->prepare("
                INSERT INTO details (id_dossier, id_service, id_patient, prix, resultat)
                VALUES (?, ?, ?, ?, '')
            ");
            
            foreach ($analyses_selectionnees as $id_service) {
                // Récupérer le prix de l'analyse
                $stmt_prix = $conn->prepare("SELECT prix_analyse FROM services WHERE id_service = ?");
                $stmt_prix->execute([$id_service]);
                $prix = $stmt_prix->fetchColumn();
                
                $stmt_detail->execute([$id_dossier, $id_service, $id_patient, $prix]);
            }
            
            $conn->commit();
            $message = "Analyses ajoutées avec succès au dossier du patient.";
            
        } catch (PDOException $e) {
            $conn->rollBack();
            $erreur = "Erreur lors de l'ajout des analyses.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Analyses - Caissier</title>
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
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
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

        .form-section {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 32px;
        }

        .form-section h3 {
            color: #2d6486;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .analyses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .category-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
        }

        .category-title {
            background-color: #2d6486;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .analyse-item {
            display: flex;
            align-items: center;
            padding: 8px;
            background: white;
            border-radius: 4px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
        }

        .analyse-item input[type="checkbox"] {
            margin-right: 12px;
            transform: scale(1.2);
        }

        .analyse-info {
            flex: 1;
        }

        .analyse-name {
            font-weight: 600;
            color: #333;
        }

        .analyse-price {
            color: #f08c00;
            font-weight: 600;
            margin-left: auto;
        }

        .btn {
            background-color: #2d6486;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #1e4a66;
        }

        .btn-orange {
            background-color: #f08c00;
        }

        .btn-orange:hover {
            background-color: #d97600;
        }

        .total-section {
            background: #e9ecef;
            padding: 16px;
            border-radius: 6px;
            margin-top: 20px;
            text-align: right;
        }

        .total-amount {
            font-size: 20px;
            font-weight: bold;
            color: #2d6486;
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
                <li><a href="caissier_analyses.php" class="active">Analyses</a></li>
                <li><a href="caissier_tarifs.php">Tarifs</a></li>
                <li><a href="notification.php">Notifications</a></li>
                <li><a href="../docteur/interface.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <h1 class="page-title">Gestion des Analyses</h1>

        <?php if (!empty($message)): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($erreur)): ?>
            <div class="message error"><?php echo htmlspecialchars($erreur); ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h3>Ajouter des analyses à un patient</h3>
            
            <form method="POST" id="analysesForm">
                <input type="hidden" name="action" value="ajouter_analyses">
                
                <div class="form-group">
                    <label for="id_patient">Sélectionner un patient :</label>
                    <select id="id_patient" name="id_patient" required>
                        <option value="">-- Choisir un patient --</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?php echo $patient['id_patient']; ?>">
                                <?php echo htmlspecialchars($patient['patient_nom'] . ' ' . $patient['patient_prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sélectionner les analyses :</label>
                    <div class="analyses-grid">
                        <?php
                        $current_category = '';
                        foreach ($analyses as $analyse):
                            if ($analyse['nom_categorie'] !== $current_category):
                                if ($current_category !== '') echo '</div>';
                                $current_category = $analyse['nom_categorie'];
                                echo '<div class="category-section">';
                                echo '<div class="category-title">' . htmlspecialchars($current_category) . '</div>';
                            endif;
                        ?>
                            <div class="analyse-item">
                                <input type="checkbox" 
                                       name="analyses[]" 
                                       value="<?php echo $analyse['id_service']; ?>"
                                       data-price="<?php echo $analyse['prix_analyse']; ?>"
                                       id="analyse_<?php echo $analyse['id_service']; ?>">
                                <div class="analyse-info">
                                    <div class="analyse-name"><?php echo htmlspecialchars($analyse['nom_analyse']); ?></div>
                                    <small><?php echo htmlspecialchars($analyse['dure_analyse']); ?></small>
                                </div>
                                <div class="analyse-price"><?php echo number_format($analyse['prix_analyse'], 2); ?> DH</div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-amount">Total : <span id="totalAmount">0.00</span> DH</div>
                </div>

                <div style="text-align: center; margin-top: 24px;">
                    <button type="submit" class="btn btn-orange">Ajouter les analyses</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Calcul du total en temps réel
        function updateTotal() {
            let total = 0;
            const checkboxes = document.querySelectorAll('input[name="analyses[]"]:checked');
            
            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.dataset.price);
            });
            
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        // Ajouter l'événement à tous les checkboxes
        document.querySelectorAll('input[name="analyses[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        // Validation du formulaire
        document.getElementById('analysesForm').addEventListener('submit', function(e) {
            const patient = document.getElementById('id_patient').value;
            const analyses = document.querySelectorAll('input[name="analyses[]"]:checked');
            
            if (!patient) {
                e.preventDefault();
                alert('Veuillez sélectionner un patient.');
                return;
            }
            
            if (analyses.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une analyse.');
                return;
            }
            
            if (!confirm('Confirmer l\'ajout de ' + analyses.length + ' analyse(s) pour ce patient ?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>