SET @sName = 'bx_market_api';


-- TABLE: licenses
CREATE TABLE IF NOT EXISTS `bx_market_api_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `license_id` int(11) unsigned NOT NULL default '0',
  `profile_id` int(11) unsigned NOT NULL default '0',
  `type` varchar(16) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `license_id` (`license_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_market_api', '_bx_market_api', 'bx_market_api@modules/boonex/market_api/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_market_api', '', 'bx_market_api@modules/boonex/market_api/|std-wi.png', '_bx_market_api', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
