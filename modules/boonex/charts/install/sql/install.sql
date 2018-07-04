-- TABLES
CREATE TABLE IF NOT EXISTS `bx_charts_top_by_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `module` varchar(255) NOT NULL,
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  INDEX `value` (`value`)
);

CREATE TABLE IF NOT EXISTS `bx_charts_most_active_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `profile_module` varchar(19) NOT NULL,
  `content_module` varchar(19) NOT NULL,
  `views_count` int(11) NOT NULL default '0',
  `create_count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  INDEX `views_count` (`views_count`),
  INDEX `create_count` (`create_count`),
  INDEX `object_id` (`object_id`),
  INDEX `profile_module` (`profile_module`)
);

CREATE TABLE IF NOT EXISTS `bx_charts_most_followed_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `profile_module` varchar(19) NOT NULL,
  `followers_count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  INDEX `followers_count` (`followers_count`),
  INDEX `object_id` (`object_id`),
  INDEX `profile_module` (`profile_module`)
);



-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_charts', '_bx_charts', 'bx_charts@modules/boonex/charts/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `bookmark`) 
VALUES(@iPageId, @sName, '{url_studio}module.php?name=bx_charts', '', 'bx_charts@modules/boonex/charts/|std-icon.svg', '_bx_charts', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);
