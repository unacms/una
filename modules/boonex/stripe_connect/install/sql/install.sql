SET @sName = 'bx_stripe_connect';


-- TABLES
CREATE TABLE `bx_stripe_connect_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `author` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `user_id` varchar(64) NOT NULL default '',
  `public_key` varchar(128) NOT NULL default '',
  `access_token` varchar(128) NOT NULL default '',
  `refresh_token` varchar(128) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `author` (`author`),
  UNIQUE KEY `user_id` (`user_id`)
);


-- Studio page and widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_stripe_connect', '_bx_stripe_connect', 'bx_stripe_connect@modules/boonex/stripe_connect/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_stripe_connect', '', 'bx_stripe_connect@modules/boonex/stripe_connect/|std-icon.svg', '_bx_stripe_connect', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
