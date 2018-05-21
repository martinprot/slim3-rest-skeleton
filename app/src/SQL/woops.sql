-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 21 Mai 2018 à 15:05
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
-- Structure de la table `CONTENT`
--

CREATE TABLE `CONTENT` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('MOVIE','TV') NOT NULL,
  `title` varchar(100) NOT NULL,
  `id_TMDB` int(10) UNSIGNED DEFAULT NULL,
  `runtime` int(10) UNSIGNED DEFAULT NULL,
  `original_language` varchar(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_EPISODE`
--

CREATE TABLE `CONTENT_EPISODE` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_CONTENT` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `id_TMDB` int(10) UNSIGNED NOT NULL,
  `episode_number` tinyint(3) UNSIGNED NOT NULL,
  `season_number` tinyint(3) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_PLATEFORM`
--

CREATE TABLE `CONTENT_PLATEFORM` (
  `id_CONTENT` int(10) UNSIGNED NOT NULL,
  `id_PLATEFORM` smallint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_TRADUCTION`
--

CREATE TABLE `CONTENT_TRADUCTION` (
  `id_CONTENT` int(10) UNSIGNED NOT NULL,
  `id_CONTENT_EPISODE` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(2) NOT NULL,
  `title` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ISSUE`
--

CREATE TABLE `ISSUE` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_CONTENT` int(10) UNSIGNED NOT NULL,
  `id_CONTENT_EPISODE` int(10) UNSIGNED DEFAULT NULL,
  `id_ISSUE_TYPE` smallint(5) UNSIGNED NOT NULL,
  `date_issue` date NOT NULL,
  `time` int(11) NOT NULL,
  `verbatim` varchar(250) DEFAULT NULL,
  `language` varchar(2) NOT NULL COMMENT 'iso 639-1',
  `subtitle` varchar(2) DEFAULT NULL,
  `id_USER_PLATEFORM_SUPPORT` int(10) UNSIGNED NOT NULL,
  `id_ISSUE_PARENT` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ISSUE_BACKING`
--

CREATE TABLE `ISSUE_BACKING` (
  `id` int(10) NOT NULL,
  `id_ISSUE` int(10) UNSIGNED NOT NULL,
  `id_USER_PLATEFORM_SUPPORT` int(10) UNSIGNED NOT NULL,
  `date_backing` date NOT NULL,
  `rate` tinyint(4) NOT NULL,
  `status` enum('OK','NOT POSSIBLE','NOT VISIBLE') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ISSUE_TYPE`
--

CREATE TABLE `ISSUE_TYPE` (
  `id` smallint(10) UNSIGNED NOT NULL,
  `category` enum('INTERFACE','VIDEO','AUDIO','SUBTITLE','OTHER') NOT NULL,
  `sub_category` varchar(50) NOT NULL,
  `explanation` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `PLATEFORM`
--

CREATE TABLE `PLATEFORM` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `code_country` varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `RANK`
--

CREATE TABLE `RANK` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `rules` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `USER`
--

CREATE TABLE `USER` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code_country` varchar(2) NOT NULL,
  `language` varchar(2) NOT NULL COMMENT 'iso 639-1',
  `date_subscription` date NOT NULL,
  `date_birth` date DEFAULT NULL,
  `id_RANK` smallint(8) UNSIGNED DEFAULT NULL,
  `device_uuid` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `USER_PLATEFORM_SUPPORT`
--

CREATE TABLE `USER_PLATEFORM_SUPPORT` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_USER` int(10) UNSIGNED NOT NULL,
  `id_PLATEFORM` smallint(10) UNSIGNED NOT NULL,
  `SUPPORT` enum('FIXE','MOBILE') NOT NULL,
  `date_insert` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_idTMDB` (`id_TMDB`);

--
-- Index pour la table `CONTENT_EPISODE`
--
ALTER TABLE `CONTENT_EPISODE`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CONTENT` (`id_CONTENT`),
  ADD KEY `IDX_TMDB` (`id_TMDB`);

--
-- Index pour la table `CONTENT_PLATEFORM`
--
ALTER TABLE `CONTENT_PLATEFORM`
  ADD PRIMARY KEY (`id_CONTENT`,`id_PLATEFORM`);

--
-- Index pour la table `CONTENT_TRADUCTION`
--
ALTER TABLE `CONTENT_TRADUCTION`
  ADD PRIMARY KEY (`id_CONTENT`,`id_CONTENT_EPISODE`,`language`) USING BTREE;

--
-- Index pour la table `ISSUE`
--
ALTER TABLE `ISSUE`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CONTENT` (`id_CONTENT`),
  ADD KEY `IDX_ISSUETYPE` (`id_ISSUE_TYPE`),
  ADD KEY `IDX_UPS` (`id_USER_PLATEFORM_SUPPORT`);

--
-- Index pour la table `ISSUE_BACKING`
--
ALTER TABLE `ISSUE_BACKING`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ISSUE_TYPE`
--
ALTER TABLE `ISSUE_TYPE`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `PLATEFORM`
--
ALTER TABLE `PLATEFORM`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_COUNTRY` (`code_country`);

--
-- Index pour la table `RANK`
--
ALTER TABLE `RANK`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `IDX_RANK` (`id_RANK`),
  ADD KEY `IDX_COUNTRY` (`code_country`) USING BTREE;

--
-- Index pour la table `USER_PLATEFORM_SUPPORT`
--
ALTER TABLE `USER_PLATEFORM_SUPPORT`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNK_USER_PLAT_SUPP` (`id_USER`,`id_PLATEFORM`,`SUPPORT`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `CONTENT_EPISODE`
--
ALTER TABLE `CONTENT_EPISODE`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ISSUE_BACKING`
--
ALTER TABLE `ISSUE_BACKING`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ISSUE_TYPE`
--
ALTER TABLE `ISSUE_TYPE`
  MODIFY `id` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `PLATEFORM`
--
ALTER TABLE `PLATEFORM`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `RANK`
--
ALTER TABLE `RANK`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `USER`
--
ALTER TABLE `USER`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `USER_PLATEFORM_SUPPORT`
--
ALTER TABLE `USER_PLATEFORM_SUPPORT`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


INSERT INTO `USER` (`id`, `email`, `password`, `name`, `code_country`, `language`, `date_subscription`, `date_birth`, `id_RANK`, `device_uuid`) VALUES
(1, 'martin@yopmail.com', '563d2c0b1213d3fd1fd5aa521ef7511f5d88dfae', 'martin', 'FR', 'FR', '2018-05-21', NULL, NULL, NULL);
-- password is SHA1("yopmail")