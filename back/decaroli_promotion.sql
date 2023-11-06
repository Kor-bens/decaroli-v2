-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 06 nov. 2023 à 14:00
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `decaroli_promotion`
--

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id_image` int NOT NULL AUTO_INCREMENT,
  `nom_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_page` int NOT NULL,
  PRIMARY KEY (`id_image`),
  KEY `id_page` (`id_page`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id_image`, `nom_image`, `url`, `id_page`) VALUES
(244, 'abcDerm.png', '654121abde875_abcDerm.png', 1),
(245, 'bbKlorane.png', '65416d66404ff_bbKlorane.png', 1),
(246, 'poupina.png', '65416d71c9ef4_poupina.png', 1),
(247, 'weledabb.png', '65416d7c1316b_weledabb.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `id_page` int NOT NULL,
  `titre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_font_size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_font_size_grand_ecran` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_font_size_moyen_ecran` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_font_size_petit_ecran` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_font_family` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bkgd_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `id_role` (`id_role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page`
--

INSERT INTO `page` (`id_page`, `titre`, `titre_font_size`, `titre_font_size_grand_ecran`, `titre_font_size_moyen_ecran`, `titre_font_size_petit_ecran`, `titre_font_family`, `titre_color`, `bkgd_color`, `id_role`) VALUES
(1, 'promotion du mois de mars', '50px', '70px', '60px', '40px', 'Montserrat', 'white', 'radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);', 2);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `nom_role`) VALUES
(1, 'administrateur'),
(2, 'editeur'),
(3, 'client');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  KEY `id_role` (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `mdp`, `mail`, `id_role`) VALUES
(1, 'Roland', '132c57d71ec31c0f0e8d37be5a02740041f43c58de23ea104e88d17947bbf23e5e462bcf75135e2de3e975f933b504ed7f7e4975aeba527f1c4b873931281a08', 'r.provensal@comnco.com', 2),
(2, 'titi', 'f69624921ee6583a0c42e446f6f87d055da032f93d3d1c2b281d4d246727dd48d2ad907f7ca16a96191e2c1f59f5642b4b04792df7da1309f7488fd3f17ce2f4', 'titi@titi.com', 1),
(3, 'toto', '10e06b990d44de0091a2113fd95c92fc905166af147aa7632639c41aa7f26b1620c47443813c605b924c05591c161ecc35944fc69c4433a49d10fc6b04a33611', 'toto@toto.com', 2),
(4, 'tata', '7759c425e452e4e809d194084601097168236325736c3911badb429d34a962af9ce681a43e719b7a5d70144dfafbd7bb1402a55aee734000b83ea1a14c7459d3', 'tata@tata.com', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
