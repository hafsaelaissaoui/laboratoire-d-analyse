<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médical - Statistiques</title>
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
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h1 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 32px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .nav-item {
            color: white;
            padding: 8px 0;
            cursor: pointer;
            transition: color 0.2s;
        }

        .nav-item:hover {
            color: #d1d5db;
        }

        .nav-item.active {
            color: #f08c00;
            font-weight: 500;
        }

        .nav-bottom {
            margin-top: auto;
            padding-top: 80px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            inset: 0;
            background-image: url('../../photos/background.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
            z-index: 1;
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
            padding: 32px;
        }

        .page-title {
            font-size: 36px;
            font-weight: bold;
            color: #eb1619;
            margin-bottom: 32px;
        }

        /* Search and Filter Section */
        .search-section {
            margin-bottom: 32px;
        }

        .search-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .btn-primary {
            background-color: #16a245;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #15803d;
        }

        .filter-row {
            display: flex;
            gap: 16px;
            align-items: end;
        }

        .date-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .date-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .date-input {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 14px;
            width: 150px;
        }

        /* Table Styles */
        .table-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 8px;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
            text-align: center;
            padding: 16px;
            font-size: 16px;
        }

        .table-body tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .table-body tr:hover {
            background-color: #f9fafb;
        }

        .table-body td {
            padding: 12px 16px;
        }

        .table-body td:nth-child(2),
        .table-body td:nth-child(3) {
            text-align: center;
        }

        .amount-cell {
            color: #eb1619;
            font-weight: 500;
        }

        /* Summary Bar */
        .summary-bar {
            background-color: #16a245;
            color: white;
            padding: 16px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 16px;
            }

            .nav-menu {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 12px;
            }

            .content-wrapper {
                padding: 16px;
            }

            .page-title {
                font-size: 28px;
            }

            .search-row,
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .summary-bar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .table-card {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>Bonjour, Docteur</h1>
            
            <nav class="nav-menu">
                <div class="nav-item">Accueil</div>
                <div class="nav-item">Patient</div>
                <div class="nav-item">Caissier/technicien</div>
                <div class="nav-item active">statistique</div>
            </nav>

            <div class="nav-bottom">
                <div class="nav-item">Deconnexion</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="background-overlay"></div>
            
            <div class="content-wrapper">
                <h1 class="page-title">Statistique</h1>

                <!-- Search and Filter Section -->
                <div class="search-section">
                    <div class="search-row">
                        <input type="text" class="search-input" placeholder="Nom de Patient, Nom analyse,ID...">
                        <button class="btn-primary">Recherche</button>
                    </div>

                    <div class="filter-row">
                        <div class="date-group">
                            <label class="date-label">Date de début</label>
                            <input type="text" class="date-input" placeholder="YYYY/MM/DD">
                        </div>
                        <div class="date-group">
                            <label class="date-label">Date de fin</label>
                            <input type="text" class="date-input" placeholder="YYYY/MM/DD">
                        </div>
                        <button class="btn-primary">Filtrer</button>
                    </div>
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
                            <tr>
                                <td>NFS</td>
                                <td>20</td>
                                <td class="amount-cell">2000 DH</td>
                            </tr>
                            <tr>
                                <td>HP (Helicobacter pylori)</td>
                                <td>5</td>
                                <td class="amount-cell">230 DH</td>
                            </tr>
                            <tr>
                                <td>Vitesse de Sédimentation (VS)</td>
                                <td>100</td>
                                <td class="amount-cell">3000 DH</td>
                            </tr>
                            <tr>
                                <td>Vitamine D</td>
                                <td>2</td>
                                <td class="amount-cell">600 DH</td>
                            </tr>
                            <tr>
                                <td>Ferritine</td>
                                <td>19</td>
                                <td class="amount-cell">1900 DH</td>
                            </tr>
                            <tr>
                                <td>Groupe ABO</td>
                                <td>3</td>
                                <td class="amount-cell">150 DH</td>
                            </tr>
                            <tr>
                                <td>Cortisol</td>
                                <td>24</td>
                                <td class="amount-cell">5900 DH</td>
                            </tr>
                            <tr>
                                <td>Hormone de croissance (GH)</td>
                                <td>10</td>
                                <td class="amount-cell">1690 DH</td>
                            </tr>
                            <tr>
                                <td>PCR (dépistage génétique de virus, bactéries)</td>
                                <td>6</td>
                                <td class="amount-cell">360 DH</td>
                            </tr>
                            <tr>
                                <td>Glycémie</td>
                                <td>39</td>
                                <td class="amount-cell">6590 DH</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Summary Bar -->
                <div class="summary-bar">
                    <span>Total des Patients : 228</span>
                    <span>Montant Total : 22 890 000 DH</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonctionnalité de recherche simple
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.table-body tr');
            
            rows.forEach(row => {
                const analysisName = row.cells[0].textContent.toLowerCase();
                if (analysisName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Navigation active
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>