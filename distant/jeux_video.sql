-- phpMyAdmin SQL Dump
-- version 3.0.1.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 25 Février 2009 à 19:55
-- Version du serveur: 5.1.30
-- Version de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `jeux_video`
--
-- Création: Mer 25 Février 2009 à 19:55
-- Dernière modification: Mer 25 Février 2009 à 19:55
--

CREATE TABLE `jeux_video` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `console` varchar(25) NOT NULL DEFAULT '',
  `prix` decimal(10,0) NOT NULL DEFAULT '0',
  `nbre_joueurs_max` tinyint(4) NOT NULL DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Contenu de la table `jeux_video`
--

INSERT INTO `jeux_video` (`ID`, `nom`, `console`, `prix`, `nbre_joueurs_max`) VALUES
(1, 'Super Mario Bros', 'NES', '4', 1),
(2, 'Sonic', 'Megadrive', '2', 1),
(3, 'Zelda : ocarina of time', 'Nintendo 64', '15', 1),
(4, 'Mario Kart 64', 'Nintendo 64', '25', 4),
(5, 'Super Smash Bros Melee', 'GameCube', '55', 4),
(6, 'Dead or Alive', 'Xbox', '60', 4),
(7, 'Dead or Alive Xtreme Beach Volley Ball', 'Xbox', '60', 4),
(8, 'Enter the Matrix', 'PC', '45', 1),
(9, 'Max Payne 2', 'PC', '50', 1),
(10, 'Yoshi''s Island', 'SuperNES', '6', 1),
(11, 'Commandos 3', 'PC', '44', 12),
(12, 'Final Fantasy X', 'PS2', '40', 1),
(13, 'Pokemon Rubis', 'GBA', '44', 4),
(14, 'Starcraft', 'PC', '19', 8),
(15, 'Grand Theft Auto 3', 'PS2', '30', 1),
(16, 'Homeworld 2', 'PC', '45', 6),
(17, 'Aladin', 'SuperNES', '10', 1),
(18, 'Super Mario Bros 3', 'SuperNES', '10', 2),
(19, 'SSX 3', 'Xbox', '56', 2),
(20, 'Star Wars : Jedi outcast', 'Xbox', '33', 1),
(21, 'Actua Soccer 3', 'PS', '30', 2),
(22, 'Time Crisis 3', 'PS2', '40', 1),
(23, 'X-FILES', 'PS', '25', 1),
(24, 'Soul Calibur 2', 'Xbox', '54', 1),
(25, 'Diablo', 'PS', '20', 1),
(26, 'Street Fighter 2', 'Megadrive', '10', 2),
(27, 'Gundam Battle Assault 2', 'PS', '29', 1),
(28, 'Spider-Man', 'Megadrive', '15', 1),
(29, 'Midtown Madness 3', 'Xbox', '59', 6),
(30, 'Tetris', 'Gameboy', '5', 1),
(31, 'The Rocketeer', 'NES', '2', 1),
(32, 'Pro Evolution Soccer 3', 'PS2', '59', 2),
(33, 'Ice Hockey', 'NES', '7', 2),
(34, 'Sydney 2000', 'Dreamcast', '15', 2),
(35, 'NBA 2k', 'Dreamcast', '12', 2),
(36, 'Aliens Versus Predator : Extinction', 'PS2', '20', 2),
(37, 'Crazy Taxi', 'Dreamcast', '11', 1),
(38, 'Le Maillon Faible', 'PS2', '10', 1),
(39, 'FIFA 64', 'Nintendo 64', '25', 2),
(40, 'Qui Veut Gagner Des Millions', 'PS2', '10', 1),
(41, 'Monopoly', 'Nintendo 64', '21', 4),
(42, 'Taxi 3', 'PS2', '19', 4),
(43, 'Indiana Jones Et Le Tombeau De L''Empereur', 'PS2', '25', 1),
(44, 'F-ZERO', 'GBA', '25', 4),
(45, 'Harry Potter Et La Chambre Des Secrets', 'Xbox', '30', 1),
(46, 'Half-Life', 'PC', '15', 32),
(47, 'Myst III Exile', 'Xbox', '49', 1),
(48, 'Wario World', 'Gamecube', '40', 4),
(49, 'Rollercoaster Tycoon', 'Xbox', '29', 1),
(50, 'Splinter Cell', 'Xbox', '53', 1);
