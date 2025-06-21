<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical</title>
    <style>
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

        /* Sidebar */
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

        /* Main Content */
        .main-content {
            flex: 1;
            position: relative;
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
        }

        .content {
            position: relative;
            z-index: 10;
            padding: 32px;
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

        /* Table */
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

        /* Icons */
        .icon {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        /* Responsive */
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
                <div class="nav-item">Accueil</div>
                <div class="nav-item">Patient</div>
                <div class="nav-item">Analyse</div>
                <div class="nav-item active">Caissier/technicien</div>
                <div class="nav-item">statistique</div>
            </nav>

            <div class="disconnect">
                <div class="nav-item">Deconnexion</div>
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
                            <button class="btn btn-add" title="Ajouter">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <line x1="19" y1="8" x2="19" y2="14"/>
                                    <line x1="22" y1="11" x2="16" y2="11"/>
                                </svg>
                            </button>
                            <button class="btn btn-edit" title="Modifier">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="btn btn-delete" title="Supprimer">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <line x1="17" y1="8" x2="22" y2="13"/>
                                    <line x1="22" y1="8" x2="17" y2="13"/>
                                </svg>
                            </button>
                        </div>

                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="">
                            <button class="search-btn">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>CIN</th>
                                <th>Age</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>date de naissance</th>
                                <th>Genre</th>
                                <th>Nom d'utilisateur</th>
                                <th>Mot de passe</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Elaissaoui</td>
                                <td>Hafsa</td>
                                <td>ZG 66908</td>
                                <td>21</td>
                                <td>0778562908</td>
                                <td>Hafsaelaissaoui@gmail.com</td>
                                <td>2002/05/26</td>
                                <td>Femelle</td>
                                <td>hafsa12</td>
                                <td>1234</td>
                                <td>Technicien</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonctionnalité de recherche simple
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Gestionnaires d'événements pour les boutons
        document.querySelector('.btn-add').addEventListener('click', function() {
            alert('Ajouter un nouveau caissier/technicien');
        });

        document.querySelector('.btn-edit').addEventListener('click', function() {
            alert('Modifier le caissier/technicien sélectionné');
        });

        document.querySelector('.btn-delete').addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                alert('Élément supprimé');
            }
        });
    </script>
</body>
</html>