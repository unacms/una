-- TABLES
CREATE TABLE IF NOT EXISTS `bx_market_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_market_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}' WHERE `object`='bx_market';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_market';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_market';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_market', 'bx_market', 'bx_market_scores', 'bx_market_scores_track', '604800', '0', 'bx_market_products', 'id', 'author', 'score', 'sc_up', 'sc_down', 'BxMarketScore', 'modules/boonex/market/classes/BxMarketScore.php');
