<?php
// Connexion à la base de données (inchangé)
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
    echo "Échec de la connexion : " . $e->getMessage();
    exit;
}

$message = '';

// Supprimer un patient
if (isset($_GET['delete_patient'])) {
    $id = $_GET['delete_patient'];
    $stmt = $pdo->prepare("DELETE FROM patient WHERE id_patient = ?");
    $stmt->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Modifier un patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_patient') {
    $id = $_POST['id_patient'];
    $stmt = $pdo->prepare("UPDATE patient SET patient_nom = ?, patient_prenom = ?, patient_cin = ?, patient_telephone = ?, patient_genre = ?, patient_date_naissance = ?, patient_email = ? WHERE id_patient = ?");
    $stmt->execute([
        $_POST['nom'], $_POST['prenom'], $_POST['cin'], $_POST['telephone'], $_POST['genre'],
        $_POST['date_naissance'], $_POST['email'], $id
    ]);
    $message = "Patient mis à jour avec succès.";
}

// Ajouter un patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_patient') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $telephone = $_POST['telephone'];
    $genre = $_POST['genre'];
    $date_naissance = $_POST['date_naissance'];
    $email = $_POST['email'] ?? '';
    $date_inscription = date('Y-m-d H:i:s');
    $password = password_hash('default123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO patient (patient_nom, patient_prenom, patient_cin, patient_telephone, patient_genre, patient_date_naissance, patient_date_inscription, patient_email, patient_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $cin, $telephone, $genre, $date_naissance, $date_inscription, $email, $password]);
    $message = 'Patient ajouté avec succès';
}

// Récupérer patients
$stmtPatients = $pdo->query("SELECT * FROM patient ORDER BY patient_nom");
$patients = $stmtPatients->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Gestion des patients</title>
    <style>
        /* Reset & base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2d6486;
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
        }
        .sidebar-header {
            padding: 24px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }
        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }
        .sidebar-nav a {
            display: block;
            padding: 14px 24px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: #f08c00;
            font-weight: 600;
        }
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }
        .sidebar-footer a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 32px;
            flex: 1;
        }
        h1 {
            color: #2d6486;
            margin-bottom: 20px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #2d6486;
            color: white;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .selected {
            background-color: #ffe0b2 !important;
        }

        /* Buttons actions with icons */
        .actions {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .icon-btn {
            background-color: #2d6486;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            padding: 10px 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }
        .icon-btn:disabled {
            background-color: #999;
            cursor: not-allowed;
        }
        .icon-btn:hover:not(:disabled) {
            background-color: #f08c00;
        }
        .icon-btn svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .modal-content h2 {
            margin-bottom: 20px;
            color: #2d6486;
        }
        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .modal-content button {
            background-color: #2d6486;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 16px;
        }
        .modal-content button.cancel-btn {
            background-color: #999;
        }
        /* Message */
        .message {
            margin-bottom: 20px;
            color: green;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">Laboratoire</div>
    <nav class="sidebar-nav">
        <a href="#" class="active">Accueil</a>
        <a href="tarifs.php">Tarifs</a>
        <a href="technicien_caissier.php">Caissier/Technicien</a>
        <a href="statistique.php">Statistique</a>
    </nav>
    <div class="sidebar-footer">
        <a href="#">Déconnexion</a>
    </div>
</div>

<div class="main-content">
    <h1>Gestion des patients</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="actions">
        <!-- Bouton Ajouter avec icône + -->
        <button class="icon-btn" title="Ajouter" onclick="openModalAdd()" aria-label="Ajouter patient">
            <!-- Plus icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/>
            </svg>
        </button>

        <!-- Bouton Modifier avec icône crayon -->
        <button class="icon-btn" id="editBtn" onclick="openModalEdit()" disabled title="Modifier" aria-label="Modifier patient">
            <!-- Pencil icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.004 1.004 0 000-1.42l-2.34-2.34a1.004 1.004 0 00-1.42 0L14.13 4.34l3.75 3.75 2.83-2.83z"/>
            </svg>
        </button>

        <!-- Bouton Supprimer avec icône corbeille -->
        <button class="icon-btn" id="deleteBtn" onclick="confirmDelete()" disabled title="Supprimer" aria-label="Supprimer patient">
            <!-- Trash bin icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M3 6h18v2H3V6zm3 3h12v12a2 2 0 01-2 2H8a2 2 0 01-2-2V9zm5-7h2v2h-2V2z"/>
            </svg>
        </button>
    </div>

    <table id="patientsTable" role="grid" aria-label="Liste des patients">
        <thead>
            <tr>
                <th>Nom</th><th>Prénom</th><th>CIN</th><th>Téléphone</th><th>Email</th><th>Genre</th><th>Date Naissance</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($patients as $patient): ?>
            <tr data-id="<?= $patient['id_patient'] ?>" tabindex="0">
                <td><?= htmlspecialchars($patient['patient_nom']) ?></td>
                <td><?= htmlspecialchars($patient['patient_prenom']) ?></td>
                <td><?= htmlspecialchars($patient['patient_cin']) ?></td>
                <td><?= htmlspecialchars($patient['patient_telephone']) ?></td>
                <td><?= htmlspecialchars($patient['patient_email']) ?></td>
                <td><?= htmlspecialchars($patient['patient_genre']) ?></td>
                <td><?= htmlspecialchars($patient['patient_date_naissance']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Ajouter -->
<div class="modal" id="modalAdd" role="dialog" aria-modal="true" aria-labelledby="modalAddTitle">
    <div class="modal-content">
        <h2 id="modalAddTitle">Ajouter un patient</h2>
        <form method="post">
            <input type="hidden" name="action" value="add_patient">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="cin" placeholder="CIN" required>
            <input type="text" name="telephone" placeholder="Téléphone" required>
            <input type="email" name="email" placeholder="Email (facultatif)">
            <select name="genre" required>
                <option value="">--Genre--</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
            <input type="date" name="date_naissance" required>
            <button type="submit">Ajouter</button>
            <button type="button" class="cancel-btn" onclick="closeModal('modalAdd')">Annuler</button>
        </form>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal" id="modalEdit" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
    <div class="modal-content">
        <h2 id="modalEditTitle">Modifier le patient</h2>
        <form method="post">
            <input type="hidden" name="action" value="edit_patient">
            <input type="hidden" name="id_patient" id="edit_id">
            <input type="text" name="nom" id="edit_nom" required>
            <input type="text" name="prenom" id="edit_prenom" required>
            <input type="text" name="cin" id="edit_cin" required>
            <input type="text" name="telephone" id="edit_telephone" required>
            <input type="email" name="email" id="edit_email">
            <select name="genre" id="edit_genre" required>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
            <input type="date" name="date_naissance" id="edit_date_naissance" required>
            <button type="submit">Mettre à jour</button>
            <button type="button" class="cancel-btn" onclick="closeModal('modalEdit')">Annuler</button>
        </form>
    </div>
</div>

<script>
    let selectedRow = null;
    const editBtn = document.getElementById('editBtn');
    const deleteBtn = document.getElementById('deleteBtn');

    document.querySelectorAll('#patientsTable tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            if(selectedRow) selectedRow.classList.remove('selected');
            selectedRow = row;
            row.classList.add('selected');
            editBtn.disabled = false;
            deleteBtn.disabled = false;
        });
        // Also enable selection via keyboard Enter
        row.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                row.click();
            }
        });
    });

    function openModalAdd() {
        document.getElementById('modalAdd').style.display = 'flex';
    }

    function openModalEdit() {
        if(!selectedRow) return alert("Sélectionnez un patient d'abord.");
        const cells = selectedRow.cells;
        document.getElementById('edit_id').value = selectedRow.dataset.id;
        document.getElementById('edit_nom').value = cells[0].textContent;
        document.getElementById('edit_prenom').value = cells[1].textContent;
        document.getElementById('edit_cin').value = cells[2].textContent;
        document.getElementById('edit_telephone').value = cells[3].textContent;
        document.getElementById('edit_email').value = cells[4].textContent;
        document.getElementById('edit_genre').value = cells[5].textContent;
        document.getElementById('edit_date_naissance').value = cells[6].textContent;
        document.getElementById('modalEdit').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function confirmDelete() {
        if(!selectedRow) return alert("Sélectionnez un patient d'abord.");
        if(confirm("Voulez-vous vraiment supprimer ce patient ?")) {
            const id = selectedRow.dataset.id;
            window.location.href = "?delete_patient=" + id;
        }
    }

    // Fermer modals en cliquant en dehors
    window.onclick = function(event) {
        ['modalAdd', 'modalEdit'].forEach(id => {
            const modal = document.getElementById(id);
            if(event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
