SET @sName = 'bx_massmailer';

-- TABLES
CREATE TABLE `bx_massmailer_campaigns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `reply_to` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `segments` varchar(255) DEFAULT NULL,
  `author` int(11) NOT NULL,
  `date_created` int(11) NOT NULL default '0',
  `date_sent` int(11) NOT NULL default '0',
  `email_list` text DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `bx_massmailer_segments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `info` text DEFAULT NULL,
  `email_list` text DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `bx_massmailer_letters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_sent` int(11) NOT NULL default '0',
  `date_seen` int(11) NOT NULL default '0',
  `code` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
);

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_massmailer', '_bx_massmailer', 'bx_massmailer@modules/boonex/massmailer/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `bookmark`) 
VALUES(@iPageId, @sName, '{url_studio}module.php?name=bx_massmailer', '', 'bx_massmailer@modules/boonex/massmailer/|std-icon.svg', '_bx_massmailer', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);



