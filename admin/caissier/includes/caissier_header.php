<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=labonew;charset=utf8", "root", "");

// Récupérer le nombre de notifications non lues (demandes à domicile en attente)
$req = $pdo->prepare("SELECT COUNT(*) FROM dossier WHERE type_service = 'domicile' AND service_etat = 'en attente'");
$req->execute();
$notification_count = $req->fetchColumn();
?>

<!-- Menu latéral / header -->
<div class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <li><a href="caissier.php">Accueil</a></li>
            <li><a href="caissier_tarifs.php">Tarifs</a></li>
            <li>
                <a href="notification.php">
                    Notifications
                    <?php if ($notification_count > 0): ?>
                        <span style="background:red; color:white; padding:2px 6px; border-radius:50%;">
                            <?= $notification_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
    </nav>
</div>
