<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'labonew'; // CHANGE ici le nom de ta base
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$message = '';
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement Ajout ou Modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'modifier' && !empty($_POST['id_user'])) {
            // Modifier utilisateur
            $stmt = $pdo->prepare("UPDATE users SET user_nom=?, user_prenom=?, user_cin=?, user_telephone=?, user_email=?, user_date_naissance=?, user_genre=?, username=?, user_password=?, user_status=? WHERE id_user=?");
            $stmt->execute([
                $_POST['user_nom'],
                $_POST['user_prenom'],
                $_POST['user_cin'],
                $_POST['user_telephone'],
                $_POST['user_email'],
                $_POST['user_date_naissance'],
                $_POST['user_genre'],
                $_POST['username'],
                $_POST['user_password'],
                $_POST['user_status'],
                $_POST['id_user']
            ]);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'ajouter') {
            // Ajouter utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (user_nom, user_prenom, user_cin, user_telephone, user_email, user_date_naissance, user_genre, username, user_password, user_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['user_nom'],
                $_POST['user_prenom'],
                $_POST['user_cin'],
                $_POST['user_telephone'],
                $_POST['user_email'],
                $_POST['user_date_naissance'],
                $_POST['user_genre'],
                $_POST['username'],
                $_POST['user_password'],
                $_POST['user_status']
            ]);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer' && !empty($_POST['id_user'])) {
            // Supprimer utilisateur
            $stmt = $pdo->prepare("DELETE FROM users WHERE id_user=?");
            $stmt->execute([$_POST['id_user']]);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Récupérer les users pour affichage
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id_user DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Médical</title>
    <style>
        /* (copie ton CSS original ici) */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 256px;
            background-color: #2d6486;
            color: white;
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            margin-bottom: 32px;
        }

        .logo h1 {
            font-size: 20px;
            font-weight: bold;
        }

        .logo .bonjour {
            color: #ff0000;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .nav-item {
            color: white;
            cursor: pointer;
            padding: 8px 0;
            text-decoration: none;
        }

        .nav-item.active {
            background-color: #f08c00;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .disconnect {
            margin-top: auto;
            padding-top: 32px;
        }

        .main-content {
            flex: 1;
            position: relative;
            padding: 32px;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('../../photos/background.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 10;
        }

        .header {
            margin-bottom: 24px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 16px;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .action-buttons {
            display: flex;
            gap: 16px;
        }

        .btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .btn-add {
            background-color: #22c55e;
        }

        .btn-add:hover {
            background-color: #16a34a;
        }

        .btn-edit {
            background-color: #f08c00;
        }

        .btn-edit:hover {
            background-color: #ea580c;
        }

        .btn-delete {
            background-color: #ff0000;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .search-container {
            position: relative;
            display: flex;
        }

        .search-input {
            width: 256px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px 0 0 4px;
            outline: none;
        }

        .search-btn {
            background-color: #f08c00;
            border: none;
            padding: 8px 12px;
            border-radius: 0 4px 4px 0;
            color: white;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #ea580c;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background-color: #2d6486;
            color: white;
            font-weight: 500;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #d9d9d9;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #d9d9d9;
        }

        .table tr:first-child td {
            font-weight: 500;
        }

        .table tr.selected {
            background-color: #a2d2ff !important;
        }

        .icon {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background: white;
            padding: 20px 30px;
            border-radius: 8px;
            width: 480px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }

        .close {
            position: absolute;
            top: 12px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 15px;
            outline: none;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #2d6486;
        }

        .modal button[type="submit"] {
            background-color: #22c55e;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .modal button[type="submit"]:hover {
            background-color: #16a34a;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 16px;
            }

            .content {
                padding: 16px;
            }

            .actions-bar {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .search-container {
                justify-content: center;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1><span class="bonjour">bonjour</span> Docteur</h1>
            </div>

            <nav class="nav">
                <a href="accueil.php" class="nav-item">Accueil</a>
                <a href="tarifs.php" class="nav-item">Tarifs</a>
                <a href="#" class="nav-item active">Caissier/technicien</a>
                <a href="statistique.php" class="nav-item">Statistique</a>
            </nav>

            <div class="disconnect">
                <div class="nav-item">Déconnexion</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="background"></div>

            <div class="content">
                <div class="header">
                    <h2 class="title">Ajouter des caissiers/techniciens</h2>

                    <div class="actions-bar">
                        <div class="action-buttons">
                            <button class="btn btn-add" title="Ajouter" id="btnAdd">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <line x1="19" y1="8" x2="19" y2="14"/>
                                    <line x1="22" y1="11" x2="16" y2="11"/>
                                </svg>
                            </button>
                            <button class="btn btn-edit" title="Modifier" id="btnEdit" disabled>
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="btn btn-delete" title="Supprimer" id="btnDelete" disabled>
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <line x1="17" y1="8" x2="22" y2="13"/>
                                    <line x1="22" y1="8" x2="17" y2="13"/>
                                </svg>
                            </button>
                        </div>

                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Rechercher...">
                            <button class="search-btn" type="button">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>CIN</th>
                                <th>Âge</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Date de naissance</th>
                                <th>Genre</th>
                                <th>Nom d'utilisateur</th>
                                <th>Mot de passe</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($users)): ?>
                                <?php foreach($users as $user): ?>
                                <tr data-id="<?= $user['id_user'] ?>">
                                    <td><?=htmlspecialchars($user['user_nom'])?></td>
                                    <td><?=htmlspecialchars($user['user_prenom'])?></td>
                                    <td><?=htmlspecialchars($user['user_cin'])?></td>
                                    <td>
                                        <?php 
                                            $birthDate = new DateTime($user['user_date_naissance']);
                                            $today = new DateTime();
                                            $age = $today->diff($birthDate)->y;
                                            echo $age;
                                        ?>
                                    </td>
                                    <td><?=htmlspecialchars($user['user_telephone'])?></td>
                                    <td><?=htmlspecialchars($user['user_email'])?></td>
                                    <td><?=htmlspecialchars($user['user_date_naissance'])?></td>
                                    <td><?=htmlspecialchars($user['user_genre'])?></td>
                                    <td><?=htmlspecialchars($user['username'])?></td>
                                    <td><?=htmlspecialchars($user['user_password'])?></td>
                                    <td><?=htmlspecialchars($user['user_status'])?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="11" style="text-align:center;">Aucun utilisateur trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout/Modifier -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2 id="modalTitle">Ajouter un utilisateur</h2>

            <form method="POST" id="userForm" novalidate>
                <input type="hidden" name="id_user" id="id_user" value="" />
                <input type="hidden" name="action" id="formAction" value="ajouter" />

                <div class="form-group">
                    <label for="user_nom">Nom</label>
                    <input type="text" id="user_nom" name="user_nom" required />
                </div>
                <div class="form-group">
                    <label for="user_prenom">Prénom</label>
                    <input type="text" id="user_prenom" name="user_prenom" required />
                </div>
                <div class="form-group">
                    <label for="user_cin">CIN</label>
                    <input type="text" id="user_cin" name="user_cin" required />
                </div>
                <div class="form-group">
                    <label for="user_telephone">Téléphone</label>
                    <input type="text" id="user_telephone" name="user_telephone" required />
                </div>
                <div class="form-group">
                    <label for="user_email">Email</label>
                    <input type="email" id="user_email" name="user_email" required />
                </div>
                <div class="form-group">
                    <label for="user_date_naissance">Date de naissance</label>
                    <input type="date" id="user_date_naissance" name="user_date_naissance" required />
                </div>
                <div class="form-group">
                    <label for="user_genre">Genre</label>
                    <select id="user_genre" name="user_genre" required>
                        <option value="" disabled>Choisir</option>
                        <option value="femme">Femme</option>
                        <option value="homme">Homme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required />
                </div>
                <div class="form-group">
                    <label for="user_password">Mot de passe</label>
                    <input type="text" id="user_password" name="user_password" required />
                </div>
                <div class="form-group">
                    <label for="user_status">Type</label>
                    <select id="user_status" name="user_status" required>
                        <option value="" disabled>Choisir</option>
                        <option value="technicien">Technicien</option>
                        <option value="caissier">Caissier</option>
                    </select>
                </div>

                <button type="submit" id="submitBtn">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Formulaire suppression (hidden) -->
    <form method="POST" id="deleteForm" style="display:none;">
        <input type="hidden" name="id_user" id="delete_id_user" value="" />
        <input type="hidden" name="action" value="supprimer" />
    </form>

    <script>
        const userModal = document.getElementById('userModal');
        const openAddBtn = document.getElementById('btnAdd');
        const closeModalBtn = document.getElementById('closeModal');
        const submitBtn = document.getElementById('submitBtn');
        const modalTitle = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');
        const userForm = document.getElementById('userForm');

        const btnEdit = document.getElementById('btnEdit');
        const btnDelete = document.getElementById('btnDelete');

        const deleteForm = document.getElementById('deleteForm');
        const deleteIdInput = document.getElementById('delete_id_user');

        let selectedRow = null;

        const rows = document.querySelectorAll('#usersTable tbody tr');

        // Gestion sélection ligne
        rows.forEach(row => {
            row.addEventListener('click', () => {
                if (selectedRow) {
                    selectedRow.classList.remove('selected');
                }
                selectedRow = row;
                selectedRow.classList.add('selected');

                btnEdit.disabled = false;
                btnDelete.disabled = false;
            });
        });

        // Ouvrir modal ajout
        openAddBtn.addEventListener('click', () => {
            userForm.reset();
            formAction.value = 'ajouter';
            modalTitle.textContent = "Ajouter un utilisateur";
            submitBtn.textContent = "Ajouter";
            document.getElementById('id_user').value = '';
            userModal.style.display = 'flex';
        });

        // Ouvrir modal modifier
        btnEdit.addEventListener('click', () => {
            if (!selectedRow) return alert('Veuillez sélectionner un utilisateur.');

            // Remplir le formulaire avec les données sélectionnées
            const cells = selectedRow.querySelectorAll('td');
            document.getElementById('id_user').value = selectedRow.dataset.id;
            document.getElementById('user_nom').value = cells[0].textContent.trim();
            document.getElementById('user_prenom').value = cells[1].textContent.trim();
            document.getElementById('user_cin').value = cells[2].textContent.trim();
            // Pas d'age à remplir car calculé dynamiquement
            document.getElementById('user_telephone').value = cells[4].textContent.trim();
            document.getElementById('user_email').value = cells[5].textContent.trim();
            document.getElementById('user_date_naissance').value = cells[6].textContent.trim();
            document.getElementById('user_genre').value = cells[7].textContent.trim();
            document.getElementById('username').value = cells[8].textContent.trim();
            document.getElementById('user_password').value = cells[9].textContent.trim();
            document.getElementById('user_status').value = cells[10].textContent.trim();

            formAction.value = 'modifier';
            modalTitle.textContent = "Modifier un utilisateur";
            submitBtn.textContent = "Modifier";

            userModal.style.display = 'flex';
        });

        // Supprimer
        btnDelete.addEventListener('click', () => {
            if (!selectedRow) return alert('Veuillez sélectionner un utilisateur.');
            const userName = selectedRow.querySelector('td').textContent.trim();

            if (confirm(`Confirmer la suppression de l'utilisateur : ${userName} ?`)) {
                deleteIdInput.value = selectedRow.dataset.id;
                deleteForm.submit();
            }
        });

        // Fermer modal
        closeModalBtn.addEventListener('click', () => {
            userModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === userModal) {
                userModal.style.display = 'none';
            }
        });

        // Recherche simple dans le tableau
        const searchInput = document.querySelector('.search-input');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Reset selection et boutons si la sélection est filtrée
            if (selectedRow && selectedRow.style.display === 'none') {
                selectedRow.classList.remove('selected');
                selectedRow = null;
                btnEdit.disabled = true;
                btnDelete.disabled = true;
            }
        });
    </script>
</body>
</html>
