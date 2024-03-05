-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 04 mars 2024 à 15:25
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sonia`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `aftDernierRdvAdresse`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `aftDernierRdvAdresse` (IN `idrdv` INTEGER, IN `idadresse` INTEGER)   BEGIN DECLARE id_adresseMax INTEGER; DECLARE id_dernierRdvAct INTEGER; SELECT id into id_adresseMax from rendez_vous where adresse_id = idadresse and date_controle = (SELECT MAX(date_controle) from rendez_vous where adresse_id = idadresse) LIMIT 0, 1; UPDATE adresse SET dernier_rdv_id = id_adresseMax where id = idadresse; END$$

DROP PROCEDURE IF EXISTS `aftDernierRdvClient`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `aftDernierRdvClient` (IN `idrdv` INTEGER, IN `idclient` INTEGER)   BEGIN DECLARE id_clientMax INTEGER; DECLARE id_dernierRdvAct INTEGER; SELECT id into id_clientMax from rendez_vous where client_id = idclient and date_controle = (SELECT MAX(date_controle) from rendez_vous where client_id = idclient) LIMIT 0, 1; UPDATE client SET dernier_rdv_id = id_clientMax where id = idclient; END$$

DROP PROCEDURE IF EXISTS `befDernierRdvAdresse`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `befDernierRdvAdresse` (IN `idrdv` INTEGER)   BEGIN DECLARE id_adresseDer INTEGER; SELECT id INTO id_adresseDer FROM adresse WHERE dernier_rdv_id  = idrdv ; IF ( id_adresseDer is not null ) THEN UPDATE adresse SET dernier_rdv_id = (SELECT id from rendez_vous where adresse_id = id_adresseDer and id != idrdv and date_controle = (SELECT MAX(date_controle) from rendez_vous where adresse_id = id_adresseDer and id != idrdv) LIMIT 0, 1) WHERE id = id_adresseDer; END IF; END$$

DROP PROCEDURE IF EXISTS `befDernierRdvClient`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `befDernierRdvClient` (IN `idrdv` INTEGER)   BEGIN DECLARE id_clientDer INTEGER;SELECT id INTO id_clientDer FROM client WHERE dernier_rdv_id  = idrdv; IF ( id_clientDer is not null ) THEN UPDATE client SET dernier_rdv_id = (SELECT id from rendez_vous where client_id = id_clientDer and id != idrdv and date_controle = (SELECT MAX(date_controle) from rendez_vous where client_id = id_clientDer and id != idrdv) LIMIT 0, 1) WHERE id = id_clientDer; END IF; END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
CREATE TABLE IF NOT EXISTS `adresse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dernier_rdv_id` int DEFAULT NULL,
  `prochaine_visite` date DEFAULT NULL,
  `numero` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commune` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_cadastrale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ancienne_adresse` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C35F08166027C82A` (`dernier_rdv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dernier_rdv_id` int DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_client` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp_client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C74404556027C82A` (`dernier_rdv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240220101548', '2024-03-04 15:19:27', 305),
('DoctrineMigrations\\Version20240223092644', '2024-03-04 15:19:27', 20);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

DROP TABLE IF EXISTS `rendez_vous`;
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `adresse_id` int DEFAULT NULL,
  `facturation` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_facturation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_controle` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_dossier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_controle` date DEFAULT NULL,
  `type_traitement` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_installation` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejet_inf` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conformite` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `impact` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_rpqs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_facturation` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp_facturation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commune_facturation` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_propriaitaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom_propriaitaire` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_65E8AA0A19EB6921` (`client_id`),
  KEY `IDX_65E8AA0A4DE7DC5C` (`adresse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déclencheurs `rendez_vous`
--
DROP TRIGGER IF EXISTS `delDernierRdvEntite`;
DELIMITER $$
CREATE TRIGGER `delDernierRdvEntite` BEFORE DELETE ON `rendez_vous` FOR EACH ROW BEGIN IF (OLD.client_id is not null ) THEN CALL befDernierRdvClient(OLD.id); END IF; IF (OLD.adresse_id is not null) THEN CALL befDernierRdvAdresse(OLD.id); END IF; END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `insDernierRdvEntite`;
DELIMITER $$
CREATE TRIGGER `insDernierRdvEntite` AFTER INSERT ON `rendez_vous` FOR EACH ROW BEGIN IF (NEW.client_id is not null) THEN CALL aftDernierRdvClient(NEW.id, NEW.client_id); END IF; IF (NEW.adresse_id is not null) THEN CALL aftDernierRdvAdresse(NEW.id, NEW.adresse_id); END IF; END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `uPAftDernierRdvEntite`;
DELIMITER $$
CREATE TRIGGER `uPAftDernierRdvEntite` AFTER UPDATE ON `rendez_vous` FOR EACH ROW BEGIN IF (NEW.client_id is not null) THEN CALL aftDernierRdvClient(NEW.id, NEW.client_id); END IF; IF (NEW.adresse_id is not null) THEN CALL aftDernierRdvAdresse(NEW.id, NEW.adresse_id); END IF; END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `uPBefDernierRdvEntite`;
DELIMITER $$
CREATE TRIGGER `uPBefDernierRdvEntite` BEFORE UPDATE ON `rendez_vous` FOR EACH ROW BEGIN IF (OLD.client_id is not null and OLD.client_id != NEW.client_id) THEN CALL befDernierRdvClient(OLD.id); END IF; IF (OLD.adresse_id is not null and OLD.adresse_id != NEW.adresse_id) THEN CALL befDernierRdvAdresse(OLD.id); END IF; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `FK_C35F08166027C82A` FOREIGN KEY (`dernier_rdv_id`) REFERENCES `rendez_vous` (`id`);

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C74404556027C82A` FOREIGN KEY (`dernier_rdv_id`) REFERENCES `rendez_vous` (`id`);

--
-- Contraintes pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD CONSTRAINT `FK_65E8AA0A19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_65E8AA0A4DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
