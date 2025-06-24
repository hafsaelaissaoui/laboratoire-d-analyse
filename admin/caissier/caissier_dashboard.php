<?php
session_start();
include '../../includes/connexion.php';

// Statistiques pour le tableau de bord
try {
    // Nombre total de patients
    $stmt = $conn->query("SELECT COUNT(*) as total FROM patient");
    $total_patients = $stmt->fetch()['total'];

    // Nombre de dossiers en attente
    $stmt = $conn->query("SELECT COUNT(*) as total FROM dossier WHERE service_etat = 'en attente'");
    $dossiers_attente = $stmt->fetch()['total'];

    // Nombre d'analyses aujourd'hui
    $stmt = $conn->query("SELECT COUNT(*) as total FROM dossier WHERE DATE(date_dossier) = CURDATE()");
    $analyses_aujourd_hui = $stmt->fetch()['total'];

    // Chiffre d'affaires du mois
    $stmt = $conn->query("
        SELECT COALESCE(SUM(dt.prix), 0) as total 
        FROM details dt 
        JOIN dossier d ON dt.id_dossier = d.id_dossier 
        WHERE MONTH(d.date_dossier) = MONTH(CURDATE()) 
        AND YEAR(d.date_dossier) = YEAR(CURDATE())
        AND d.service_etat = 'validé'
    ");
    $ca_mois = $stmt->fetch()['total'];

    // Derniers dossiers
    $stmt = $conn->query("
        SELECT d.*, p.patient_nom, p.patient_prenom 
        FROM dossier d 
        JOIN patient p ON d.id_patient = p.id_patient 
        ORDER BY d.date_dossier DESC 
        LIMIT 5
    ");
    $derniers_dossiers = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Caissier</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #f08c00;
        }

        .stat-card.primary {
            border-left-color: #2d6486;
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-card.info {
            border-left-color: #17a2b8;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #2d6486;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .recent-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-header {
            background-color: #2d6486;
            color: white;
            padding: 16px 24px;
            font-size: 18px;
            font-weight: 600;
        }

        .dossier-list {
            padding: 0;
        }

        .dossier-item {
            padding: 16px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dossier-item:last-child {
            border-bottom: none;
        }

        .dossier-item:hover {
            background-color: #f8f9fa;
        }

        .dossier-info h4 {
            margin: 0 0 4px 0;
            color: #2d6486;
        }

        .dossier-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
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

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .action-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .action-card:hover {
            transform: translateY(-2px);
        }

        .action-card a {
            text-decoration: none;
            color: #2d6486;
            font-weight: 600;
        }

        .action-icon {
            font-size: 48px;
            margin-bottom: 12px;
            color: #f08c00;
        }

        .welcome-message {
            background: linear-gradient(135deg, #2d6486 0%, #6a9ab0 100%);
            color: white;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 32px;
        }

        .welcome-message h2 {
            margin-bottom: 8px;
        }

        .welcome-message p {
            opacity: 0.9;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Caissier</h2>
        <nav>
            <ul>
                <li><a href="caissier_dashboard.php" class="active">Tableau de bord</a></li>
                <li><a href="caissier_patient.php">Patients</a></li>
                <li><a href="caissier_analyses.php">Analyses</a></li>
                <li><a href="caissier_factures.php">Facturation</a></li>
                <li><a href="caissier_tarifs.php">Tarifs</a></li>
                <li><a href="notification.php">Notifications</a></li>
                <li><a href="../docteur/interface.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="welcome-message">
            <h2>Bienvenue dans votre espace caissier</h2>
            <p>Gérez les patients, les analyses et la facturation du laboratoire</p>
        </div>

        <h1 class="page-title">Tableau de Bord</h1>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-number"><?php echo number_format($total_patients); ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-number"><?php echo number_format($dossiers_attente); ?></div>
                <div class="stat-label">Dossiers en attente</div>
            </div>
            <div class="stat-card info">
                <div class="stat-number"><?php echo number_format($analyses_aujourd_hui); ?></div>
                <div class="stat-label">Analyses aujourd'hui</div>
            </div>
            <div class="stat-card success">
                <div class="stat-number"><?php echo number_format($ca_mois, 0); ?> DH</div>
                <div class="stat-label">CA du mois</div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <div class="action-card">
                <div class="action-icon">👤</div>
                <a href="caissier_patient.php">Gérer les patients</a>
            </div>
            <div class="action-card">
                <div class="action-icon">🧪</div>
                <a href="caissier_analyses.php">Ajouter des analyses</a>
            </div>
            <div class="action-card">
                <div class="action-icon">💰</div>
                <a href="caissier_factures.php">Facturation</a>
            </div>
            <div class="action-card">
                <div class="action-icon">📋</div>
                <a href="caissier_tarifs.php">Consulter tarifs</a>
            </div>
        </div>

        <!-- Derniers dossiers -->
        <div class="recent-section">
            <div class="section-header">Derniers Dossiers</div>
            <div class="dossier-list">
                <?php if (empty($derniers_dossiers)): ?>
                    <div class="dossier-item">
                        <p style="text-align: center; color: #666; font-style: italic;">Aucun dossier récent</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($derniers_dossiers as $dossier): ?>
                        <div class="dossier-item">
                            <div class="dossier-info">
                                <h4><?php echo htmlspecialchars($dossier['patient_nom'] . ' ' . $dossier['patient_prenom']); ?></h4>
                                <p><?php echo htmlspecialchars($dossier['libelle']); ?> - <?php echo date('d/m/Y H:i', strtotime($dossier['date_dossier'])); ?></p>
                            </div>
                            <div class="status-badge status-<?php echo str_replace(' ', '-', $dossier['service_etat']); ?>">
                                <?php echo ucfirst($dossier['service_etat']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>