-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 21 juin 2025 à 17:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `labonew`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(10) UNSIGNED NOT NULL,
  `nom_categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Biochimie'),
(2, 'Immunologie'),
(3, 'Microbiologie'),
(4, 'Auto-immunité'),
(5, 'Biologie de reproduction'),
(6, 'Biologie moléculaire');

-- --------------------------------------------------------

--
-- Structure de la table `details`
--

CREATE TABLE `details` (
  `id_detail` int(10) UNSIGNED NOT NULL,
  `id_dossier` int(10) UNSIGNED NOT NULL,
  `id_service` int(10) UNSIGNED NOT NULL,
  `id_patient` int(10) UNSIGNED NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `resultat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dossier`
--

CREATE TABLE `dossier` (
  `id_dossier` int(10) UNSIGNED NOT NULL,
  `date_dossier` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `id_patient` int(10) UNSIGNED NOT NULL,
  `type_service` enum('LOCAL','domicile') NOT NULL,
  `service_genre_infirmier` enum('infirmière','infirmier') NOT NULL,
  `service_date_demande` datetime NOT NULL,
  `ordonnance_path` text NOT NULL,
  `service_etat` enum('en attente','validé','refusé') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `id_patient` int(10) UNSIGNED NOT NULL,
  `patient_nom` varchar(255) NOT NULL,
  `patient_prenom` varchar(255) NOT NULL,
  `patient_cin` varchar(50) NOT NULL,
  `patient_telephone` varchar(255) NOT NULL,
  `patient_date_naissance` date NOT NULL,
  `patient_genre` enum('Homme','Femme') NOT NULL,
  `patient_date_inscription` datetime NOT NULL,
  `patient_password` varchar(255) NOT NULL,
  `patient_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id_service` int(10) UNSIGNED NOT NULL,
  `nom_analyse` varchar(255) NOT NULL,
  `prix_analyse` decimal(8,2) NOT NULL,
  `dure_analyse` varchar(255) NOT NULL,
  `id_categorie` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id_service`, `nom_analyse`, `prix_analyse`, `dure_analyse`, `id_categorie`) VALUES
(1, 'Glycémie à jeun', 60.00, '1 jour', 1),
(2, 'Créatinine', 40.00, '1 jour', 1),
(3, 'Urée sanguine', 35.00, '1 jour', 1),
(4, 'Bilirubine totale', 50.00, '1 jour', 1),
(5, 'Cholestérol total', 45.00, '1 jour', 1),
(6, 'Triglycérides', 50.00, '1 jour', 1),
(7, 'Protéines totales', 40.00, '1 jour', 1),
(8, 'Acide urique', 35.00, '1 jour', 1),
(9, 'Calcium', 30.00, '1 jour', 1),
(10, 'ASAT/ALAT', 70.00, '1 jour', 1),
(11, 'CRP', 60.00, '1 jour', 2),
(12, 'IgG', 100.00, '2 jours', 2),
(13, 'IgA', 100.00, '2 jours', 2),
(14, 'IgM', 100.00, '2 jours', 2),
(15, 'Test HIV', 120.00, '2 jours', 2),
(16, 'Antigène HBs', 110.00, '2 jours', 2),
(17, 'Anticorps HCV', 130.00, '2 jours', 2),
(18, 'Procalcitonine', 200.00, '2 jours', 2),
(19, 'Test EBV', 140.00, '2 jours', 2),
(20, 'Test CMV', 150.00, '2 jours', 2),
(21, 'ECBU', 60.00, '2 jours', 3),
(22, 'Coproculture', 70.00, '3 jours', 3),
(23, 'Hémoculture', 150.00, '5 jours', 3),
(24, 'Prélèvement vaginal', 80.00, '3 jours', 3),
(25, 'Antibiogramme', 90.00, '2 jours', 3),
(26, 'Prélèvement nasal', 70.00, '2 jours', 3),
(27, 'Prélèvement pharyngé', 75.00, '2 jours', 3),
(28, 'Culture mycologique', 100.00, '5 jours', 3),
(29, 'Test Helicobacter pylori', 90.00, '2 jours', 3),
(30, 'Examen parasitologique des selles', 70.00, '2 jours', 3),
(31, 'ANA', 150.00, '3 jours', 4),
(32, 'Anti-DNA', 180.00, '3 jours', 4),
(33, 'Anti-CCP', 160.00, '3 jours', 4),
(34, 'Anticorps anti-TPO', 140.00, '3 jours', 4),
(35, 'Anticorps anti-TG', 140.00, '3 jours', 4),
(36, 'FR (facteur rhumatoïde)', 70.00, '2 jours', 4),
(37, 'Anti-phospholipides', 170.00, '4 jours', 4),
(38, 'Anti-SM', 180.00, '4 jours', 4),
(39, 'Anti-RNP', 160.00, '3 jours', 4),
(40, 'Anti-centromère', 190.00, '4 jours', 4),
(41, 'FSH', 90.00, '1 jour', 5),
(42, 'LH', 90.00, '1 jour', 5),
(43, 'Estradiol', 100.00, '2 jours', 5),
(44, 'Progestérone', 100.00, '2 jours', 5),
(45, 'Testostérone', 110.00, '2 jours', 5),
(46, 'AMH', 130.00, '2 jours', 5),
(47, 'Inhibine B', 140.00, '3 jours', 5),
(48, 'Spermogramme', 150.00, '1 jour', 5),
(49, 'Spermoculture', 120.00, '2 jours', 5),
(50, 'Prolactine', 90.00, '1 jour', 5),
(51, 'PCR COVID-19', 300.00, '1 jour', 6),
(52, 'PCR HBV', 400.00, '3 jours', 6),
(53, 'PCR HCV', 400.00, '3 jours', 6),
(54, 'PCR VIH', 420.00, '3 jours', 6),
(55, 'PCR HPV', 350.00, '3 jours', 6),
(56, 'PCR Chlamydia', 320.00, '3 jours', 6),
(57, 'PCR Gonorrhée', 320.00, '3 jours', 6),
(58, 'PCR Mycobactérie', 450.00, '4 jours', 6),
(59, 'PCR Grippe A/B', 300.00, '2 jours', 6),
(60, 'PCR CMV', 400.00, '2jous', 6);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `user_nom` varchar(255) NOT NULL,
  `user_prenom` varchar(255) NOT NULL,
  `user_telephone` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_date_naissance` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_status` enum('caissier','technicien') NOT NULL,
  `user_genre` enum('homme','femme') NOT NULL,
  `user_cin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `user_nom`, `user_prenom`, `user_telephone`, `user_email`, `user_date_naissance`, `username`, `user_password`, `user_status`, `user_genre`, `user_cin`) VALUES
(6, 'anass', 'ali', '0778562909', 'hafsanas@gmail.com', '2002-07-07', 'anasss', '12341234', 'technicien', 'homme', 'zg1633278'),
(7, 'rachida', 'laaraja', '0899999999', 'hafsaelaissaoui00g2@gmail.com', '2002-07-07', 'rachidadris', '1234rachida', 'technicien', 'homme', 'ZG132323');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_dossier` (`id_dossier`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD PRIMARY KEY (`id_dossier`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id_patient`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `details`
--
ALTER TABLE `details`
  MODIFY `id_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dossier`
--
ALTER TABLE `dossier`
  MODIFY `id_dossier` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `patient`
--
ALTER TABLE `patient`
  MODIFY `id_patient` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `details_ibfk_1` FOREIGN KEY (`id_dossier`) REFERENCES `dossier` (`id_dossier`),
  ADD CONSTRAINT `details_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`),
  ADD CONSTRAINT `details_ibfk_3` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_patient`);

--
-- Contraintes pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD CONSTRAINT `dossier_ibfk_1` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_patient`);

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
