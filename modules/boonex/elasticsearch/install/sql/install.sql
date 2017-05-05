
-- Tables
CREATE TABLE IF NOT EXISTS `bx_mlc_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_mlc_strings` (
  `key_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) unsigned NOT NULL DEFAULT '0',
  `string` text NOT NULL DEFAULT '',
  `orig` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key_id`,`language_id`),
  FULLTEXT KEY `string` (`string`)
);


-- Studio page and widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_mlc', '_bx_mlc', '_bx_mlc', 'bx_mlc@modules/boonex/mlc/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_mlc', '{url_studio}module.php?name=bx_mlc&page=settings', '', 'bx_mlc@modules/boonex/mlc/|std-icon.svg', '_bx_mlc', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
