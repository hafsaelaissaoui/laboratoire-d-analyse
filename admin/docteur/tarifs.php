<?php
// tarifs.php

// Connexion BDD
$host = 'localhost';
$db = 'labonew';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit("Erreur BDD : " . $e->getMessage());
}

// Gérer AJAX requests (add, edit, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $nom = $_POST['nom_analyse'] ?? '';
        $prix = $_POST['prix_analyse'] ?? 0;
        $duree = $_POST['dure_analyse'] ?? '';
        $categorie_nom = trim($_POST['categorie']) ?? '';

        // Vérifier si la catégorie existe déjà
        $stmtCat = $pdo->prepare("SELECT id_categorie FROM categorie WHERE nom_categorie = ?");
        $stmtCat->execute([$categorie_nom]);
        $cat = $stmtCat->fetch();

        if ($cat) {
            $id_categorie = $cat['id_categorie'];
        } else {
            // Ajouter la nouvelle catégorie
            $stmtInsertCat = $pdo->prepare("INSERT INTO categorie (nom_categorie) VALUES (?)");
            $stmtInsertCat->execute([$categorie_nom]);
            $id_categorie = $pdo->lastInsertId();
        }

        // Insérer le nouveau service
        $stmt = $pdo->prepare("INSERT INTO services (nom_analyse, prix_analyse, dure_analyse, id_categorie) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prix, $duree, $id_categorie]);

        echo json_encode(['status' => 'success', 'message' => 'Analyse ajoutée']);
        exit;
    } elseif ($action === 'edit') {
        $id = $_POST['id_service'] ?? 0;
        $nom = $_POST['nom_analyse'] ?? '';
        $prix = $_POST['prix_analyse'] ?? 0;
        $duree = $_POST['dure_analyse'] ?? '';
        $categorie_nom = trim($_POST['categorie']) ?? '';

        // Vérifier si la catégorie existe déjà
        $stmtCat = $pdo->prepare("SELECT id_categorie FROM categorie WHERE nom_categorie = ?");
        $stmtCat->execute([$categorie_nom]);
        $cat = $stmtCat->fetch();

        if ($cat) {
            $id_categorie = $cat['id_categorie'];
        } else {
            // Ajouter la nouvelle catégorie
            $stmtInsertCat = $pdo->prepare("INSERT INTO categorie (nom_categorie) VALUES (?)");
            $stmtInsertCat->execute([$categorie_nom]);
            $id_categorie = $pdo->lastInsertId();
        }

        // Mettre à jour le service
        $stmt = $pdo->prepare("UPDATE services SET nom_analyse = ?, prix_analyse = ?, dure_analyse = ?, id_categorie = ? WHERE id_service = ?");
        $stmt->execute([$nom, $prix, $duree, $id_categorie, $id]);

        echo json_encode(['status' => 'success', 'message' => 'Analyse modifiée']);
        exit;
    } elseif ($action === 'delete') {
        $id = $_POST['id_service'] ?? 0;

        $stmt = $pdo->prepare("DELETE FROM services WHERE id_service = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Analyse supprimée']);
        exit;
    }
}

// Récupérer services + catégorie
$stmt = $pdo->query("
    SELECT s.id_service, s.nom_analyse, s.prix_analyse, s.dure_analyse, c.nom_categorie
    FROM services s
    JOIN categorie c ON s.id_categorie = c.id_categorie
    ORDER BY s.id_service ASC
");
$services = $stmt->fetchAll();

// Récupérer toutes les catégories pour le select du modal
$stmtCatAll = $pdo->query("SELECT nom_categorie FROM categorie ORDER BY nom_categorie ASC");
$categories = $stmtCatAll->fetchAll(PDO::FETCH_COLUMN);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des analyses - Dashboard Médical</title>

    <style>
        /* Reset et styles généraux */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #2D6486;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            color: white;
        }
        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-weight: 600;
            transition: background-color 0.3s ease;
            text-transform: capitalize;
        }
        .sidebar ul li a:hover {
            background-color: #255469;
        }
        .sidebar ul li a.active {
            background-color: #1b3f54;
        }
        .sidebar .sidebar-header {
            padding: 0 20px 20px 20px;
            font-size: 1.2em;
            font-weight: bold;
        }

        /* Main content */
        .main-content {
            margin-left: 220px;
            padding: 32px;
            flex: 1;
            position: relative;
        }
        .background-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('/../../photos/background.jpg');
            background-size: cover; background-position: center;
            opacity: 0.2;
            z-index: 1;
        }
        .content-wrapper {
            position: relative;
            z-index: 2;
        }
        .page-title {
            font-size: 30px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 32px;
        }

        /* Search Bar */
        .search-container {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }
        .search-input-wrapper {
            flex: 1;
            max-width: 384px;
        }
        .search-input {
            width: 100%;
            padding: 8px 16px;
            border: 1px solid #d9d9d9;
            border-radius: 6px;
            font-size: 14px;
        }
        .search-button {
            background-color: #16a245;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .search-button:hover {
            background-color: #138a3a;
        }

        /* Action Icons */
        .action-icons {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        .action-icon {
            width: 40px;
            height: 40px;
            background-color: #f08c00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
            border: none;
        }
        .action-icon:hover {
            background-color: #e07a00;
        }
        .action-icon:disabled {
            background-color: #cccccc;
            cursor: default;
        }
        .action-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #d9d9d9;
            overflow-x: auto;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        .table-header {
            background-color: #2d6486;
        }
        .table-header th {
            color: white;
            font-weight: 600;
            padding: 16px 24px;
            text-align: left;
            white-space: nowrap;
        }
        .table-body tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table-body tr:nth-child(odd) {
            background-color: white;
        }
        .table-body td {
            padding: 16px 24px;
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
        }
        .table-body tr:first-child td:first-child {
            font-weight: 500;
        }
        .table-body tr:hover {
    background-color: #f0c06f50; /* Couleur claire au survol (hover) */
    cursor: pointer; /* Indique que c’est cliquable */
}

.table-body tr.selected {
    background-color: #f08c0050 !important; /* Couleur plus visible pour la sélection */
}


        /* Modal */
        #modalAnalyse {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #modalAnalyse .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
            box-sizing: border-box;
        }
        #modalAnalyse h2 {
            margin-bottom: 20px;
            font-weight: bold;
        }
        #modalAnalyse form > div {
            margin-bottom: 15px;
        }
        #modalAnalyse label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        #modalAnalyse input[type="text"],
        #modalAnalyse input[type="number"],
        #modalAnalyse select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        #modalAnalyse button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        #modalAnalyse .btn-cancel {
            background: #999;
            color: white;
            margin-right: 10px;
        }
        #modalAnalyse .btn-submit {
            background: #f08c00;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 10px 0;
            }
            .sidebar ul li {
                display: inline-block;
                margin-right: 10px;
            }
            .main-content {
                margin-left: 0;
                padding: 16px;
            }
            .data-table {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-header">Bonjour, Docteur</div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="tarifs.php" class="active">Tarifs</a></li>
        <li><a href="technicien_caissier.php">Caissier/technicien</a></li>
        <li><a href="statistique.php">Statistique</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <div class="background-overlay"></div>
    <div class="content-wrapper">
        <h1 class="page-title">Gestion des analyses</h1>

        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <input type="text" class="search-input" placeholder="Nom de l'analyse">
            </div>
            <button class="search-button">Recherche</button>
        </div>

        <!-- Action Icons -->
        <div class="action-icons">
            <button id="btnAdd" class="action-icon" title="Ajouter">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
            <button id="btnEdit" class="action-icon" title="Modifier" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m18 2 4 4-14 14H4v-4L18 2z"></path>
                </svg>
            </button>
            <button id="btnDelete" class="action-icon" title="Supprimer" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3,6 5,6 21,6"></polyline>
                    <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            <table class="data-table" id="tableServices">
                <thead class="table-header">
                    <tr>
                        <th>ID</th>
                        <th>Analyse</th>
                        <th>Prix</th>
                        <th>Durée</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    <?php if (count($services) > 0): ?>
                        <?php foreach ($services as $service): ?>
                        <tr data-id="<?= htmlspecialchars($service['id_service']) ?>">
                            <td><?= htmlspecialchars($service['id_service']) ?></td>
                            <td><?= htmlspecialchars($service['nom_analyse']) ?></td>
                            <td><?= htmlspecialchars($service['prix_analyse']) ?> DH</td>
                            <td><?= htmlspecialchars($service['dure_analyse']) ?></td>
                            <td><?= htmlspecialchars($service['nom_categorie']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Aucune analyse disponible</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter / Modifier -->
<div id="modalAnalyse" style="display:none; align-items:center; justify-content:center;">
    <div class="modal-content">
        <h2 id="modalTitle">Ajouter une analyse</h2>
        <form id="formAnalyse">
            <input type="hidden" id="id_service" name="id_service" value="">
            <div>
                <label for="nom_analyse">Nom de l'analyse</label>
                <input type="text" id="nom_analyse" name="nom_analyse" required>
            </div>
            <div>
                <label for="prix_analyse">Prix (DH)</label>
                <input type="number" id="prix_analyse" name="prix_analyse" required min="0" step="0.01">
            </div>
            <div>
                <label for="dure_analyse">Durée</label>
                <input type="text" id="dure_analyse" name="dure_analyse" required placeholder="Exemple : 2 jours">
            </div>
            <div>
                <label for="categorie">Catégorie (Type)</label>
                <input list="categories-list" id="categorie" name="categorie" required autocomplete="off" placeholder="Choisir ou saisir une catégorie">
                <datalist id="categories-list">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div style="text-align:right; margin-top: 15px;">
                <button type="button" class="btn-cancel">Annuler</button>
                <button type="submit" class="btn-submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Variables DOM
    const btnAdd = document.getElementById('btnAdd');
    const btnEdit = document.getElementById('btnEdit');
    const btnDelete = document.getElementById('btnDelete');
    const modal = document.getElementById('modalAnalyse');
    const formAnalyse = document.getElementById('formAnalyse');
    const modalTitle = document.getElementById('modalTitle');
    const cancelBtn = modal.querySelector('.btn-cancel');
    const table = document.getElementById('tableServices');
    const searchInput = document.querySelector('.search-input');
    const searchButton = document.querySelector('.search-button');

    let selectedRow = null;

    // Sélection de la ligne dans la table avec gestion de la classe 'selected'
    table.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            if (selectedRow) selectedRow.classList.remove('selected');
            if (selectedRow === row) {
                selectedRow = null;
                btnEdit.disabled = true;
                btnDelete.disabled = true;
            } else {
                selectedRow = row;
                row.classList.add('selected');
                btnEdit.disabled = false;
                btnDelete.disabled = false;
            }
        });
    });

    // Ouvrir modal Ajout
    btnAdd.addEventListener('click', () => {
        modal.style.display = 'flex';
        modalTitle.textContent = 'Ajouter une analyse';
        formAnalyse.reset();
        document.getElementById('id_service').value = '';
    });

    // Ouvrir modal Modifier
    btnEdit.addEventListener('click', () => {
        if (!selectedRow) return;
        modal.style.display = 'flex';
        modalTitle.textContent = 'Modifier une analyse';

        document.getElementById('id_service').value = selectedRow.dataset.id;
        document.getElementById('nom_analyse').value = selectedRow.cells[1].textContent;
        document.getElementById('prix_analyse').value = selectedRow.cells[2].textContent.replace(' DH', '');
        document.getElementById('dure_analyse').value = selectedRow.cells[3].textContent;
        document.getElementById('categorie').value = selectedRow.cells[4].textContent;
    });

    // Fermer modal Annuler
    cancelBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Soumettre formulaire (AJAX)
    formAnalyse.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(formAnalyse);
        const id_service = formData.get('id_service');
        const action = id_service ? 'edit' : 'add';

        formData.append('action', action);

        fetch('tarifs.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              alert(data.message);
              if (data.status === 'success') {
                  location.reload();
              }
          }).catch(err => {
              alert('Erreur lors de la requête');
              console.error(err);
          });
    });

    // Supprimer analyse
    btnDelete.addEventListener('click', () => {
        if (!selectedRow) return;

        if (!confirm('Confirmez-vous la suppression de cette analyse ?')) return;

        const id_service = selectedRow.dataset.id;
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id_service', id_service);

        fetch('tarifs.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              alert(data.message);
              if (data.status === 'success') {
                  location.reload();
              }
          }).catch(err => {
              alert('Erreur lors de la requête');
              console.error(err);
          });
    });
    // Recherche simple (filtre tableau)
    searchButton.addEventListener('click', () => {
        const filter = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const analyse = row.cells[1].textContent.toLowerCase();
            if (analyse.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    // Enter pour lancer la recherche
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') searchButton.click();
    });
</script>
</body>
</html>
