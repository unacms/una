SET @sName = 'bx_notifications';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_notifications_events` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) unsigned NOT NULL default '0',
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `action` varchar(255) collate utf8_unicode_ci NOT NULL,
  `object_id` text collate utf8_unicode_ci NOT NULL,
  `object_privacy_view` int(11) NOT NULL default '3',
  `subobject_id` int(11) NOT NULL default '0',
  `content` text collate utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL default '0',
  `processed` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`)
);

CREATE TABLE IF NOT EXISTS `bx_notifications_events2users` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_notifications_handlers` (
  `id` int(11) NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `type` enum('insert','update','delete') NOT NULL DEFAULT 'insert',
  `alert_unit` varchar(64) NOT NULL default '',
  `alert_action` varchar(64) NOT NULL default '',
  `content` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE `handler` (`group`, `type`),
  UNIQUE `alert` (`alert_unit`, `alert_action`)
);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES
('profile', 'delete', 'profile', 'delete', '');

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_ntfs', '_bx_ntfs', 'bx_notifications@modules/boonex/notifications/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_notifications', '', 'bx_notifications@modules/boonex/notifications/|std-wi.png', '_bx_ntfs', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
