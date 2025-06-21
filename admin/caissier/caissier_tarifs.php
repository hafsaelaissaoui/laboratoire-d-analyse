<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs des analyses</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            height: 100vh;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 256px;
            background-color: #2d6486;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-nav {
            flex: 1;
            padding: 24px;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 24px;
        }

        .sidebar-nav a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar-nav a:hover {
            color: #e5e7eb;
        }

        .sidebar-nav .active {
            color: #f08c00;
            font-weight: 500;
        }

        .notification-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bell-icon {
            width: 16px;
            height: 16px;
            color: #f08c00;
        }

        .sidebar-footer {
            padding: 24px;
            border-top: 1px solid #3a7a9a;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            inset: 0;
            background-image: url('../photos/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.3;
        }

        .content {
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
            margin-bottom: 32px;
            max-width: 512px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-right: none;
            border-radius: 6px 0 0 6px;
            background: white;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #16a245;
            box-shadow: 0 0 0 1px #16a245;
        }

        .search-button {
            padding: 12px 24px;
            background-color: #16a245;
            color: white;
            border: none;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .search-button:hover {
            background-color: #138a3a;
        }

        /* Table Styles */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1024px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background-color: #2d6486;
        }

        .table-header th {
            color: white;
            font-weight: 600;
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #374151;
        }

        .table-body tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.3s;
        }

        .table-body tr:hover {
            background-color: #f9fafb;
        }

        .table-body td {
            padding: 16px;
            color: #374151;
        }

        .table-body .font-medium {
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .sidebar-nav {
                padding: 16px;
            }

            .sidebar-nav ul {
                display: flex;
                gap: 16px;
            }

            .sidebar-nav li {
                margin-bottom: 0;
            }

            .content {
                padding: 16px;
            }

            .search-container {
                max-width: 100%;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="caissier.php" onclick="navigateTo('accueil')">Accueil</a>
                </li>
                <li>
                    <a href="caissier_tarifs.php" class="active" onclick="navigateTo('tarifs')">Tarifs des analyses</a>
                </li>
                <li class="notification-item">
                    <a href="#" onclick="navigateTo('notifications')">Notifications</a>
                    <svg class="bell-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2C7.8 2 6 3.8 6 6v3.7L4.6 11.1c-.4.4-.6 1-.6 1.6V14c0 .6.4 1 1 1h10c.6 0 1-.4 1-1v-1.3c0-.6-.2-1.2-.6-1.6L14 9.7V6c0-2.2-1.8-4-4-4zm0 16c1.1 0 2-.9 2-2H8c0 1.1.9 2 2 2z"/>
                    </svg>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="#" onclick="logout()">Deconnexion</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Background Image -->
        <div class="background-overlay"></div>

        <!-- Content -->
        <div class="content">
            <h1 class="page-title">Tarifs des analyses</h1>

            <!-- Search Bar -->
            <div class="search-container">
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="Nom de l'analyse"
                    id="searchInput"
                    onkeyup="filterTable()"
                >
                <button class="search-button" onclick="performSearch()">Recherche</button>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <table class="table" id="analysisTable">
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
                            <td class="font-medium">1</td>
                            <td>Nfs</td>
                            <td>100 DH</td>
                            <td>4H</td>
                            <td>Hématologie</td>
                        </tr>
                        <tr>
                            <td class="font-medium">2</td>
                            <td>Vitamine D</td>
                            <td>300 DH</td>
                            <td>6H</td>
                            <td>BIOCHIMIE</td>
                        </tr>
                        <!-- Empty rows to match design -->
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Sample data for more comprehensive search functionality
        const analysisData = [
            { id: 1, analyse: "Nfs", prix: "100 DH", duree: "4H", type: "Hématologie" },
            { id: 2, analyse: "Vitamine D", prix: "300 DH", duree: "6H", type: "BIOCHIMIE" },
            { id: 3, analyse: "Glycémie", prix: "50 DH", duree: "2H", type: "BIOCHIMIE" },
            { id: 4, analyse: "Cholestérol", prix: "80 DH", duree: "3H", type: "BIOCHIMIE" },
            { id: 5, analyse: "TSH", prix: "120 DH", duree: "5H", type: "HORMONOLOGIE" }
        ];

        // Navigation function
        function navigateTo(page) {
            // Remove active class from all links
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active class to clicked link
            event.target.classList.add('active');
            
            console.log('Navigating to:', page);
            // Here you would implement actual navigation logic
        }

        // Logout function
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter?')) {
                console.log('User logged out');
                // Here you would implement actual logout logic
            }
        }

        // Search functionality
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('analysisTable');
            const rows = table.getElementsByTagName('tr');

            // Skip header row (index 0)
            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;

                // Check if any cell contains the search term
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                // Show or hide row based on search result
                rows[i].style.display = found ? '' : 'none';
            }
        }

        // Perform search button click
        function performSearch() {
            filterTable();
            const searchTerm = document.getElementById('searchInput').value;
            if (searchTerm) {
                console.log('Searching for:', searchTerm);
            }
        }

        // Add enter key support for search
        document.getElementById('searchInput').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Medical Analysis Pricing System Loaded');
        });
    </script>
</body>
</html>
