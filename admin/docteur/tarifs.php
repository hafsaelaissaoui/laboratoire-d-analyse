<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des analyses - Dashboard Médical</title>
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

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 256px;
            background-color: #2d6486;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 24px;
        }

        .sidebar-header h1 {
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 16px;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 8px;
        }

        .sidebar-nav a {
            display: block;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .sidebar-nav a:hover {
            background-color: #3a7ba0;
        }

        .sidebar-nav a.active {
            background-color: #3a7ba0;
            color: #f08c00;
            font-weight: 500;
        }

        .sidebar-footer {
            padding: 16px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/../../photos/background.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
            padding: 32px;
        }

        .page-title {
            font-size: 30px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 32px;
        }

        /* Search Bar Styles */
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

        /* Action Icons Styles */
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

        .action-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #d9d9d9;
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background-color: #2d6486;
        }

        .table-header th {
            color: white;
            font-weight: 600;
            padding: 16px 24px;
            text-align: left;
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
        }

        .table-body tr:first-child td:first-child {
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .content-wrapper {
                padding: 16px;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .search-input-wrapper {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Bonjour, Docteur</h1>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="docteur.php">Accueil</a></li>
                    <li><a href="docteur.php">Patient</a></li>
                    <li><a href="tarif.php" class="active">Analyse</a></li>
                    <li><a href="technicien_caissier.php">Caissier/technicien</a></li>
                    <li><a href="#">statistique</a></li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a href="#">Deconnexion</a>
            </div>
        </div>

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
                    <button class="action-icon" title="Ajouter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <button class="action-icon" title="Modifier">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m18 2 4 4-14 14H4v-4L18 2z"></path>
                        </svg>
                    </button>
                    <button class="action-icon" title="Supprimer">
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
                    <table class="data-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th>Analyse</th>
                                <th>Prix</th>
                                <th>Durée</th>
                                <th>TYPE</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            <tr>
                                <td>1</td>
                                <td>Nfs</td>
                                <td>100 DH</td>
                                <td>4H</td>
                                <td>Hématologie</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Vitamine D</td>
                                <td>300 DH</td>
                                <td>6H</td>
                                <td>BIOCHIMIE</td>
                            </tr>
                            <tr>
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
                            </tr>
                            <tr>
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
                            </tr>
                            <tr>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonctionnalité de recherche simple
        document.querySelector('.search-button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value.toLowerCase();
            const rows = document.querySelectorAll('.table-body tr');
            
            rows.forEach(row => {
                const analyseName = row.cells[1]?.textContent.toLowerCase() || '';
                if (analyseName.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Recherche en temps réel
        document.querySelector('.search-input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.table-body tr');
            
            rows.forEach(row => {
                const analyseName = row.cells[1]?.textContent.toLowerCase() || '';
                if (analyseName.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Fonctionnalité des boutons d'action
        document.querySelectorAll('.action-icon').forEach((button, index) => {
            button.addEventListener('click', function() {
                const actions = ['Ajouter', 'Modifier', 'Supprimer'];
                alert(`Action: ${actions[index]} - Fonctionnalité à implémenter`);
            });
        });
    </script>
</body>
</html>
