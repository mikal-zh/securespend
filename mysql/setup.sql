-- Create a database
CREATE DATABASE IF NOT EXISTS `securespend` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Use the database
USE `securespend`;

-- Create a table
CREATE TABLE IF NOT EXISTS `note` (
  `user_id` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `valeur` decimal(5,2) NOT NULL,
  `statut` enum('en cours','oui','non') DEFAULT 'en cours',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
);


-- Insert some data only if the table is empty
INSERT INTO `note` (`user_id`, `nom`, `valeur`, `statut`)
SELECT 'securespend', '1', 1.00, 'non'
UNION ALL
SELECT 'securespend', '1', 1.00, 'oui'
UNION ALL
SELECT 'securespend', '1', 1.00, 'oui'
UNION ALL
SELECT 'securespend', '1', 1.00, 'oui'
UNION ALL
SELECT 'securespend', '1', 1.00, 'en cours'
UNION ALL
SELECT 'securespend', '1', 1.00, 'en cours'
UNION ALL
SELECT 'user_01', 'h', 1.00, 'oui'
UNION ALL
SELECT 'user_01', 'h', 1.00, 'en cours'
UNION ALL
SELECT 'user_01', 'h', 1.00, 'en cours'
UNION ALL
SELECT 'user_01', 'h', 1.00, 'en cours'
WHERE NOT EXISTS (SELECT 1 FROM `note`);
