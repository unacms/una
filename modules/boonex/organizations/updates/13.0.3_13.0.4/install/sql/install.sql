-- TABLES
CREATE TABLE IF NOT EXISTS `bx_organizations_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL default '0',
  `group_profile_id` int(11) NOT NULL default '0',
  `author_profile_id` int(11) NOT NULL default '0',
  `invited_profile_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
);
