-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               10.1.16-MariaDB - mariadb.org binary distribution
-- Serwer OS:                    Win32
-- HeidiSQL Wersja:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Zrzut struktury bazy danych messagecrypt
CREATE DATABASE IF NOT EXISTS `messagecrypt` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci */;
USE `messagecrypt`;

-- Zrzut struktury tabela messagecrypt.friend
CREATE TABLE IF NOT EXISTS `friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) DEFAULT NULL,
  `recipient` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `author_recipient` (`author`,`recipient`),
  KEY `friend_recipient_id` (`recipient`),
  KEY `friend_author_id` (`author`),
  CONSTRAINT `FK_55EEAC616804FB49` FOREIGN KEY (`recipient`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_55EEAC61BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Zrzucanie danych dla tabeli messagecrypt.friend: ~11 rows (około)
DELETE FROM `friend`;
/*!40000 ALTER TABLE `friend` DISABLE KEYS */;
INSERT INTO `friend` (`id`, `author`, `recipient`) VALUES
	(24, 1, 2),
	(33, 1, 3),
	(39, 1, 5),
	(41, 1, 6),
	(52, 1, 13),
	(23, 2, 1),
	(37, 3, 1),
	(38, 5, 1),
	(35, 5, 2),
	(42, 6, 1),
	(49, 13, 1);
/*!40000 ALTER TABLE `friend` ENABLE KEYS */;

-- Zrzut struktury tabela messagecrypt.message
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `author` int(11) DEFAULT NULL,
  `recipient` int(11) DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `message_recipient_id` (`recipient`),
  KEY `message_author_id` (`author`),
  CONSTRAINT `FK_B6BD307F6804FB49` FOREIGN KEY (`recipient`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD307FBDAFD8C8` FOREIGN KEY (`author`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Zrzucanie danych dla tabeli messagecrypt.message: ~17 rows (około)
DELETE FROM `message`;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` (`id`, `text`, `date`, `author`, `recipient`, `read`) VALUES
	(3, 'asdasd11', '2017-08-16 14:24:08', 3, 1, 1),
	(4, 'Wiadomosci 22', '2017-08-16 14:24:39', 2, 1, 1),
	(5, 'Hej ;) ', '2017-08-16 15:50:24', 1, 3, 1),
	(6, 'Hej ;) ', '2017-08-16 16:02:41', 3, 1, 1),
	(7, 'Co słychać ;) ? Jak w domu ;) ? ', '2017-08-16 16:03:00', 1, 3, 1),
	(8, 'a dobrze dobrze ;p ', '2017-08-16 16:03:15', 5, 1, 1),
	(9, 'qweqwe', '2017-08-16 18:44:29', 1, 3, 1),
	(10, 'whvw', '2017-08-19 14:43:55', 1, 3, 1),
	(11, 'whvw#whvw', '2017-08-22 23:01:08', 1, 3, 1),
	(12, 'test test\r\n\r\ntest test', '2017-08-22 23:01:35', 1, 3, 1),
	(13, 'whvw#whvw\r\n\r\nwhvw#whvw', '2017-08-22 23:01:56', 1, 3, 1),
	(14, '<script> alert("QWE"); </script>', '2017-08-22 23:20:10', 1, 3, 1),
	(15, 'Wiadomosci 22', '2017-08-16 14:24:39', 1, 6, 1),
	(16, 'Hej ;) ', '2017-08-16 15:50:24', 1, 13, 1),
	(17, 'qwe', '2017-09-29 21:57:08', 1, 2, 0),
	(18, 'qwe', '2017-09-29 22:02:22', 2, 1, 1),
	(19, 'hej', '2017-10-01 13:57:04', 5, 1, 1);
/*!40000 ALTER TABLE `message` ENABLE KEYS */;

-- Zrzut struktury tabela messagecrypt.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `birthDate` date DEFAULT NULL,
  `avatar` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Zrzucanie danych dla tabeli messagecrypt.user: ~9 rows (około)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `lastName`, `email`, `login`, `password`, `birthDate`, `avatar`, `token`) VALUES
	(1, 'Piotr', 'Religa', 'test@test.pl', 'preliga', '$2a$06$BqOTfrB4lnwkY3zWkbh4c.NSV/4bMGmL7fCIl8BGIvAAskSmmB5oG', '2017-08-01', '624344752c2b02335af44cc240d2ba43.png', NULL),
	(2, 'Piotr', 'Religa', 'test2@test2.pl', 'preliga2', 'test22', '2017-08-31', '624344752c2b02335af44cc240d2ba43.png', NULL),
	(3, 'testtest', 'testtest', 'testtest@qwe.pl', 'testtest', 'testtest', '2017-07-17', NULL, NULL),
	(5, 'testtest', 'testtest', 'testtest@qwe.pl2', 'testtest2', 'qweqwe', '2017-07-17', 'a2d2d102b3fca197c8a6e794c3ca36c3.png', NULL),
	(6, 'weqweqweq', 'qweweqqwe', 'wqeweq@qweqwe.pl', 'qwe', 'SMR5VyoK30dYAQPNS8Tl8gOZnKc=', '2017-07-28', NULL, NULL),
	(9, 'weqweqweq', 'qweweqqwe', 'wqeweq@qweqw1e.pl', 'qweqweqwe1', 'qweqweqwe', '2017-07-28', NULL, NULL),
	(11, 'weqweqweq', 'qweweqqwe', 'wqeweq@qweqw12e.pl', 'qweqweqwe12', 'qweqweqwe', '2017-07-28', NULL, NULL),
	(12, 'qweqwe', 'qweqwe', 'qwe@qwe.pl', 'admin', '$2a$06$r/IVCMpgCcXJ2eZVm./blO8lbtvGKrtvlCYMGmVtEeJ', '2017-07-18', NULL, NULL),
	(13, 'Alekandra', 'Testowicz', 'szpaner00@gmail.com', 'asdasdasd', '$2y$13$5L36Y7.nOkg61TkDu/zKTewIvHKpvK7V4wTEgLUPPncS/SrMVPUsq', '2017-07-18', NULL, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
