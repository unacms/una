
DROP TABLE `bx_forum_report`;

ALTER TABLE `bx_forum` ADD `forum_order` int(11) NOT NULL default '0';
ALTER TABLE `bx_forum_cat` ADD `cat_expanded` tinyint(4) NOT NULL default '0';
ALTER TABLE `bx_forum_post` ADD `hidden` tinyint(4) NOT NULL default '0';
ALTER TABLE `bx_forum_topic` ADD `topic_hidden` tinyint(4) NOT NULL default '0';

CREATE TABLE IF NOT EXISTS `bx_forum_actions_log` (
  `user_name` varchar(32) NOT NULL default '',
  `id` int(11) NOT NULL default '0',
  `action_name` varchar(32) NOT NULL default '',
  `action_when` int(11) NOT NULL default '0',
  KEY `action_when` (`action_when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_forum_attachments` (
  `att_hash` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `att_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `att_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `att_when` int(11) NOT NULL,
  `att_size` int(11) NOT NULL,
  `att_downloads` int(11) NOT NULL,
  PRIMARY KEY (`att_hash`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_forum_signatures` (
  `user` varchar(32) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `when` int(11) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


SET @iLevelNonMember := 1;
SET @iLevelStandard := 2;
SET @iLevelPromotion := 3;

INSERT INTO `sys_acl_actions` VALUES (NULL, 'forum files download', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT IGNORE INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum del topics', NULL);
INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum move topics', NULL);
INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum hide topics', NULL);
INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum unhide topics', NULL);
INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum hide posts', NULL);
INSERT IGNORE INTO `sys_acl_actions` VALUES (NULL, 'forum unhide posts', NULL);




DELETE FROM `sys_page_compose` WHERE `Page` = 'forums_index';
DELETE FROM `sys_page_compose_pages` WHERE `Name` = 'forums_index';

SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('forums_index', 'Forums Index', @iMaxOrder);
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES('forums_index', '998px', 'Full Index', '_bx_forums_index', 1, 0, 'FullIndex', '', 0, 100, 'non,memb', 0);

SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('forums_home', 'Forums Home', @iMaxOrder);
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
('forums_home', '998px', 'Short Index', '_bx_forums_index', 1, 0, 'ShortIndex', '', 1, 34, 'non,memb', 0),
('forums_home', '998px', 'Recent Topics', '_bx_forums_recent_topics', 2, 0, 'RecentTopics', '', 0, 66, 'non,memb', 0);




SET @iId = (SELECT `ID` FROM `sys_menu_top` WHERE `Parent` = 0 AND `Name` = 'Forums' AND `Link` = 'forum/');
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, @iId, 'Forums Index', '_bx_forum_menu_forum_index', 'forum/?action=goto&index=1', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/forum/|bx_forums.png', '', 0, ''),
(NULL, @iId, 'Manage Forum', '_bx_forum_menu_manage_forum', 'forum/?action=goto&manage_forum=1', 20, 'memb', '', '', 'return isAdmin();', 1, 1, 1, 'custom', 'modules/boonex/forum/|bx_forums.png', '', 0, ''),
(NULL, @iId, 'Reported Posts', '_bx_forum_menu_reported_posts', 'forum/?action=goto&reported_posts=1', 22, 'memb', '', '', 'return isAdmin();', 1, 1, 1, 'custom', 'modules/boonex/forum/|bx_forums.png', '', 0, ''),
(NULL, @iId, 'Hidden Posts', '_bx_forum_menu_hidden_posts', 'forum/?action=goto&hidden_posts=1', 24, 'memb', '', '', 'return isAdmin();', 1, 1, 1, 'custom', 'modules/boonex/forum/|bx_forums.png', '', 0, ''),
(NULL, @iId, 'Hidden Topics', '_bx_forum_menu_hidden_topics', 'forum/?action=goto&hidden_topics=1', 26, 'memb', '', '', 'return isAdmin();', 1, 1, 1, 'custom', 'modules/boonex/forum/|bx_forums.png', '', 0, '');




UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'forum' AND `version` = '1.0.2';

