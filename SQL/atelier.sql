-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 05 fév. 2018 à 15:16
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `atelier`
--

-- --------------------------------------------------------

--
-- Structure de la table `atl_category`
--

DROP TABLE IF EXISTS `atl_category`;
CREATE TABLE IF NOT EXISTS `atl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_category`
--

INSERT INTO `atl_category` (`id`, `nom`, `description`) VALUES
(1, 'web', NULL),
(2, 'Pâtisserie', NULL),
(3, 'Développement personnel ', NULL),
(4, 'Beaux Arts', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `atl_credit`
--

DROP TABLE IF EXISTS `atl_credit`;
CREATE TABLE IF NOT EXISTS `atl_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `date_paiement` datetime NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F3A761671F7E88B` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `atl_departement`
--

DROP TABLE IF EXISTS `atl_departement`;
CREATE TABLE IF NOT EXISTS `atl_departement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_departement`
--

INSERT INTO `atl_departement` (`id`, `nom`, `description`) VALUES
(1, 'Informatique', NULL),
(2, 'PNL', NULL),
(3, 'Cuisine', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `atl_event`
--

DROP TABLE IF EXISTS `atl_event`;
CREATE TABLE IF NOT EXISTS `atl_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `organisateur_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `region_id` int(11) NOT NULL,
  `departement_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `date_creation` date DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `prix` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `nbr_max` int(11) NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codeP` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ville_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C9497F2A3DA5256D` (`image_id`),
  KEY `IDX_C9497F2A12469DE2` (`category_id`),
  KEY `IDX_C9497F2AD936B2FA` (`organisateur_id`),
  KEY `IDX_C9497F2AFB88E14F` (`utilisateur_id`),
  KEY `IDX_C9497F2A98260155` (`region_id`),
  KEY `IDX_C9497F2ACCF9E01E` (`departement_id`),
  KEY `IDX_C9497F2AA73F0036` (`ville_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_event`
--

INSERT INTO `atl_event` (`id`, `category_id`, `organisateur_id`, `utilisateur_id`, `region_id`, `departement_id`, `image_id`, `titre`, `description`, `date_creation`, `date_debut`, `date_fin`, `heure_debut`, `heure_fin`, `prix`, `nbr_max`, `adresse`, `codeP`, `ville_id`) VALUES
(1, 1, NULL, NULL, 1, 1, 3, 'Développement PHP', NULL, NULL, '2018-02-11', '2018-03-17', '12:00:00', '18:00:00', '250€', 10, 'Paris', NULL, 1),
(2, 3, NULL, NULL, 3, 2, 7, 'Leadership', NULL, NULL, '2018-02-11', '2018-02-28', '09:00:00', '12:00:00', '220€', 8, 'France', NULL, 3),
(3, 2, NULL, NULL, 1, 3, 4, 'Astuces délicieuses', NULL, NULL, '2018-02-18', '2018-03-27', '09:00:00', '12:00:00', '180€', 10, 'france', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `atl_gallery`
--

DROP TABLE IF EXISTS `atl_gallery`;
CREATE TABLE IF NOT EXISTS `atl_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_gallery`
--

INSERT INTO `atl_gallery` (`id`, `nom`) VALUES
(1, 'developpement'),
(2, 'design'),
(3, 'Gateaux'),
(4, 'pizza'),
(5, 'Leader');

-- --------------------------------------------------------

--
-- Structure de la table `atl_image`
--

DROP TABLE IF EXISTS `atl_image`;
CREATE TABLE IF NOT EXISTS `atl_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_37DA71D24E7AF8F` (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_image`
--

INSERT INTO `atl_image` (`id`, `gallery_id`, `titre`, `path`, `alt`) VALUES
(3, 1, 'dev_php', 'https://laracasts.com/images/series/squares/the-php-practitioner.jpg', 'php'),
(4, 3, 'Gateau au chocolat', 'https://www.atelierdeschefs.com/media/recette-e20863-gateau-tout-chocolat.jpg', 'gateau'),
(5, 2, 'web design', 'https://3iplanet.com/wp-content/uploads/2015/05/The-Web-Designing-Best-Web-Design-Company-In-udaipur.jpg', 'web_design'),
(6, 4, 'Pizza au saumon', 'https://www.atelierdeschefs.com/media/recette-e23066-pizza-nordique.jpg', 'pizza'),
(7, 5, 'leadership', 'http://www.sceptreglobal.com/wp-content/uploads/2017/08/leadership.jpg', 'leadership');

-- --------------------------------------------------------

--
-- Structure de la table `atl_organisateur`
--

DROP TABLE IF EXISTS `atl_organisateur`;
CREATE TABLE IF NOT EXISTS `atl_organisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `atl_region`
--

DROP TABLE IF EXISTS `atl_region`;
CREATE TABLE IF NOT EXISTS `atl_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_region`
--

INSERT INTO `atl_region` (`id`, `nom`) VALUES
(1, 'Île-de-France'),
(2, 'Auvergne-Rhône-Alpes'),
(3, 'Haute-Normandie');

-- --------------------------------------------------------

--
-- Structure de la table `atl_user`
--

DROP TABLE IF EXISTS `atl_user`;
CREATE TABLE IF NOT EXISTS `atl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `facebook_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8E6151A92FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8E6151AA0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_8E6151AC05FB297` (`confirmation_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `atl_ville`
--

DROP TABLE IF EXISTS `atl_ville`;
CREATE TABLE IF NOT EXISTS `atl_ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_ville`
--

INSERT INTO `atl_ville` (`id`, `nom`) VALUES
(1, 'Paris'),
(2, 'Lyon'),
(3, 'Montivilliers ');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `atl_credit`
--
ALTER TABLE `atl_credit`
  ADD CONSTRAINT `FK_8F3A761671F7E88B` FOREIGN KEY (`event_id`) REFERENCES `atl_event` (`id`);

--
-- Contraintes pour la table `atl_event`
--
ALTER TABLE `atl_event`
  ADD CONSTRAINT `FK_C9497F2A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `atl_category` (`id`),
  ADD CONSTRAINT `FK_C9497F2A3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `atl_image` (`id`),
  ADD CONSTRAINT `FK_C9497F2A98260155` FOREIGN KEY (`region_id`) REFERENCES `atl_region` (`id`),
  ADD CONSTRAINT `FK_C9497F2AA73F0036` FOREIGN KEY (`ville_id`) REFERENCES `atl_ville` (`id`),
  ADD CONSTRAINT `FK_C9497F2ACCF9E01E` FOREIGN KEY (`departement_id`) REFERENCES `atl_departement` (`id`),
  ADD CONSTRAINT `FK_C9497F2AD936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `atl_organisateur` (`id`),
  ADD CONSTRAINT `FK_C9497F2AFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `atl_user` (`id`);

--
-- Contraintes pour la table `atl_image`
--
ALTER TABLE `atl_image`
  ADD CONSTRAINT `FK_37DA71D24E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `atl_gallery` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
