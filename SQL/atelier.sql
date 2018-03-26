-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 23 mars 2018 à 22:38
-- Version du serveur :  10.1.30-MariaDB
-- Version de PHP :  7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `atelier__`
--

-- --------------------------------------------------------

--
-- Structure de la table `atl_category`
--

CREATE TABLE `atl_category` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `atl_credit` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `date_paiement` datetime NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `montant` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `atl_departement`
--

CREATE TABLE `atl_departement` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `atl_event` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `organisateur_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `ville_id` int(11) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `departement_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `date_creation` datetime DEFAULT NULL,
  `date_debut` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_fin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prix` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `nbr_max` int(11) NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codeP` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_event`
--

INSERT INTO `atl_event` (`id`, `category_id`, `organisateur_id`, `utilisateur_id`, `ville_id`, `region_id`, `departement_id`, `titre`, `description`, `date_creation`, `date_debut`, `date_fin`, `prix`, `nbr_max`, `adresse`, `codeP`, `image`) VALUES
(6, 1, NULL, NULL, 1, 1, 1, 'Symfony 3', 'Formation Symfony3', NULL, '24/03/2018 12:00', '31/03/2018 17:00', '150€', 10, 'paris', '1253', 'ca966722257e3205ca2e2efdcc6d0914.jpeg'),
(7, 1, NULL, NULL, 1, 1, 1, 'PHP 5', 'Formation php 5', NULL, '22/04/2018 12:00', '29/04/2018 17:00', '150€', 8, 'paris', '1253', '5d6f1157b59de5d21196b30845c3db74.jpeg'),
(8, 2, NULL, NULL, 1, 1, 3, 'Gâteau au chocolat', 'Secrets de la recette du Gâteau au chocolat', NULL, '27/05/2018 09:00', '29/05/2018 12:00', '90€', 7, 'paris', '1253', 'ec471655f211123f9858b9d06dd9827e.jpeg'),
(9, 3, NULL, NULL, 2, 2, 2, 'Leadership', 'Apprendre les secrets du Leader', NULL, '02/04/2018 09:00', '06/04/2018 12:00', '280€', 10, 'Adresse 1', '125', '2249d68535edba2bc04e989d451ca089.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `atl_gallery`
--

CREATE TABLE `atl_gallery` (
  `id` int(11) NOT NULL,
  `nom` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `atl_image` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_image`
--

INSERT INTO `atl_image` (`id`, `gallery_id`, `titre`, `path`, `alt`) VALUES
(3, 1, 'dev_php', 'https://laracasts.com/images/series/squares/the-php-practitioner.jpg', 'php'),
(4, 3, 'Gateau au chocolat', 'https://www.atelierdeschefs.com/media/recette-e20863-gateau-tout-chocolat.jpg', 'gateau'),
(5, 2, 'web design', 'https://3iplanet.com/wp-content/uploads/2015/05/The-Web-Designing-Best-Web-Design-Company-In-udaipur.jpg', 'web_design'),
(6, 4, 'Pizza au saumon', 'https://www.atelierdeschefs.com/media/recette-e23066-pizza-nordique.jpg', 'pizza'),
(7, 5, 'leadership', 'http://www.sceptreglobal.com/wp-content/uploads/2017/08/leadership.jpg', 'leadership'),
(8, 1, 'dev_php', 'https://laracasts.com/images/series/squares/the-php-practitioner.jpg', 'php'),
(9, 3, 'Gateau au chocolat', 'https://www.atelierdeschefs.com/media/recette-e20863-gateau-tout-chocolat.jpg', 'gateau'),
(10, 2, 'web design', 'https://3iplanet.com/wp-content/uploads/2015/05/The-Web-Designing-Best-Web-Design-Company-In-udaipur.jpg', 'web_design'),
(11, 4, 'Pizza au saumon', 'https://www.atelierdeschefs.com/media/recette-e23066-pizza-nordique.jpg', 'pizza'),
(12, 5, 'leadership', 'http://www.sceptreglobal.com/wp-content/uploads/2017/08/leadership.jpg', 'leadership');

-- --------------------------------------------------------

--
-- Structure de la table `atl_organisateur`
--

CREATE TABLE `atl_organisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `atl_region`
--

CREATE TABLE `atl_region` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `atl_user` (
  `id` int(11) NOT NULL,
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
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_user`
--

INSERT INTO `atl_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(25, 'nadia', 'nadia', 'nadia.hbr@gmail.com', 'nadia.hbr@gmail.com', 1, NULL, '$2y$13$u3SVuJCCQScwzbbgJ74cNek7Y0vDeqnS8qwjZ9e1F5RAgr0ShRCr2', '2018-03-23 22:35:55', NULL, NULL, 'a:2:{i:0;s:10:\"ROLE_ADMIN\";i:1;s:14:\"ROLE_ORGANIZER\";}'),
(41, 'organisateur', 'organisateur', 'organisateur@atelier.com', 'organisateur@atelier.com', 1, NULL, '$2y$13$c821hI17IXeEq94t.q725u6oA51j2y08faPdHvBFfO8wMj6sKeZWS', '2018-03-23 22:24:26', NULL, NULL, 'a:1:{i:0;s:14:\"ROLE_ORGANIZER\";}'),
(42, 'user', 'user', 'user@atelier.com', 'user@atelier.com', 1, NULL, '$2y$13$237o/E8i1mWAc3tmQnZB6uTc2fglCjQWwaKNlwifCm5zCJCZhKCcy', '2018-03-23 22:30:15', NULL, NULL, 'a:0:{}');

-- --------------------------------------------------------

--
-- Structure de la table `atl_ville`
--

CREATE TABLE `atl_ville` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `atl_ville`
--

INSERT INTO `atl_ville` (`id`, `nom`) VALUES
(1, 'Paris'),
(2, 'Lyon'),
(3, 'Montivilliers ');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `atl_category`
--
ALTER TABLE `atl_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atl_credit`
--
ALTER TABLE `atl_credit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F3A761671F7E88B` (`event_id`);

--
-- Index pour la table `atl_departement`
--
ALTER TABLE `atl_departement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atl_event`
--
ALTER TABLE `atl_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C9497F2A12469DE2` (`category_id`),
  ADD KEY `IDX_C9497F2AD936B2FA` (`organisateur_id`),
  ADD KEY `IDX_C9497F2AFB88E14F` (`utilisateur_id`),
  ADD KEY `IDX_C9497F2AA73F0036` (`ville_id`),
  ADD KEY `IDX_C9497F2A98260155` (`region_id`),
  ADD KEY `IDX_C9497F2ACCF9E01E` (`departement_id`);

--
-- Index pour la table `atl_gallery`
--
ALTER TABLE `atl_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atl_image`
--
ALTER TABLE `atl_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_37DA71D24E7AF8F` (`gallery_id`);

--
-- Index pour la table `atl_organisateur`
--
ALTER TABLE `atl_organisateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atl_region`
--
ALTER TABLE `atl_region`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atl_user`
--
ALTER TABLE `atl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8E6151A92FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_8E6151AA0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_8E6151AC05FB297` (`confirmation_token`);

--
-- Index pour la table `atl_ville`
--
ALTER TABLE `atl_ville`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `atl_category`
--
ALTER TABLE `atl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `atl_credit`
--
ALTER TABLE `atl_credit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `atl_departement`
--
ALTER TABLE `atl_departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `atl_event`
--
ALTER TABLE `atl_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `atl_gallery`
--
ALTER TABLE `atl_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `atl_image`
--
ALTER TABLE `atl_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `atl_organisateur`
--
ALTER TABLE `atl_organisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `atl_region`
--
ALTER TABLE `atl_region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `atl_user`
--
ALTER TABLE `atl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `atl_ville`
--
ALTER TABLE `atl_ville`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
