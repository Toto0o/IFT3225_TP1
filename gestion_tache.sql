SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `categories` (
  `id`  int          NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`nom`) VALUES
  ('Backend'),
  ('Frontend'),
  ('QA'),
  ('Docs'),
  ('DevOps');

CREATE TABLE `tuiles` (
  `id`           int        NOT NULL AUTO_INCREMENT,
  `titre`        text       NOT NULL,
  `description`  text       NOT NULL,
  `date`         date       NOT NULL,
  `priorite`     enum('haute','moyenne','basse') NOT NULL,
  `realise`      tinyint(1) NOT NULL DEFAULT 0,
  `categorie_id` int        DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`categorie_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id`       int          NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin`    tinyint(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
