SET @sName = 'bx_attendant';

-- TABLE: data
CREATE TABLE `bx_attendant_events` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `method` varchar(50) NOT NULL,
  `event` varchar(50) NOT NULL,
  `added` int(11) DEFAULT NULL,
  `processed` int(11) DEFAULT NULL,
  `action` varchar(10) NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `module` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `object_id` (`object_id`),
  INDEX `action` (`action`)
);
-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_attendant', '_bx_attendant', 'bx_attendant@modules/boonex/attendant/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `featured`) 
VALUES(@iPageId, @sName, 'extensions', '{url_studio}module.php?name=bx_attendant', '', 'bx_attendant@modules/boonex/attendant/|std-icon.svg', '_bx_attendant', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);
