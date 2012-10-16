-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- Serveur: 127.0.0.1
-- Généré le : Mer 15 Septembre 2010 à 20:23
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `tarot`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE IF NOT EXISTS `joueur` (
  `login` varchar(16) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `pic` text,
  PRIMARY KEY (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `joueur`
--

INSERT INTO `joueur` (`login`, `password`, `pic`) VALUES
('TZS', 'taroot', NULL),
('TXO', 'taroot', NULL),
('ZIN', 'taroot', NULL),
('ZJT', 'taroot', NULL),
('JJ4', 'taroot', NULL),
('CP7', 'taroot', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE IF NOT EXISTS `participation` (
  `Poignee` tinyint(4) NOT NULL DEFAULT '0',
  `Misere` tinyint(4) NOT NULL DEFAULT '0',
  `DoublePoignee` tinyint(4) NOT NULL DEFAULT '0',
  `TriplePoignee` tinyint(4) NOT NULL DEFAULT '0',
  `Position` enum('Preneur','Equipier','Defenseur') NOT NULL DEFAULT 'Preneur',
  `LoginParticipant` varchar(32) NOT NULL DEFAULT '',
  `IDPartie` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `participation`
--

INSERT INTO `participation` (`Poignee`, `Misere`, `DoublePoignee`, `TriplePoignee`, `Position`, `LoginParticipant`, `IDPartie`) VALUES
(0, 0, 0, 0, 'Defenseur', 'ZJT', 31),
(1, 0, 0, 0, 'Defenseur', 'TZS', 31),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 31),
(0, 0, 0, 0, 'Preneur', 'TXO', 31),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 33),
(0, 0, 0, 0, 'Defenseur', 'TZS', 33),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 33),
(1, 0, 0, 0, 'Equipier', 'CP7', 33),
(0, 0, 0, 0, 'Preneur', 'TXO', 33),
(0, 1, 0, 0, 'Defenseur', 'ZIN', 32),
(0, 0, 0, 0, 'Defenseur', 'TZS', 32),
(1, 0, 0, 0, 'Defenseur', 'TXO', 32),
(0, 0, 0, 0, 'Equipier', 'JJ4', 32),
(0, 0, 0, 0, 'Preneur', 'CP7', 32),
(0, 0, 0, 0, 'Preneur', 'TXO', 34),
(0, 0, 0, 0, 'Equipier', 'CP7', 34),
(0, 0, 0, 0, 'Defenseur', 'TZS', 34),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 34),
(0, 0, 0, 0, 'Defenseur', 'ZIN', 34),
(0, 0, 0, 0, 'Preneur', 'TXO', 35),
(0, 0, 0, 0, 'Equipier', 'CP7', 35),
(0, 0, 0, 0, 'Defenseur', 'TZS', 35),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 35),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 35),
(0, 0, 0, 0, 'Preneur', 'CP7', 36),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 36),
(0, 0, 0, 0, 'Defenseur', 'TXO', 36),
(0, 0, 0, 0, 'Defenseur', 'TZS', 36),
(0, 0, 0, 0, 'Preneur', 'CP7', 37),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 37),
(0, 0, 0, 0, 'Defenseur', 'ZIN', 37),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 37),
(0, 0, 0, 0, 'Preneur', 'ZJT', 38),
(0, 0, 0, 0, 'Equipier', 'ZIN', 38),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 38),
(0, 0, 0, 0, 'Defenseur', 'TXO', 38),
(0, 0, 0, 0, 'Defenseur', 'TZS', 38),
(0, 0, 0, 0, 'Preneur', 'TXO', 39),
(0, 0, 0, 0, 'Equipier', 'JJ4', 39),
(0, 0, 0, 0, 'Defenseur', 'TZS', 39),
(0, 0, 0, 0, 'Defenseur', 'ZIN', 39),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 39),
(0, 0, 0, 0, 'Preneur', 'ZIN', 40),
(0, 0, 0, 0, 'Equipier', 'TXO', 40),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 40),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 40),
(0, 0, 0, 0, 'Defenseur', 'TZS', 40),
(0, 0, 0, 0, 'Preneur', 'TZS', 41),
(0, 0, 0, 0, 'Equipier', 'ZIN', 41),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 41),
(0, 0, 0, 0, 'Defenseur', 'TXO', 41),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 41),
(0, 0, 0, 0, 'Preneur', 'ZJT', 42),
(0, 0, 0, 0, 'Equipier', 'JJ4', 42),
(0, 0, 0, 0, 'Defenseur', 'TXO', 42),
(0, 1, 0, 0, 'Defenseur', 'TZS', 42),
(0, 0, 0, 0, 'Defenseur', 'ZIN', 42),
(0, 0, 0, 0, 'Preneur', 'ZIN', 43),
(0, 0, 0, 0, 'Equipier', 'ZJT', 43),
(0, 0, 0, 0, 'Defenseur', 'TXO', 43),
(0, 0, 0, 0, 'Defenseur', 'TZS', 43),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 43),
(0, 0, 0, 0, 'Preneur', 'TZS', 44),
(0, 0, 0, 0, 'Equipier', 'JJ4', 44),
(0, 0, 0, 0, 'Defenseur', 'TXO', 44),
(0, 0, 0, 0, 'Defenseur', 'ZIN', 44),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 44),
(0, 0, 0, 0, 'Preneur', 'JJ4', 45),
(0, 0, 0, 0, 'Equipier', 'ZIN', 45),
(0, 0, 0, 0, 'Defenseur', 'TXO', 45),
(0, 0, 0, 0, 'Defenseur', 'TZS', 45),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 45),
(0, 0, 0, 0, 'Preneur', 'TZS', 46),
(0, 0, 0, 0, 'Equipier', 'ZIN', 46),
(0, 0, 0, 0, 'Defenseur', 'TXO', 46),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 46),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 46),
(0, 0, 0, 0, 'Preneur', 'ZIN', 47),
(0, 0, 0, 0, 'Defenseur', 'JJ4', 47),
(0, 0, 0, 0, 'Defenseur', 'TXO', 47),
(0, 0, 0, 0, 'Defenseur', 'TZS', 47),
(0, 0, 0, 0, 'Defenseur', 'ZJT', 47);

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

CREATE TABLE IF NOT EXISTS `partie` (
  `IDPartie` bigint(20) NOT NULL AUTO_INCREMENT,
  `DatePartie` date NOT NULL DEFAULT '0000-00-00',
  `NomSession` varchar(128) NOT NULL DEFAULT '',
  `ScorePartie` smallint(6) NOT NULL DEFAULT '0',
  `NbBoutsPartie` smallint(6) NOT NULL DEFAULT '0',
  `LoginPreneur` varchar(16) NOT NULL DEFAULT '',
  `LoginEquipier` varchar(16) DEFAULT NULL,
  `ContratPartie` enum('Petite','Garde','GardeSans','GardeContre') NOT NULL DEFAULT 'Petite',
  `ChelemAnnonce` tinyint(4) NOT NULL DEFAULT '0',
  `ChelemReussi` tinyint(4) NOT NULL DEFAULT '0',
  `LoginPetitAuBout` varchar(32) NOT NULL DEFAULT '',
  `PetitAuBoutReussi` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDPartie`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Contenu de la table `partie`
--

INSERT INTO `partie` (`IDPartie`, `DatePartie`, `NomSession`, `ScorePartie`, `NbBoutsPartie`, `LoginPreneur`, `LoginEquipier`, `ContratPartie`, `ChelemAnnonce`, `ChelemReussi`, `LoginPetitAuBout`, `PetitAuBoutReussi`) VALUES
(42, '2010-09-14', 'Septembre2010', 50, 2, 'ZJT', 'JJ4', 'Petite', 0, 0, 'Aucun', 1),
(41, '2010-09-14', 'Septembre2010', 55, 1, 'TZS', 'ZIN', 'Petite', 0, 0, 'Aucun', 1),
(40, '2010-09-14', 'Septembre2010', 51, 3, 'ZIN', 'TXO', 'Petite', 0, 0, 'Aucun', 1),
(39, '2010-09-14', 'Septembre2010', 39, 3, 'TXO', 'JJ4', 'Petite', 0, 0, 'Aucun', 1),
(37, '2010-09-13', 'Test', 30, 1, 'CP7', 'Aucun', 'Petite', 0, 0, 'Aucun', 1),
(38, '2010-09-14', 'Septembre2010', 44, 3, 'ZJT', 'ZIN', 'Petite', 0, 0, 'Aucun', 1),
(36, '2010-09-13', 'Test', 55, 2, 'CP7', 'Aucun', 'Garde', 0, 0, 'Aucun', 1),
(35, '2010-09-13', 'Test', 45, 2, 'TXO', 'CP7', 'Petite', 0, 0, 'Aucun', 1),
(34, '2010-09-13', 'Test', 55, 0, 'TXO', 'CP7', 'Garde', 0, 0, 'Aucun', 1),
(31, '2010-09-12', 'Test', 40, 2, 'TXO', 'Aucun', 'Garde', 0, 0, 'JJ4', 0),
(33, '2010-09-13', 'Test', 47, 1, 'TXO', 'CP7', 'Petite', 0, 0, 'JJ4', 1),
(32, '2010-09-12', 'Test', 39, 3, 'CP7', 'JJ4', 'Petite', 0, 0, 'CP7', 1),
(43, '2010-09-15', 'Septembre2010', 68, 2, 'ZIN', 'ZJT', 'Garde', 0, 0, 'Aucun', 1),
(44, '2010-09-15', 'Septembre2010', 22, 2, 'TZS', 'JJ4', 'Petite', 0, 0, 'Aucun', 1),
(45, '2010-09-15', 'Septembre2010', 61, 2, 'JJ4', 'ZIN', 'Petite', 0, 0, 'Aucun', 1),
(46, '2010-09-15', 'Septembre2010', 57, 3, 'TZS', 'ZIN', 'Garde', 0, 0, 'TZS', 1),
(47, '2010-09-15', 'Septembre2010', 26, 2, 'ZIN', 'Aucun', 'Petite', 0, 0, 'Aucun', 1);

-- --------------------------------------------------------

--
-- Structure de la table `phrasedujour`
--

CREATE TABLE IF NOT EXISTS `phrasedujour` (
  `Phrase` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `phrasedujour`
--

INSERT INTO `phrasedujour` (`Phrase`) VALUES
('Ta mère le dinosaure assis.'),
('Le saviez vous : la chatte de Bertrand est composée à 95% de matière noire et concentre près de 80% de l''énergie totale contenue dans notre galaxie.'),
('Tortues ninja tortues ninja tortues ninja tortues ninja tortues ninja tortues ninja'),
('Renaud est sur le point de signer chez Durex au poste de directeur qualité en charge des essais.'),
('Ne jamais rouler en Fiat Brava, jamais jamais jamais.'),
('Salut les moiz, ça biche ou bien ?'),
('stupid chili leather'),
('Garde sans, mort au tournant.'),
('-1000 paye un pot (hein Grégoire).'),
('+1000 paye un pot (hein Bertrand).'),
('Le Mont Saint-Michel est le joyau de la Bretagne.'),
('Le Mont Saint-Michel est le joyau de la Normandie.'),
('Vélizy, ville de lumière.'),
('Le 3D ouvre les portes du monde que tu imagines.'),
('Si Bertrand prend, emprunte le chemin boueux (proverbe chinois).');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `NomSession` varchar(128) NOT NULL DEFAULT '',
  `DateSession` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`NomSession`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `session`
--

INSERT INTO `session` (`NomSession`, `DateSession`) VALUES
('Test', '2010-09-11'),
('Septembre2010', '2010-09-13');
