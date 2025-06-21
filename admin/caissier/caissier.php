<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des patients</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-image: url('../photos/background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
}

.container {
    min-height: 100vh;
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(4px);
    display: flex;
}

/* Sidebar Styles */
.sidebar {
    width: 256px;
    background-color: #2d6486;
    color: white;
    padding: 24px;
}

.nav {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-item.logout {
    padding-top: 32px;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #e5e7eb;
}

.nav-link.active {
    color: #f08c00;
}

.notification-icon {
    color: #f08c00;
    width: 16px;
    height: 16px;
}

/* Main Content Styles */
.main-content {
    flex: 1;
    padding: 32px;
}

.content-wrapper {
    max-width: 1280px;
    margin: 0 auto;
}

.page-title {
    font-size: 30px;
    font-weight: bold;
    color: black;
    margin-bottom: 32px;
}

/* Action Bar Styles */
.action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 16px;
}

.action-btn {
    width: 40px;
    height: 40px;
    background-color: #f08c00;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

.action-btn:hover {
    background-color: #dc7503;
}

.action-btn i {
    width: 20px;
    height: 20px;
}

.search-container {
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-input {
    width: 320px;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background-color: white;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #f08c00;
    box-shadow: 0 0 0 2px rgba(240, 140, 0, 0.2);
}

.search-btn {
    width: 40px;
    height: 40px;
    background-color: #f08c00;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

.search-btn:hover {
    background-color: #dc7503;
}

.search-btn i {
    width: 20px;
    height: 20px;
}

/* Table Styles */
.table-container {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.patient-table {
    width: 100%;
    border-collapse: collapse;
}

.table-header {
    background-color: #2d6486;
}

.table-header th {
    color: white;
    font-weight: 500;
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #374151;
}

.table-header th.analyse-column {
    background-color: #f08c00;
}

.table-row {
    border-bottom: 1px solid #e5e7eb;
}

.table-row td {
    padding: 12px 16px;
    vertical-align: top;
}

.name-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.chevron-icon {
    width: 16px;
    height: 16px;
}

.date-cell {
    font-size: 14px;
    line-height: 1.4;
}

.analyse-cell {
    background-color: #f08c00;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        padding: 16px;
    }
    
    .nav {
        flex-direction: row;
        gap: 16px;
        overflow-x: auto;
    }
    
    .main-content {
        padding: 16px;
    }
    
    .action-bar {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }
    
    .search-container {
        justify-content: center;
    }
    
    .search-input {
        width: 100%;
        max-width: 320px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .patient-table {
        min-width: 800px;
    }
}
 </style>   <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="nav">
                <div class="nav-item">
                    <a href="caissier.php" class="nav-link active">Accueil</a>
                </div>
                <div class="nav-item">
                    <a href="caissier_tarifs.php" class="nav-link">Tarifs des analyses</a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">Notifications</a>
                    <i data-lucide="bell" class="notification-icon"></i>
                </div>
                <div class="nav-item logout">
                    <a href="#" class="nav-link">Deconnexion</a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-wrapper">
                <h1 class="page-title">Gestion des patients</h1>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="action-buttons">
                        <button class="action-btn">
                            <i data-lucide="user-plus"></i>
                        </button>
                        <button class="action-btn">
                            <i data-lucide="user-minus"></i>
                        </button>
                        <button class="action-btn">
                            <i data-lucide="edit"></i>
                        </button>
                    </div>

                    <div class="search-container">
                        <input type="text" placeholder="Rechercher..." class="search-input">
                        <button class="search-btn">
                            <i data-lucide="search"></i>
                        </button>
                    </div>
                </div>

                <!-- Patient Table -->
                <div class="table-container">
                    <table class="patient-table">
                        <thead>
                            <tr class="table-header">
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>CIN</th>
                                <th>Age</th>
                                <th>Date de naissance</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Genre</th>
                                <th>Créé le</th>
                                <th class="analyse-column">Analyse</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-row">
                                <td class="name-cell">
                                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                                    Elaissaoui
                                </td>
                                <td>Hafsa</td>
                                <td>ZG 66908</td>
                                <td>21</td>
                                <td>2002/07/07</td>
                                <td>0778562908</td>
                                <td>hafsaelaissaoui@gmail.com</td>
                                <td>Femelle</td>
                                <td class="date-cell">
                                    2025/05/26<br>
                                    12:45:07
                                </td>
                                <td class="analyse-cell"></td>
                            </tr>
                            <tr class="table-row">
                                <td>Elaissaoui</td>
                                <td>Hafsa</td>
                                <td>--------</td>
                                <td>10jours</td>
                                <td>2025/05/28</td>
                                <td>0778562908</td>
                                <td>--------------</td>
                                <td>Male</td>
                                <td class="date-cell">
                                    2025/05/26<br>
                                    12:45:07
                                </td>
                                <td></td>
                            </tr>
                            <!-- Empty rows for spacing -->
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr class="table-row"><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>