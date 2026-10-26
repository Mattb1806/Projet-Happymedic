-- ============================================================
-- HappyMedic - Base de données "medi"
-- Reconstituée à partir des captures phpMyAdmin du projet.
-- Données : comptes de DÉMONSTRATION uniquement (aucune donnée réelle).
--
-- Utilisation : dans phpMyAdmin, onglet "Importer", choisir ce fichier.
-- Attention : les tables existantes sont supprimées puis recréées.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `medi` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `medi`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ------------------------------------------------------------
-- Table `personne` : utilisateurs (chef, médecins, patients)
-- statut : 'manitou' (chef), 'medecin' ou 'patient'
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `personne`;
CREATE TABLE `personne` (
  `nom` varchar(128) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(128) COLLATE utf8_bin NOT NULL,
  `email` varchar(128) COLLATE utf8_bin NOT NULL,
  `mdp` varchar(128) COLLATE utf8_bin NOT NULL,
  `statut` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT 'patient',
  `id_personne` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_personne`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `personne` (`nom`, `prenom`, `email`, `mdp`, `statut`, `id_personne`) VALUES
('le gaulois', 'asterix', 'ag@tf1.fr', 'pwdasterix', 'manitou', 1),
('le gaulois', 'obelix', 'og@tf1.fr', 'obelix', 'medecin', 2),
('ramix', 'pano', 'pano@tf1.fr', 'pano', 'medecin', 3),
('Cesar', 'Jules', 'jc@tf1.fr', 'cesar', 'patient', 4);

-- ------------------------------------------------------------
-- Table `ordonnance` : prescriptions
-- Une ligne = les quantités de chaque médicament (s1, s2, v, b, r,
-- j, c1, c2) à délivrer au patient id_personne.
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `ordonnance`;
CREATE TABLE `ordonnance` (
  `s1` int(11) NOT NULL DEFAULT 0,
  `s2` int(11) NOT NULL DEFAULT 0,
  `v` int(11) NOT NULL DEFAULT 0,
  `b` int(11) NOT NULL DEFAULT 0,
  `r` int(11) NOT NULL DEFAULT 0,
  `j` int(11) NOT NULL DEFAULT 0,
  `c1` int(11) NOT NULL DEFAULT 0,
  `c2` int(11) NOT NULL DEFAULT 0,
  `id_personne` int(11) NOT NULL,
  `id_ordonnance` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_ordonnance`),
  KEY `id_personne` (`id_personne`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `ordonnance` (`s1`, `s2`, `v`, `b`, `r`, `j`, `c1`, `c2`, `id_personne`, `id_ordonnance`) VALUES
(270, 540, 3, 4, 6, 5, 1, 1, 1, 4),
(270, 540, 3, 4, 6, 5, 1, 1, 2, 5),
(540, 810, 4, 9, 5, 4, 1, 1, 3, 6);

-- ------------------------------------------------------------
-- Table `stock` : médicaments disponibles dans le distributeur
-- nom : code du médicament (s1, s2, v, b, r, j, c1, c2)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `nmed` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(32) COLLATE utf8_bin NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`nmed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `stock` (`nmed`, `nom`, `quantite`) VALUES
(1, 's1', 4),
(2, 's2', 10),
(3, 'v', 3),
(4, 'b', 8),
(5, 'r', 6),
(6, 'j', 15),
(7, 'c1', 64),
(9, 'c2', 45);
