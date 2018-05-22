-- TABLES
CREATE TABLE IF NOT EXISTS `bx_shopify_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_shopify';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_shopify';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify', 'bx_shopify_scores', 'bx_shopify_scores_track', '604800', '0', 'bx_shopify_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');
