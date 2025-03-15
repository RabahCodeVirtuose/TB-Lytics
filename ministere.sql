-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 15 mars 2025 à 11:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ministere`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_comptes_cmpts`
--

CREATE TABLE `t_comptes_cmpts` (
  `pseudo` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_comptes_cmpts`
--

INSERT INTO `t_comptes_cmpts` (`pseudo`, `mot_de_passe`) VALUES
('directeur1@ministere.com', '$2y$10$hashedpassword123'),
('rabah.toubal.etudes@gmail.com', '$2y$10$YBovYVKkKYHKjGE1kzhtFODjfiVsTP5Bn3fJEHN7TUcMD7d468RN.'),
('toubal@ministere.com', '$2y$10$Eh8aphn5vytEUrIMPK.JGOPkuPneWKI6dHoam9YAgVFaej4uU8FyC');

-- --------------------------------------------------------

--
-- Structure de la table `t_employes_emplys`
--

CREATE TABLE `t_employes_emplys` (
  `id_employe` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `date_naissance` date NOT NULL,
  `date_recrutement` date NOT NULL CHECK (`date_recrutement` > `date_naissance`),
  `diplome` varchar(150) DEFAULT NULL,
  `fonction` varchar(100) DEFAULT NULL,
  `type_contrat` enum('CDD','CDI') NOT NULL,
  `structure_id` int(11) DEFAULT NULL,
  `archivé` tinyint(1) DEFAULT 0,
  `affectation_réelle` varchar(255) DEFAULT NULL,
  `conformite` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_employes_emplys`
--

INSERT INTO `t_employes_emplys` (`id_employe`, `nom`, `prenom`, `sexe`, `date_naissance`, `date_recrutement`, `diplome`, `fonction`, `type_contrat`, `structure_id`, `archivé`, `affectation_réelle`, `conformite`) VALUES
(2, 'Lemoine', 'Jean', 'M', '1988-09-25', '2018-02-15', 'ittak', 'Analyste', 'CDD', 2, 1, 'qahwadji', 1),
(5, 'Belkacem', 'Amira', 'F', '1990-01-28', '2018-10-25', 'qahwadji professionnel', 'Analyste', 'CDD', 2, 1, 'Direction IT', 0),
(6, 'GAUTIER', 'Celine', 'F', '1990-02-03', '2019-03-11', 'Licence en Finance', 'Chef de projet', 'CDI', 2, 0, 'Direction Générale', 1),
(8, 'BOICHUT', 'Emelie', 'F', '1990-02-03', '2017-09-14', 'qahwadji professionnel', 'Assistante RH', 'CDI', 4, 1, 'Ressources Humaines', 1),
(9, 'LE MARCHAND', 'Stéphane', 'M', '1990-02-03', '2020-02-20', 'qahwadji professionnel', 'Comptable', 'CDI', 1, 0, 'Direction Financière', 0),
(10, 'COLIN', 'Alexis', 'M', '1990-02-03', '2021-12-05', 'qahwadji professionnel', 'Développeur', 'CDD', 4, 1, 'Direction IT', 1),
(11, 'Fethi', 'Yacine', 'M', '1990-02-01', '2022-01-15', 'qahwadji professionnel', 'Analyste', 'CDI', 1, 0, 'Direction Générale', 1),
(12, 'Abdelkader', 'Mourad', 'M', '1990-02-03', '2016-11-30', 'qahwadji professionnel', 'Technicien', 'CDD', 2, 0, 'Direction ', 1),
(13, 'Nassim', 'Kamelouhhhhhh', 'M', '1990-02-03', '2018-05-12', 'd aghyou', 'Chef de projet', 'CDI', 1, 0, 'Direction IT', 1),
(14, 'Siham', 'Leila', 'F', '1990-02-03', '2019-08-09', 'qahwadji professionnel', 'Assistante RH', 'CDD', 4, 0, 'Ressources Humaines', 1),
(15, 'Nour', 'Malek', 'M', '1990-02-03', '2020-11-13', 'qahwadji professionnel', 'Chargé de communication', 'CDI', 4, 1, 'Direction Générale', 1),
(16, 'ali ', 'libodh', '', '1996-02-27', '2025-01-27', '0', 'SQQ', 'CDI', 1, 0, 'ding', 1),
(17, 'TOUBAL', 'loulouh', '', '2007-01-27', '2013-10-27', '0', 'ittakerAzemmour', 'CDI', 1, 0, 'ittessew aman n la fontaine', 1),
(18, 'TOUBAL', 'loulouh', '', '2007-01-27', '2013-10-27', '0', 'ittakerAzemmour', 'CDI', 1, 0, 'ittessew aman n la fontaine', 1),
(19, 'TOUBAL', 'loulouh', '', '2007-01-27', '2013-10-27', '0', 'ittakerAzemmourdddd', 'CDI', 1, 0, 'ittessew aman n la fontaine', 1),
(20, 'TOUBAL', 'loulouh', '', '2007-01-27', '2013-10-27', '0', 'ittakerAzemmourdddd', 'CDI', 1, 0, 'ittessew aman n la fontaine', 1),
(21, 'TOUBAL', 'Belaid', '', '1972-01-28', '2010-02-02', '0', 'Directeur', 'CDI', 1, 0, 'directeur', 1),
(22, 'TOUBAL', 'Brahim', 'M', '1996-01-16', '2016-04-01', 'ulach', 'directeur', 'CDI', 1, 0, 'Directeur', 1),
(23, 'BOUREKACHE', 'Lvaz', 'M', '2003-09-09', '2021-12-24', 'yechroub lkawkaw', 'plombier', 'CDI', 4, 0, 'plombier', 1),
(24, 'TOUBAL', 'Lamine ', 'M', '2002-07-17', '2025-01-26', 'ddd', 'cuisinier', 'CDD', 1, 0, 'plongeur', 1),
(25, 'TOUBAL', 'Slimanee', 'M', '2003-08-22', '2006-04-12', 'rien', 'plongeur pro', 'CDD', 1, 0, 'tissit baman', 1),
(26, 'OULD ALI', 'Karima ', 'F', '2004-12-16', '2025-01-27', '0', 'mart lem3elem', 'CDI', 1, 0, 'gestion', 1),
(27, 'RAHMOUN', 'Merouane', 'M', '2004-01-02', '2025-02-03', '0', 'ya3ti', 'CDI', 4, 0, 'pipa', 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_notifications_ntfctns`
--

CREATE TABLE `t_notifications_ntfctns` (
  `id_notification` int(11) NOT NULL,
  `destinataire_id` varchar(255) DEFAULT NULL,
  `type_notification` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `etat` enum('Non lue','Lue') DEFAULT 'Non lue',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_notifications_ntfctns`
--

INSERT INTO `t_notifications_ntfctns` (`id_notification`, `destinataire_id`, `type_notification`, `message`, `etat`, `date_creation`) VALUES
(1, 'toubal@ministere.com', 'Nouvelle Demande de Compte', 'Une demande de compte a été créée.', 'Non lue', '2024-12-27 18:00:31'),
(2, 'directeur1@ministere.com', 'Promotion Employé', 'Marie Dupont a reçu une promotion.', 'Lue', '2024-12-27 18:00:31');

-- --------------------------------------------------------

--
-- Structure de la table `t_profils_prfls`
--

CREATE TABLE `t_profils_prfls` (
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `fonction` varchar(100) DEFAULT NULL,
  `etat` enum('A','D') DEFAULT 'D'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_profils_prfls`
--

INSERT INTO `t_profils_prfls` (`pseudo`, `nom`, `prenom`, `date_naissance`, `fonction`, `etat`) VALUES
('directeur1@ministere.com', 'Smith', 'Jane', '1980-05-20', 'Directeur Régional', 'A'),
('rabah.toubal.etudes@gmail.com', 'TOUBAL', 'Rabah', '2003-01-28', 'Directeur Régional', 'A'),
('toubal@ministere.com', 'Toubal', 'Fatyma', '1985-03-15', 'Administrateur Principal', 'A');

-- --------------------------------------------------------

--
-- Structure de la table `t_structures_strcts`
--

CREATE TABLE `t_structures_strcts` (
  `id_structure` int(11) NOT NULL,
  `nom_structure` varchar(150) NOT NULL,
  `type_structure` enum('Direction Générale','Direction Régionale') NOT NULL,
  `directeur_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_structures_strcts`
--

INSERT INTO `t_structures_strcts` (`id_structure`, `nom_structure`, `type_structure`, `directeur_id`) VALUES
(1, 'Direction Générale', 'Direction Générale', 'toubal@ministere.com'),
(2, 'Direction Régionale Nord', 'Direction Régionale', 'directeur1@ministere.com'),
(4, 'Direction Orléans', 'Direction Régionale', 'rabah.toubal.etudes@gmail.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_comptes_cmpts`
--
ALTER TABLE `t_comptes_cmpts`
  ADD PRIMARY KEY (`pseudo`);

--
-- Index pour la table `t_employes_emplys`
--
ALTER TABLE `t_employes_emplys`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `structure_id` (`structure_id`);

--
-- Index pour la table `t_notifications_ntfctns`
--
ALTER TABLE `t_notifications_ntfctns`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Index pour la table `t_profils_prfls`
--
ALTER TABLE `t_profils_prfls`
  ADD PRIMARY KEY (`pseudo`);

--
-- Index pour la table `t_structures_strcts`
--
ALTER TABLE `t_structures_strcts`
  ADD PRIMARY KEY (`id_structure`),
  ADD UNIQUE KEY `nom_structure` (`nom_structure`),
  ADD KEY `directeur_id` (`directeur_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_employes_emplys`
--
ALTER TABLE `t_employes_emplys`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `t_notifications_ntfctns`
--
ALTER TABLE `t_notifications_ntfctns`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_structures_strcts`
--
ALTER TABLE `t_structures_strcts`
  MODIFY `id_structure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_employes_emplys`
--
ALTER TABLE `t_employes_emplys`
  ADD CONSTRAINT `t_employes_emplys_ibfk_1` FOREIGN KEY (`structure_id`) REFERENCES `t_structures_strcts` (`id_structure`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `t_notifications_ntfctns`
--
ALTER TABLE `t_notifications_ntfctns`
  ADD CONSTRAINT `t_notifications_ntfctns_ibfk_1` FOREIGN KEY (`destinataire_id`) REFERENCES `t_comptes_cmpts` (`pseudo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `t_profils_prfls`
--
ALTER TABLE `t_profils_prfls`
  ADD CONSTRAINT `t_profils_prfls_ibfk_1` FOREIGN KEY (`pseudo`) REFERENCES `t_comptes_cmpts` (`pseudo`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `t_structures_strcts`
--
ALTER TABLE `t_structures_strcts`
  ADD CONSTRAINT `t_structures_strcts_ibfk_1` FOREIGN KEY (`directeur_id`) REFERENCES `t_comptes_cmpts` (`pseudo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
