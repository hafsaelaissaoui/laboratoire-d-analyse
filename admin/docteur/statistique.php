<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=labonew;charset=utf8", "root", "");

// Traitement du filtre de date
$dateCondition = "";
$params = [];

if (!empty($_GET['date_debut']) && !empty($_GET['date_fin'])) {
    $dateCondition = "WHERE d.id_dossier IN (
        SELECT id_dossier FROM dossier 
        WHERE date_dossier BETWEEN :date_debut AND :date_fin
    )";
   $params[':date_debut'] = $_GET['date_debut'] . " 00:00:00";
$params[':date_fin']   = $_GET['date_fin'] . " 23:59:59";

}

// Requête pour récupérer les statistiques par analyse
$sql = "
    SELECT 
        s.nom_analyse,
        COUNT(d.id_detail) AS nombre,
        SUM(d.prix) AS total
    FROM details d
    JOIN services s ON d.id_service = s.id_service
    $dateCondition
    GROUP BY s.nom_analyse
    ORDER BY total DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$analyses = $stmt->fetchAll();

// Requête pour total patients et total montant
$sqlTotal = "
    SELECT 
        COUNT(DISTINCT d.id_patient) AS total_patients,
        SUM(d.prix) AS total_montant
    FROM details d
    $dateCondition
";
$stmt2 = $pdo->prepare($sqlTotal);
$stmt2->execute($params);
$totaux = $stmt2->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Médical - Statistiques</title>
  <style>
    <style>
    /* === Styles identiques à ceux fournis === */
    * {margin: 0;padding: 0;box-sizing: border-box;}
    body {font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;background-color: #ffffff;min-height: 100vh;}
    .dashboard-container {display: flex;min-height: 100vh;}
    .sidebar {width: 256px;background-color: #2d6486;color: white;padding: 24px;display: flex;flex-direction: column;}
    .sidebar h1 {font-size: 20px;font-weight: 600;margin-bottom: 32px;}
    .nav-menu {display: flex;flex-direction: column;gap: 16px;}
    .nav-item {color: white;padding: 8px 0;cursor: pointer;transition: color 0.2s;}
    .nav-item:hover {color: #d1d5db;}
    .nav-item.active {color: #f08c00;font-weight: 500;}
    .nav-bottom {margin-top: auto;padding-top: 80px;}
    .main-content {flex: 1;position: relative;}
    .background-overlay {position: absolute;inset: 0;background-image: url('../../photos/background.jpg');background-size: cover;background-position: center;opacity: 0.2;z-index: 1;}
    .content-wrapper {position: relative;z-index: 10;padding: 32px;}
    .page-title {font-size: 36px;font-weight: bold;color: #eb1619;margin-bottom: 32px;}
    .search-section {margin-bottom: 32px;}
    .search-row {display: flex;gap: 16px;margin-bottom: 16px;}
    .search-input {flex: 1;padding: 12px 16px;border: 1px solid #d1d5db;border-radius: 6px;background-color: white;font-size: 14px;}
    .search-input::placeholder {color: #9ca3af;}
    .btn-primary {background-color: #16a245;color: white;border: none;padding: 12px 32px;border-radius: 6px;font-weight: 500;cursor: pointer;transition: background-color 0.2s;}
    .btn-primary:hover {background-color: #15803d;}
    .filter-row {display: flex;gap: 16px;align-items: end;}
    .date-group {display: flex;flex-direction: column;gap: 8px;}
    .date-label {font-size: 14px;font-weight: 500;color: #374151;}
    .date-input {padding: 12px 16px;border: 1px solid #d1d5db;border-radius: 6px;background-color: white;font-size: 14px;width: 150px;}
    .table-card {background-color: rgba(255, 255, 255, 0.95);backdrop-filter: blur(4px);border-radius: 8px;margin-bottom: 24px;overflow: hidden;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);}
    .data-table {width: 100%;border-collapse: collapse;}
    .table-header {background-color: #2d6486;}
    .table-header th {color: white;font-weight: 600;text-align: center;padding: 16px;font-size: 16px;}
    .table-body tr {border-bottom: 1px solid #e5e7eb;}
    .table-body tr:hover {background-color: #f9fafb;}
    .table-body td {padding: 12px 16px;}
    .table-body td:nth-child(2), .table-body td:nth-child(3) {text-align: center;}
    .amount-cell {color: #eb1619;font-weight: 500;}
    .summary-bar {background-color: #16a245;color: white;padding: 16px;border-radius: 8px;display: flex;justify-content: space-between;align-items: center;font-weight: 600;}
    @media (max-width: 768px) {
      .dashboard-container {flex-direction: column;}
      .sidebar {width: 100%;padding: 16px;}
      .nav-menu {flex-direction: row;flex-wrap: wrap;gap: 12px;}
      .content-wrapper {padding: 16px;}
      .page-title {font-size: 28px;}
      .search-row, .filter-row {flex-direction: column;align-items: stretch;}
      .summary-bar {flex-direction: column;gap: 8px;text-align: center;}
      .table-card {overflow-x: auto;}
    }
  </style>
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
<div class="sidebar">
  <h1>Bonjour, Docteur</h1>
  <nav class="nav-menu">
    <a href="accueil.php" class="nav-item">Accueil</a>
    <a href="tarifs.php" class="nav-item">tarifs</a>
    <a href="technicien_caissier.php" class="nav-item">Caissier/technicien</a>
    <a href="statistique.php" class="nav-item active">Statistique</a>
  </nav>
  <div class="nav-bottom">
  <a href="interface.php" class="nav-item" onclick="return confirmLogout()">Déconnexion</a>
</div>

<script>
  function confirmLogout() {
    return confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
  }
</script>

</div>


    <!-- Main Content -->
    <div class="main-content">
      <div class="background-overlay"></div>
      <div class="content-wrapper">
        <h1 class="page-title">Statistique</h1>

        <!-- Search and Filter Section -->
        <div class="search-section">
          <div class="search-row">
            <input type="text" class="search-input" placeholder="Nom analyse">
            <button class="btn-primary">Recherche</button>
          </div>
          <form method="GET" class="filter-row">
            <div class="date-group">
              <label class="date-label">Date de début</label>
              <input type="date" class="date-input" name="date_debut" value="<?= isset($_GET['date_debut']) ? $_GET['date_debut'] : '' ?>">
            </div>
            <div class="date-group">
              <label class="date-label">Date de fin</label>
              <input type="date" class="date-input" name="date_fin" value="<?= isset($_GET['date_fin']) ? $_GET['date_fin'] : '' ?>">
            </div>
            <button type="submit" class="btn-primary">Filtrer</button>
          </form>
        </div>

        <!-- Data Table -->
        <div class="table-card">
          <table class="data-table">
            <thead class="table-header">
              <tr>
                <th>Analyse</th>
                <th>Nombre</th>
                <th>Montant Total (DH)</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <?php foreach ($analyses as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nom_analyse']) ?></td>
                  <td><?= $row['nombre'] ?></td>
                  <td class="amount-cell"><?= number_format($row['total'], 2) ?> DH</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Summary Bar -->
        <div class="summary-bar">
          <span>Total des Patients : <?= $totaux['total_patients'] ?></span>
          <span>Montant Total : <?= number_format($totaux['total_montant'], 2) ?> DH</span>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Recherche dynamique dans le tableau
    document.querySelector('.search-input').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.table-body tr');
      rows.forEach(row => {
        const content = row.textContent.toLowerCase();
        row.style.display = content.includes(searchTerm) ? '' : 'none';
      });
    });

    // Navigation active
    document.querySelectorAll('.nav-item').forEach(item => {
      item.addEventListener('click', function () {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>
</body>
</html>


