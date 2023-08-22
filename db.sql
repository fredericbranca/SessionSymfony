-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour sessionfred
CREATE DATABASE IF NOT EXISTS `sessionfred` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sessionfred`;

-- Listage de la structure de table sessionfred. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.categorie : ~6 rows (environ)
INSERT INTO `categorie` (`id`, `nom`) VALUES
	(1, 'Bureautique'),
	(2, 'Développement Web'),
	(3, 'Comptabilité'),
	(4, 'Infographie'),
	(5, 'Vente'),
	(6, 'Web Design');

-- Listage de la structure de table sessionfred. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table sessionfred.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230822132604', '2023-08-22 15:26:09', 373);

-- Listage de la structure de table sessionfred. formation
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.formation : ~6 rows (environ)
INSERT INTO `formation` (`id`, `nom`) VALUES
	(1, 'Initialisation bureautique et infographie'),
	(2, 'Initialisation comptabilité'),
	(3, 'Remise à niveau numérique'),
	(4, 'DWWB (développeur Web / Web Mobile'),
	(5, 'Web Designer'),
	(6, 'Assistant dentaire');

-- Listage de la structure de table sessionfred. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table sessionfred. module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C242628BCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_C242628BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.module : ~16 rows (environ)
INSERT INTO `module` (`id`, `categorie_id`, `nom`) VALUES
	(1, 1, 'PowerPoint'),
	(2, 1, 'Word'),
	(3, 1, 'Excel'),
	(4, 3, 'Base de fiscalité'),
	(5, 2, 'HTML'),
	(6, 2, 'CSS'),
	(7, 2, 'PHP'),
	(8, 2, 'SQL'),
	(9, 3, 'Comptabilité de gestion'),
	(10, 5, 'Techniques de stratégies de vente'),
	(11, 5, 'Compétences en négociation'),
	(12, 4, 'Adobe Photoshop'),
	(13, 4, 'Adobe Illustrator'),
	(14, 6, 'Figma'),
	(15, 5, 'E-Commerce'),
	(16, 1, 'Logiciel d\'assistant denstiste');

-- Listage de la structure de table sessionfred. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_id` int NOT NULL,
  `session_id` int NOT NULL,
  `nb_jour` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FFAFC2B591` (`module_id`),
  KEY `IDX_3DDCB9FF613FECDF` (`session_id`),
  CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_3DDCB9FFAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.programme : ~13 rows (environ)
INSERT INTO `programme` (`id`, `module_id`, `session_id`, `nb_jour`) VALUES
	(1, 9, 1, 50),
	(2, 4, 1, 35),
	(3, 2, 2, 7),
	(4, 12, 2, 28),
	(5, 16, 3, 25),
	(6, 16, 4, 25),
	(7, 16, 5, 25),
	(8, 9, 6, 70),
	(9, 4, 7, 25),
	(10, 5, 8, 30),
	(11, 6, 8, 30),
	(12, 7, 9, 50),
	(13, 4, 10, 25);

-- Listage de la structure de table sessionfred. session
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `formation_id` int NOT NULL,
  `nb_place` int NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D45200282E` (`formation_id`),
  CONSTRAINT `FK_D044D5D45200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.session : ~10 rows (environ)
INSERT INTO `session` (`id`, `formation_id`, `nb_place`, `date_debut`, `date_fin`) VALUES
	(1, 2, 14, '2022-07-08 09:35:41', '2022-11-23 05:35:07'),
	(2, 1, 14, '2023-08-11 00:15:15', '2023-12-21 21:10:32'),
	(3, 6, 12, '2021-07-10 04:15:26', '2021-10-06 23:53:06'),
	(4, 6, 13, '2023-07-28 01:59:17', '2023-11-22 01:05:15'),
	(5, 6, 8, '2023-10-27 06:44:39', '2024-01-05 14:02:52'),
	(6, 2, 9, '2023-12-28 16:24:40', '2024-03-01 01:34:12'),
	(7, 2, 7, '2022-08-07 18:41:06', '2022-03-16 01:23:47'),
	(8, 4, 12, '2023-07-03 07:17:59', '2024-02-18 16:40:06'),
	(9, 3, 8, '2024-01-09 15:16:59', '2024-12-08 19:47:50'),
	(10, 2, 15, '2023-07-07 13:02:54', '2023-11-18 19:52:00');

-- Listage de la structure de table sessionfred. stagiaire
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.stagiaire : ~60 rows (environ)
INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `telephone`) VALUES
	(1, 'Valentine Albert', 'Patrick', '09 96 28 89 09'),
	(2, 'Robert Marchand', 'Camille', '01 42 42 45 58'),
	(3, 'Arthur Laine', 'Laurence', '03 58 23 18 71'),
	(4, 'Margaux de la Fleury', 'Margaret', '03 49 03 10 68'),
	(5, 'Colette Pinto', 'Alexandria', '+33 1 77 18 64 44'),
	(6, 'Thierry de Fouquet', 'Christophe', '05 61 08 65 05'),
	(7, 'Margot de Valette', 'Alix', '0341917290'),
	(8, 'Anne de Pons', 'Corinne', '02 10 27 62 81'),
	(9, 'Marthe de la Dias', 'Suzanne', '03 45 63 42 29'),
	(10, 'Éléonore Guyon', 'Michèle', '0758393096'),
	(11, 'Aimé Lejeune-Blondel', 'Renée', '0380906355'),
	(12, 'Michel-Thomas Moulin', 'Colette', '+33 (0)7 99 11 64 78'),
	(13, 'Brigitte Martins-Ruiz', 'Manon', '02 85 20 29 13'),
	(14, 'Marthe Baron', 'Clémence', '0732928124'),
	(15, 'Guy Perrot', 'Robert', '04 95 95 02 64'),
	(16, 'Margaret Reynaud', 'Yves', '+33 (0)1 95 29 94 97'),
	(17, 'Olivie Barbe', 'Capucine', '06 54 03 63 88'),
	(18, 'Hortense de Dupre', 'Maryse', '+33 (0)1 27 85 20 00'),
	(19, 'Nicolas Toussaint', 'Patricia', '+33 5 91 72 14 42'),
	(20, 'Lucie Hamon', 'Marie', '+33 (0)8 99 22 23 09'),
	(21, 'Adèle Blondel', 'Margot', '+33 1 24 89 94 38'),
	(22, 'Noémi Courtois', 'Monique', '0152467575'),
	(23, 'Danielle Rossi', 'Michel', '0689192305'),
	(24, 'Thomas Ollivier', 'Aurélie', '02 20 02 55 67'),
	(25, 'Grégoire Peltier', 'Renée', '0239480385'),
	(26, 'Alice Chevalier-Gilbert', 'Élisabeth', '+33 (0)2 51 43 18 67'),
	(27, 'Alexandria Hernandez', 'Julien', '0320149183'),
	(28, 'Roland Gros', 'Maggie', '+33 1 84 70 53 34'),
	(29, 'Françoise Gilbert-Barbe', 'Édith', '+33 9 48 97 15 00'),
	(30, 'Agathe Lecoq', 'Agnès', '04 50 37 02 03'),
	(31, 'Catherine Raymond', 'Roger', '+33 1 47 51 07 71'),
	(32, 'Sabine Le Leconte', 'Arnaude', '0895280900'),
	(33, 'Nath Lemoine', 'Paulette', '01 03 07 69 92'),
	(34, 'Marie Picard', 'Christelle', '+33 6 48 57 84 01'),
	(35, 'Élise Marechal', 'Thibault', '+33 1 53 83 99 13'),
	(36, 'Christophe Robert', 'Andrée', '+33 (0)6 35 23 08 09'),
	(37, 'Victoire Masson', 'Dominique', '0392349159'),
	(38, 'Marguerite Boulay', 'Alain', '07 34 89 29 96'),
	(39, 'Pauline Julien-Louis', 'Dorothée', '+33 5 81 83 05 20'),
	(40, 'Emmanuel Mendes', 'Pierre', '+33 (0)7 30 62 32 89'),
	(41, 'Gilbert Lopes', 'Laure', '0755105969'),
	(42, 'Corinne Paul', 'Céline', '01 53 84 35 31'),
	(43, 'Margot Moulin-Pierre', 'Maurice', '08 93 18 39 84'),
	(44, 'Aimé Payet-Brun', 'Susanne', '04 98 43 90 25'),
	(45, 'Anastasie Gomes-Cousin', 'Caroline', '+33 2 64 77 47 01'),
	(46, 'Alphonse Le Perrin', 'Maryse', '0735687880'),
	(47, 'Marianne Le Verdier', 'Henri', '+33 (0)1 48 31 33 95'),
	(48, 'Nicolas Arnaud', 'Matthieu', '+33 (0)2 60 82 98 49'),
	(49, 'Alfred Briand', 'Maurice', '0934442880'),
	(50, 'Madeleine Navarro', 'Honoré', '0394382758'),
	(51, 'Monique-Marthe Martins', 'Brigitte', '+33 (0)8 91 19 93 59'),
	(52, 'Marcel Mercier', 'Sébastien', '+33 1 23 44 28 49'),
	(53, 'Élodie de la Aubert', 'Roger', '+33 (0)9 14 93 79 31'),
	(54, 'Guy Collin', 'Julie', '0121505506'),
	(55, 'Mathilde Lefort', 'Alexandrie', '0771711286'),
	(56, 'Véronique-Marine Legendre', 'Daniel', '+33 (0)7 98 64 76 29'),
	(57, 'Jules Jean', 'Lorraine', '+33 (0)7 61 71 13 06'),
	(58, 'Françoise Torres-Lemonnier', 'Arnaude', '0893576031'),
	(59, 'Dominique Samson', 'Marianne', '+33 (0)1 29 50 65 32'),
	(60, 'Christelle Hebert', 'Jacqueline', '+33 (0)5 46 54 68 11');

-- Listage de la structure de table sessionfred. stagiaire_session
CREATE TABLE IF NOT EXISTS `stagiaire_session` (
  `stagiaire_id` int NOT NULL,
  `session_id` int NOT NULL,
  PRIMARY KEY (`stagiaire_id`,`session_id`),
  KEY `IDX_D32D02D4BBA93DD6` (`stagiaire_id`),
  KEY `IDX_D32D02D4613FECDF` (`session_id`),
  CONSTRAINT `FK_D32D02D4613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D32D02D4BBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.stagiaire_session : ~60 rows (environ)
INSERT INTO `stagiaire_session` (`stagiaire_id`, `session_id`) VALUES
	(1, 4),
	(2, 4),
	(3, 9),
	(4, 5),
	(5, 9),
	(6, 4),
	(7, 8),
	(8, 1),
	(9, 4),
	(10, 9),
	(11, 7),
	(12, 2),
	(13, 1),
	(14, 5),
	(15, 8),
	(16, 10),
	(17, 8),
	(18, 1),
	(19, 4),
	(20, 9),
	(21, 10),
	(22, 8),
	(23, 10),
	(24, 5),
	(25, 2),
	(26, 4),
	(27, 10),
	(28, 8),
	(29, 5),
	(30, 5),
	(31, 9),
	(32, 6),
	(33, 9),
	(34, 10),
	(35, 4),
	(36, 6),
	(37, 1),
	(38, 1),
	(39, 10),
	(40, 2),
	(41, 7),
	(42, 9),
	(43, 4),
	(44, 2),
	(45, 8),
	(46, 8),
	(47, 8),
	(48, 6),
	(49, 6),
	(50, 3),
	(51, 6),
	(52, 9),
	(53, 4),
	(54, 4),
	(55, 2),
	(56, 1),
	(57, 7),
	(58, 4),
	(59, 10),
	(60, 8);

-- Listage de la structure de table sessionfred. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `nom` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionfred.user : ~10 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `nom`, `prenom`) VALUES
	(1, 'michel00@perrot.org', '["ROLE_USER"]', 'n)p7U1cb<{k_+y-q', 1, 'Devaux', 'Christiane'),
	(2, 'dbodin@camus.com', '["ROLE_USER"]', 'u5#~4o', 1, 'Dijoux', 'Benjamin'),
	(3, 'ribeiro.raymond@hotmail.fr', '["ROLE_USER"]', '3LvhMY8qTc', 1, 'Payet', 'Tristan'),
	(4, 'jmarion@durand.net', '["ROLE_USER"]', 'JNa\'kMirg', 1, 'Raynaud', 'Lucy'),
	(5, 'hugues.leleu@hotmail.fr', '["ROLE_USER"]', ';Zjzn2%xhTkuEpmMdL0', 1, 'Pereira', 'Théophile'),
	(6, 'etienne.lucas@laroche.net', '["ROLE_USER"]', 'eqAWP8yDoJ', 1, 'Joly', 'Joseph'),
	(7, 'genevieve.lebreton@marchal.fr', '["ROLE_USER"]', '1&W#ZLnsURJM', 1, 'Joubert', 'Augustin'),
	(8, 'penelope68@lejeune.fr', '["ROLE_USER"]', 'sHB!HsUlX(gh:Dg', 1, 'Barbe', 'Gérard'),
	(9, 'jerome.bernard@allard.com', '["ROLE_USER"]', 'W_19~"N<T8y|H4{j', 1, 'Grenier', 'Gabrielle'),
	(10, 'ledoux.denise@sfr.fr', '["ROLE_USER"]', '`K?y=;_d5IJ~0*cH', 1, 'Bernier', 'Mathilde');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
