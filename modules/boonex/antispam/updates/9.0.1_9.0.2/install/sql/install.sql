-- TABLES
CREATE TABLE IF NOT EXISTS `bx_antispam_disposable_email_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `list` enum('blacklist','custom_blacklist','whitelist','custom_whitelist') NOT NULL DEFAULT 'custom_blacklist',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;