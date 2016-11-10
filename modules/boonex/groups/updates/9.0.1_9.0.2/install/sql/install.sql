-- TABLES
CREATE TABLE IF NOT EXISTS `bx_groups_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_groups@modules/boonex/groups/|std-icon.svg' WHERE `name`='bx_groups';
UPDATE `sys_std_widgets` SET `icon`='bx_groups@modules/boonex/groups/|std-icon.svg' WHERE `module`='bx_groups' AND `caption`='_bx_groups';