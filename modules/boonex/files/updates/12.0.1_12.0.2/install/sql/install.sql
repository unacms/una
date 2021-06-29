-- TABLES
CREATE TABLE IF NOT EXISTS `bx_files_downloading_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `files` MEDIUMTEXT NOT NULL,
  `result` TEXT NOT NULL,
  `started` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);
