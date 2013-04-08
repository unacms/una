
-- Studio page and widget.
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_groups', '_bx_groups', '_bx_groups', 'bx_groups@modules/boonex/groups/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_groups', CONCAT('{url_studio}module.php?name=', 'bx_groups'), '', 'bx_groups@modules/boonex/groups/|std-wi.png', '_bx_groups', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));



-- create tables
CREATE TABLE IF NOT EXISTS `bx_groups_main` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `uri` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `country` varchar(2) NOT NULL,
  `city` varchar(64) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `status` enum('approved','pending') NOT NULL default 'approved',
  `thumb` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `author_id` int(10) unsigned NOT NULL default '0',
  `tags` varchar(255) NOT NULL default '',
  `categories` text NOT NULL,
  `views` int(11) NOT NULL,
  `rate` float NOT NULL,
  `rate_count` int(11) NOT NULL,
  `comments_count` int(11) NOT NULL,
  `fans_count` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `allow_view_group_to` int(11) NOT NULL,
  `allow_view_fans_to` varchar(16) NOT NULL,
  `allow_comment_to` varchar(16) NOT NULL,
  `allow_rate_to` varchar(16) NOT NULL,  
  `allow_post_in_forum_to` varchar(16) NOT NULL,
  `allow_join_to` int(11) NOT NULL,
  `join_confirmation` tinyint(4) NOT NULL default '0',
  `allow_upload_photos_to` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `author_id` (`author_id`),
  KEY `created` (`created`),
  FULLTEXT KEY `search` (`title`,`desc`,`tags`,`categories`),
  FULLTEXT KEY `tags` (`tags`),
  FULLTEXT KEY `categories` (`categories`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_fans` (
  `id_entry` int(10) unsigned NOT NULL,
  `id_profile` int(10) unsigned NOT NULL,
  `when` int(10) unsigned NOT NULL,
  `confirmed` tinyint(4) UNSIGNED NOT NULL default '0',
  PRIMARY KEY (`id_entry`, `id_profile`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_admins` (
  `id_entry` int(10) unsigned NOT NULL,
  `id_profile` int(10) unsigned NOT NULL,
  `when` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_entry`, `id_profile`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_images` (
  `entry_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `entry_id` (`entry_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_rating` (
  `gal_id` smallint( 6 ) NOT NULL default '0',
  `gal_rating_count` int( 11 ) NOT NULL default '0',
  `gal_rating_sum` int( 11 ) NOT NULL default '0',
  UNIQUE KEY `gal_id` (`gal_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_rating_track` (
  `gal_id` smallint( 6 ) NOT NULL default '0',
  `gal_ip` varchar( 20 ) default NULL,
  `gal_date` datetime default NULL,
  KEY `gal_ip` (`gal_ip`, `gal_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_cmts` (
  `cmt_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
  `cmt_parent_id` int( 11 ) NOT NULL default '0',
  `cmt_object_id` int( 12 ) NOT NULL default '0',
  `cmt_author_id` int( 10 ) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL ,
  `cmt_mood` tinyint( 4 ) NOT NULL default '0',
  `cmt_rate` int( 11 ) NOT NULL default '0',
  `cmt_rate_count` int( 11 ) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int( 11 ) NOT NULL default '0',
  PRIMARY KEY ( `cmt_id` ),
  KEY `cmt_object_id` (`cmt_object_id` , `cmt_parent_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_cmts_track` (
  `cmt_system_id` int( 11 ) NOT NULL default '0',
  `cmt_id` int( 11 ) NOT NULL default '0',
  `cmt_rate` tinyint( 4 ) NOT NULL default '0',
  `cmt_rate_author_id` int( 10 ) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int( 11 ) unsigned NOT NULL default '0',
  `cmt_rate_ts` int( 11 ) NOT NULL default '0',
  PRIMARY KEY (`cmt_system_id` , `cmt_id` , `cmt_rate_author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_views_track` (
  `id` int(10) unsigned NOT NULL,
  `viewer` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `ts` int(10) unsigned NOT NULL,
  KEY `id` (`id`,`viewer`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- tag objects
INSERT INTO `sys_objects_tag` VALUES (NULL, 'bx_groups', 'SELECT `Tags` FROM `bx_groups_main` WHERE `id` = {iID} AND `status` = ''approved''', 'permalinks_modules', 'm/groups/browse/tag/{tag}', 'modules/?r=groups/browse/tag/{tag}', '_bx_groups');

-- category objects
INSERT INTO `sys_objects_categories` VALUES (NULL, 'bx_groups', 'SELECT `Categories` FROM `bx_groups_main` WHERE `id` = {iID} AND `status` = ''approved''', 'permalinks_modules', 'm/groups/browse/category/{tag}', 'modules/?r=groups/browse/category/{tag}', '_bx_groups');

INSERT INTO `sys_categories` (`Category`, `ID`, `Type`, `Owner`, `Status`) VALUES 
('Arts & Literature', '0', 'bx_groups', '0', 'active'),
('Animals & Pets', '0', 'bx_groups', '0', 'active'),
('Activities', '0', 'bx_groups', '0', 'active'),
('Automotive', '0', 'bx_groups', '0', 'active'),
('Business & Money', '0', 'bx_groups', '0', 'active'),
('Companies & Co-workers', '0', 'bx_groups', '0', 'active'),
('Cultures & Nations', '0', 'bx_groups', '0', 'active'),
('Dolphin Community', '0', 'bx_groups', '0', 'active'),
('Family & Friends', '0', 'bx_groups', '0', 'active'),
('Fan Clubs', '0', 'bx_groups', '0', 'active'),
('Fashion & Style', '0', 'bx_groups', '0', 'active'),
('Fitness & Body Building', '0', 'bx_groups', '0', 'active'),
('Food & Drink', '0', 'bx_groups', '0', 'active'),
('Gay, Lesbian & Bi', '0', 'bx_groups', '0', 'active'),
('Health & Wellness', '0', 'bx_groups', '0', 'active'),
('Hobbies & Entertainment', '0', 'bx_groups', '0', 'active'),
('Internet & Computers', '0', 'bx_groups', '0', 'active'),
('Love & Relationships', '0', 'bx_groups', '0', 'active'),
('Mass Media', '0', 'bx_groups', '0', 'active'),
('Music & Cinema', '0', 'bx_groups', '0', 'active'),
('Places & Travel', '0', 'bx_groups', '0', 'active'),
('Politics', '0', 'bx_groups', '0', 'active'),
('Recreation & Sports', '0', 'bx_groups', '0', 'active'),
('Religion', '0', 'bx_groups', '0', 'active'),
('Science & Innovations', '0', 'bx_groups', '0', 'active'),
('Sex', '0', 'bx_groups', '0', 'active'),
('Teens & Schools', '0', 'bx_groups', '0', 'active'),
('Other', '0', 'bx_groups', '0', 'active');


