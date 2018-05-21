-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 21 Mai 2018 à 15:03
-- Version du serveur :  5.7.22-0ubuntu0.16.04.1
-- Version de PHP :  7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `woops`
--

-- --------------------------------------------------------

--
-- Structure de la table `oauth_access_tokens`
--

CREATE TABLE `OAUTH_ACCESS_TOKENS` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_authorization_codes`
--

CREATE TABLE `OAUTH_AUTHORIZATION_CODES` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_clients`
--

CREATE TABLE `OAUTH_CLIENTS` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) NOT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(100) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oauth_clients`
--

INSERT INTO `OAUTH_CLIENTS` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
('WOOPS', 'WOOPS', 'woops://oath', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `oauth_jwt`
--

CREATE TABLE `OAUTH_JWT` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_refresh_tokens`
--

CREATE TABLE `OAUTH_REFRESH_TOKENS` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_scopes`
--

CREATE TABLE `OAUTH_SCOPES` (
  `scope` text,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `oauth_access_tokens`
--
ALTER TABLE `OAUTH_ACCESS_TOKENS`
  ADD PRIMARY KEY (`access_token`);

--
-- Index pour la table `oauth_authorization_codes`
--
ALTER TABLE `OAUTH_AUTHORIZATION_CODES`
  ADD PRIMARY KEY (`authorization_code`);

--
-- Index pour la table `oauth_clients`
--
ALTER TABLE `OAUTH_CLIENTS`
  ADD PRIMARY KEY (`client_id`);

--
-- Index pour la table `oauth_jwt`
--
ALTER TABLE `OAUTH_JWT`
  ADD PRIMARY KEY (`client_id`);

--
-- Index pour la table `oauth_refresh_tokens`
--
ALTER TABLE `OAUTH_REFRESH_TOKENS`
  ADD PRIMARY KEY (`refresh_token`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
