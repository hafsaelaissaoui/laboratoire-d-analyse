<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laboratoire Chark</title>

  <!-- Font Awesome pour icônes -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />

  <style>
    :root {
      --orange: #F08C00;
      --blue-light: #6A9AB0;
      --blue-dark: #2D6486;
      --white: #fff;
      --grey-light: #f7f7f7;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: var(--white);
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
    .hero {
      position: relative;
      background: url('../photos/1.jpg') no-repeat center center/cover;
      height: 500px;
      display: flex;
      align-items: center;
    }

    .overlay {
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      padding-left: 2rem;
      color: white;
    }

    .hero-content {
      max-width: 600px;
    }

    .hero h1 {
      font-size: 2.8rem;
      margin-bottom: 1rem;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .hero p {
      font-size: 1.2rem;
      line-height: 1.6;
      margin-bottom: 2rem;
      font-weight: 500;
    }

    .hero .buttons {
      display: flex;
      gap: 1.5rem;
    }

    .hero .buttons a {
      background-color: var(--blue-dark);
      color: white;
      padding: 0.7rem 1.5rem;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s, color 0.3s;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .hero .buttons a:hover {
      background-color: var(--orange);
      color: white;
    }

    /* Plateau Technique */

    .plateau {
      padding: 3rem 2rem;
      text-align: center;
      max-width: 1100px;
      margin: 0 auto;
    }

    .plateau h2 {
      color: var(--blue-dark);
      margin-bottom: 2.5rem;
      font-size: 2.4rem;
      font-weight: 700;
    }

    .services {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
    }

    @media (max-width: 900px) {
      .services {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .services {
        grid-template-columns: 1fr;
      }
    }

    .card {
      background-color: var(--grey-light);
      border: 1.5px solid var(--orange);
      border-radius: 12px;
      padding: 2rem 1.5rem;
      box-shadow: 0 4px 10px rgba(240, 140, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(240, 140, 0, 0.4);
    }

    .card h3 {
      color: var(--blue-dark);
      font-size: 1.4rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      font-weight: 700;
    }

    .card h3 i {
      color: var(--orange);
      font-size: 1.6rem;
    }

    .card p {
      font-size: 1rem;
      line-height: 1.5;
      color: #333;
      padding: 0 0.5rem;
      font-weight: 500;
    }

    /* Section Laboratoire */

    .laboratoire {
      background-color: var(--blue-light);
      padding: 3rem 2rem;
      text-align: center;
    }

    .laboratoire h2 {
      color: white;
      margin-bottom: 2rem;
      font-weight: 700;
      font-size: 2.2rem;
    }

   .gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
  max-width: 1100px;
  margin: 0 auto;
  padding: 1rem;
}

.gallery img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  cursor: pointer;
}

.gallery img:hover {
  transform: scale(1.08);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
}

  </style>
</head>
<body>

 <nav>
    <div class="logo">Laboratoire Chark</div>
    <ul>
      <li><a href="index.php" class="active">Accueil</a></li>
      <li><a href="resultat.php">Résultat</a></li>
      <li><a href="domicile.php">Services à domicile</a></li>
      <li><a href="compte.php">Compte</a></li>
    </ul>
</nav>
  <section class="hero">
    <div class="overlay">
      <div class="hero-content">
        <h1>LABORATOIRE CHARK</h1>
        <p>Laboratoire d’Analyses Médicales<br>
        Notre disponibilité est notre engagement auprès des patients et du corps médical 24h/24 et 7j/7</p>
        <div class="buttons">
          <a href="../utilisateur/tarifs.php">Tarifs des analyses</a>
          <a href="domicile.php">Services à domicile</a>
        </div>
      </div>
    </div>
  </section>

  <section class="plateau">
    <h2>Plateau Technique</h2>
    <div class="services">
      <div class="card">
        <h3><i class="fas fa-vials"></i> BIOCHIMIE</h3>
        <p>Analyse des paramètres biochimiques, des enzymes sur tous les échantillons biologiques. La diversité et les gammes des technologies disponibles au laboratoire nous confèrent un système de contrôle de qualité interne robuste.</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-tint"></i> IMMUNOLOGIE</h3>
        <p>Analyse des anticorps et des antigènes spécifiques de plusieurs maladies. Le choix des réactifs est réalisé vis-à-vis de la sensibilité et de la spécificité de ces composantes afin d'obtenir une quantification exacte.</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-microscope"></i> MICROBIOLOGIE</h3>
        <p>Diagnostic des maladies infectieuses bactériennes, virales, mycologiques et parasitaires. La technique CMI utilisée au laboratoire offre un grand avantage pour détecter les profils de multirésistance et assurer un bon usage thérapeutique.</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-shield-virus"></i> AUTO IMMUNITÉ</h3>
        <p>Diagnostic des maladies auto-immunes par la quantification des anticorps avec la technique ELISA (technique de référence).</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-baby"></i> BIOLOGIE DE LA REPRODUCTION</h3>
        <p>Le laboratoire DU NORD s'intègre dans l'étude de l'infertilité chez le couple via des analyses cytologiques, biochimiques et génomiques des spermatozoïdes.</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-dna"></i> BIOLOGIE MOLÉCULAIRE</h3>
        <p>Un service spécialisé dans la détection de nombreuses infections via les techniques PCR et RT-PCR.</p>
      </div>
    </div>
  </section>

  <section class="laboratoire">
    <h2>Notre Laboratoire</h2>
    <div class="gallery">
      <img src="../photos/1.jpg" alt="photo 1" />
      <img src="../photos/2.jpg" alt="photo 3" />
      <img src="../photos/3.jpg" alt="photo 4" />
      <img src="../photos/4.jpg" alt="photo 5" />
      <img src="../photos/6.jpg" alt="photo 6" />
      <img src="../photos/7.jpg" alt="photo 7" />
     
    </div>
  </section>

  

<?php include '../includes/footer.php';?>


</body>
</html>
