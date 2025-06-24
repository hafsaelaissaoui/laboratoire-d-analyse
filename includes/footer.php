<!-- footer.php -->
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;900&display=swap" rel="stylesheet">

<style>
  footer {
    background-color: #2D6486;
    color: white;
    font-family: 'Lato', sans-serif;
  }

  .footer-container {
    display: flex;
    justify-content: space-around;
    padding: 40px 20px;
    flex-wrap: wrap;
  }

  .footer-section {
    flex: 1;
    min-width: 200px;
    margin: 10px;
  }

  .footer-section h3 {
    font-weight: 900;
    margin-bottom: 10px;
    border-bottom: 2px solid white;
    display: inline-block;
    padding-bottom: 4px;
  }

  .footer-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer-section li {
    font-weight: 400;
    margin: 6px 0;
    line-height: 1.5;
  }

  .logo-section {
    text-align: center;
  }

  .logo {
    width: 100px;
    margin-bottom: 10px;
  }

  .copyright {
    background-color: #F08C00;
    text-align: center;
    padding: 6px;
    font-size: 14px;
    color: white;
  }
</style>

<footer>
  <div class="footer-container">
    <div class="footer-section logo-section">
      <img src="../photos/logo.png" alt="Logo Laboratoire Chark" class="logo" />
    </div>
    <div class="footer-section">
      <h3>SERVICES</h3>
      <ul>
        <li>Biochimie</li>
        <li>Immunologie</li>
        <li>Microbiologie</li>
        <li>Auto-immunite</li>
        <li>Biologie de reproduction</li>
        <li>Biologie moleculaire</li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>LIENS RAPIDES</h3>
      <ul>
        <li>Accueil</li>
        <li>Resultat</li>
        <li>Services à domicile</li>
        <li>Compte</li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>CONTACT</h3>
      <ul>
        <li>+(212) 539 954 000</li>
        <li>labochark@gmail.com</li>
        <li>19 rue Beni Marin<br>résid. Walim<br>av. Mohammed V<br>Guercif - Maroc</li>
      </ul>
    </div>
  </div>
  <div class="copyright">
    Copyright 2025 &nbsp;&nbsp;Laboratoire Chark
  </div>
</footer>
