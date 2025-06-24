<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=labonew;charset=utf8", "root", "");

// Récupération des analyses triées par catégorie
$sql = "SELECT s.*, c.nom_categorie 
        FROM services s 
        JOIN categorie c ON s.id_categorie = c.id_categorie
        ORDER BY c.nom_categorie, s.nom_analyse";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$analyses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Tarif des Analyses</title>
  <style>
    :root {
      --primary: #0082c8;
      --secondary: #00a651;
      --light: #f4f8fb;
      --text: #333;
      --table-bg: #fff;
      --header-bg: #f0f0f0;
      --border: #ddd;
      --shadow: rgba(0, 0, 0, 0.05);
      --orange: #F08C00;
      --blue-light: #6A9AB0;
      --blue-dark: #2D6486;
      --white: #fff;
      --grey-light: #f7f7f7;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--light);
      padding: 0;
      margin: 0;
      color: var(--text);
    }
    nav {
      background-color: var(--blue-dark);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      color: var(--white);
    }

    nav .logo {
      font-weight: bold;
      font-size: 1.2rem;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      color: var(--white);
      text-decoration: none;
      position: relative;
      padding: 0.2rem 0.4rem;
      transition: color 0.3s;
      font-weight: 600;
    }

    nav ul li a:hover,
    nav ul li a.active {
      color: var(--orange);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 20px 40px;
    }

    h1 {
      text-align: center;
      margin-bottom: 40px;
      color: var(--primary);
      font-size: 28px;
    }

    .search-form {
      text-align: center;
      margin-bottom: 40px;
    }

    .search-form input {
      width: 320px;
      padding: 12px 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 16px;
      box-shadow: 0 2px 5px var(--shadow);
      transition: 0.3s ease;
    }

    .search-form input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 130, 200, 0.2);
    }

    .table-container {
      margin-top: 20px;
      padding: 0 10px;
    }

    .category-header {
      background-color: var(--primary);
      color: white;
      padding: 12px 20px;
      margin: 50px 0 10px;
      border-radius: 6px;
      font-size: 18px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: var(--table-bg);
      box-shadow: 0 3px 10px var(--shadow);
      margin-bottom: 20px;
    }

    thead {
      background-color: var(--header-bg);
    }

    th, td {
      padding: 14px 12px;
      border: 1px solid var(--border);
      text-align: center;
      font-size: 15px;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f7fc;
    }

    @media (max-width: 768px) {
      .search-form input {
        width: 90%;
      }

      th, td {
        font-size: 14px;
        padding: 10px;
      }

      .category-header {
        font-size: 16px;
        padding: 10px 15px;
      }
    }
  </style>
</head>
<body>

 <nav>
    <div class="logo">Laboratoire Chark</div>
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="resultat.php">Résultat</a></li>
      <li><a href="domicile.php">Services à domicile</a></li>
      <li><a href="compte.php">Compte</a></li>
    </ul>
</nav>

<div class="container">
  <h1>Tarif des Analyses</h1>

  <form class="search-form" onsubmit="return false;">
    <input type="text" id="searchInput" placeholder="Rechercher une analyse...">
  </form>

  <div class="table-container" id="tableContainer">
    <?php
      $currentCategorie = '';
      $tableOpened = false;

      foreach ($analyses as $analyse):
        if ($analyse['nom_categorie'] !== $currentCategorie):
          if ($tableOpened) {
            echo "</tbody></table>";
          }

          $currentCategorie = $analyse['nom_categorie'];
          echo "<div class='category-header'>" . htmlspecialchars($currentCategorie) . "</div>";
          echo "<table class='analyse-table'>
                  <thead>
                    <tr>
                      <th>Analyse</th>
                      <th>Prix (DH)</th>
                      <th>Durée</th>
                    </tr>
                  </thead>
                  <tbody>";
          $tableOpened = true;
        endif;
    ?>
        <tr>
          <td><?= htmlspecialchars($analyse['nom_analyse']) ?></td>
          <td><?= htmlspecialchars($analyse['prix_analyse']) ?></td>
          <td><?= htmlspecialchars($analyse['dure_analyse']) ?></td>
        </tr>
    <?php endforeach;

      if ($tableOpened) {
        echo "</tbody></table>";
      }
    ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
  document.getElementById('searchInput').addEventListener('input', function () {
    const search = this.value.toLowerCase();
    const tables = document.querySelectorAll('.analyse-table');

    tables.forEach(table => {
      let showTable = false;
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(search)) {
          row.style.display = '';
          showTable = true;
        } else {
          row.style.display = 'none';
        }
      });

      // Affichage ou masquage du tableau et du titre de la catégorie
      const categoryHeader = table.previousElementSibling;
      table.style.display = showTable ? '' : 'none';
      categoryHeader.style.display = showTable ? '' : 'none';
    });
  });
</script>

</body>
</html>
