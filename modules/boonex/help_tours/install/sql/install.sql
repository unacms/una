-- Studio page and widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_help_tours', '_bx_help_tours', '_bx_help_tours', 'bx_help_tours@modules/boonex/help_tours/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_help_tours', '{url_studio}module.php?name=bx_help_tours&page=tours', '', 'bx_help_tours@modules/boonex/help_tours/|std-icon.svg', '_bx_help_tours', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

-- Main Tables
CREATE TABLE `bx_help_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `overlay` tinyint(1) NOT NULL,
  `page` varchar(128) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `bx_help_tours_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `element` varchar(255) NOT NULL,
  `arrow` ENUM('auto', 'auto-start', 'auto-end', 'top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'right', 'right-start', 'right-end', 'left', 'left-start', 'left-end'),
  `title` varchar(128) NOT NULL,
  `text` varchar(128) NOT NULL,
  `order` int(11) NOT NULL,
  KEY `tour` (`tour`),
  PRIMARY KEY (`id`)
);

CREATE TABLE `bx_help_tours_track_views` (
  `account` int(11) NOT NULL,
  `tour` int(11) NOT NULL,
  KEY `account` (`account`),
  PRIMARY KEY (`account`, `tour`)
);

-- ACCOUNT REMOVAL ALERT
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES
('bx_help_tours_account_delete', '', '', 'a:3:{s:6:"module";s:13:"bx_help_tours";s:6:"method";s:23:"response_account_delete";s:6:"params";s:5:"$this";}');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'delete', @iHandler);