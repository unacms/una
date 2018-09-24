-- TABLES
CREATE TABLE IF NOT EXISTS `bx_oauth_allowed_origins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(191))
);