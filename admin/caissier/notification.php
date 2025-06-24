<?php
$pdo = new PDO("mysql:host=localhost;dbname=labonew;charset=utf8", "root", "");

// Récupération des demandes à domicile en attente
$stmt = $pdo->prepare("
  SELECT d.*, p.patient_nom, p.patient_prenom, p.patient_telephone, p.patient_email
  FROM dossier d
  JOIN patient p ON d.id_patient = p.id_patient
  WHERE d.type_service = 'domicile' AND d.service_etat = 'en attente'
  ORDER BY d.date_dossier DESC
");
$stmt->execute();
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Notifications - Services à domicile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }
    .notification {
      background: white;
      padding: 15px;
      border-left: 5px solid #F08C00;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .notification h3 {
      margin: 0;
      color: #2D6486;
    }
    .notification a {
      color: #F08C00;
      text-decoration: underline;
    }
    .notification img {
      max-width: 200px;
      margin-top: 10px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

<h2>🔔 Notifications - Demandes à domicile</h2>

<?php if (count($demandes) > 0): ?>
  <?php foreach ($demandes as $d): ?>
    <div class="notification">
      <h3>Demande de <?= htmlspecialchars($d['patient_nom'] . ' ' . $d['patient_prenom']) ?></h3>
      <p>
        📅 Service demandé le : <strong><?= htmlspecialchars($d['service_date_demande']) ?></strong><br>
        📧 Email : <?= htmlspecialchars($d['patient_email']) ?><br>
        📞 Téléphone : <?= htmlspecialchars($d['patient_telephone']) ?><br>
        👤 Infirmier souhaité : <?= htmlspecialchars($d['service_genre_infirmier']) ?>
      </p>
      <?php if (!empty($d['ordonnance_path'])): ?>
        <p>
          🧾 Ordonnance : <a href="<?= htmlspecialchars($d['ordonnance_path']) ?>" target="_blank">Voir le fichier</a><br>
          <?php
          $ext = pathinfo($d['ordonnance_path'], PATHINFO_EXTENSION);
          if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
            <img src="<?= htmlspecialchars($d['ordonnance_path']) ?>" alt="Ordonnance">
          <?php endif; ?>
        </p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>Aucune demande de service à domicile en attente.</p>
<?php endif; ?>

</body>
</html>
