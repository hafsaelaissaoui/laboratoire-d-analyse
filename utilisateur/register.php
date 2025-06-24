<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratoire Chark</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: linear-gradient(to bottom, #6a9ab0, #2d6486);
        }

        /* Header Styles */
        .header {
            background-color: #2d6486;
            padding: 1rem 1.5rem;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 3rem;
            height: 3rem;
            background-color: #ff8c00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .logo-text {
            color: white;
        }

        .logo-title {
            font-weight: bold;
            font-size: 1.125rem;
        }

        .logo-subtitle {
            font-size: 0.875rem;
            letter-spacing: 0.1em;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #ff8c00;
        }

        .nav a.active {
            color: #ff8c00;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            max-width: 1152px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .content-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        /* Left Panel */
        .welcome-panel {
            background-color: #ff8c00;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .welcome-title {
            color: white;
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .connect-btn {
            border: 2px solid white;
            color: white;
            background: transparent;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .connect-btn:hover {
            background: white;
            color: #ff8c00;
        }

        /* Right Panel - Form */
        .form-panel {
            padding: 3rem;
            background-color: #f5f5f5;
        }

        .form-title {
            font-size: 1.875rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #000000;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-input {
            background-color: #d9d9d9;
            border: none;
            padding: 0.75rem 1rem;
            height: 3rem;
            border-radius: 0.375rem;
            font-size: 1rem;
        }

        .form-input::placeholder {
            color: #a6a3a3;
        }

        .form-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #ff8c00;
        }

        .submit-section {
            padding-top: 1rem;
            display: flex;
            justify-content: center;
        }

        .submit-btn {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 0.75rem 3rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1.125rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #f08c00;
        }

        /* Footer */
        .footer {
            background-color: #2d6486;
            color: white;
            padding: 3rem 0;
            margin-top: 4rem;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .footer-logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-logo-icon {
            width: 5rem;
            height: 5rem;
            background-color: #6a9ab0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .footer-logo-text {
            text-align: center;
        }

        .footer-section h3 {
            font-weight: bold;
            font-size: 1.125rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid white;
            padding-bottom: 0.5rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .footer-section a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #ff8c00;
        }

        .contact-info p {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Copyright */
        .copyright {
            background-color: #ff8c00;
            text-align: center;
            padding: 0.75rem;
        }

        .copyright p {
            color: white;
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
            }

            .content-card {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .footer-container {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 1.5rem;
            }

            .welcome-panel,
            .form-panel {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">🔬</div>
                <div class="logo-text">
                    <div class="logo-title">Laboratoire</div>
                    <div class="logo-subtitle">CHARK</div>
                </div>
            </div>
            <nav class="nav">
               
                    <a href="index.php">Accueil</a>
                   <a href="resultat.php">Resultat</a>
                    <a href="domicile.php">Services a domicile</a>
                    <a href="compte.php"class="active">Contact</a>
                
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-card">
            <!-- Left Panel -->
            <div class="welcome-panel">
                <h2 class="welcome-title">Bienvenue a nouveau !</h2>
                <a href="compte.php" class="connect-btn">SE CONNECTER</a>
            </div>

            <!-- Right Panel - Create Account Form -->
            <div class="form-panel">
                <h2 class="form-title">Create Account</h2>

                <form class="form">
                    <div class="form-row">
                        <input type="text" placeholder="Prenom" class="form-input">
                        <input type="text" placeholder="Nom" class="form-input">
                    </div>

                    <div class="form-row">
                        <input type="tel" placeholder="Telephone" class="form-input">
                        <input type="text" placeholder="Genre" class="form-input">
                    </div>

                    <input type="email" placeholder="Email" class="form-input">
                    <input type="text" placeholder="CIN" class="form-input">
                    <input type="date" placeholder="Date de naissance" class="form-input">
                    <input type="password" placeholder="Password" class="form-input">
                    <input type="password" placeholder="confirm Password" class="form-input">

                    <div class="submit-section">
                        <button type="submit" class="submit-btn">S'INSCRIRE</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Logo Section -->
            <div class="footer-logo-section">
                <div class="footer-logo-icon">🔬</div>
                <div class="footer-logo-text">
                    <div class="logo-title">Laboratoire</div>
                    <div class="logo-subtitle">Chark</div>
                </div>
            </div>

            <!-- Services -->
            <div class="footer-section">
                <h3>SERVICES</h3>
                <ul>
                    <li>Biochimie,</li>
                    <li>Immunologie</li>
                    <li>Auto-immunite</li>
                    <li>Microbiologie</li>
                    <li>Biologie de</li>
                    <li>reproduction</li>
                    <li>Biologie moleculaire</li>
                </ul>
            </div>

            <!-- Liens Rapides -->
            <div class="footer-section">
                <h3>LIENS RAPIDES</h3>
                 <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="resultat.php">Resultat</a></li>
                    <li><a href="domicile.php">Services a domicile</a></li>
                    <li><a href="compte.php"class="active">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <h3>CONTACT</h3>
                <div class="contact-info">
                    <p>+(212) 539 954 000</p>
                    <p>labochark@gmail.com</p>
                    <p>19 rue Beni Marin</p>
                    <p>résid. Walim</p>
                    <p>av. Mohammed V</p>
                    <p>Guercif - Maroc</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Copyright -->
    <div class="copyright">
        <p>Copyright 2025 Laboratoire Chark</p>
    </div>

    <script>
        // Simple form validation
        document.querySelector('.form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const inputs = this.querySelectorAll('.form-input');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#ff0000';
                    isValid = false;
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (isValid) {
                alert('Compte créé avec succès!');
            } else {
                alert('Veuillez remplir tous les champs.');
            }
        });

        // Remove error styling on input
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                this.style.borderColor = '';
            });
        });
    </script>
</body>
</html>