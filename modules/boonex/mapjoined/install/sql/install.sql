SET @sName = 'bx_mapjoined';

-- TABLE: data
CREATE TABLE `bx_mapjoined_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(16) DEFAULT NULL,
  `lng` float DEFAULT NULL,
  `lat` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `mapjoined_accounts_join` (`joined`)

);

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_mapjoined', '_bx_mapjoined', 'bx_mapjoined@modules/boonex/mapjoined/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `bookmark`) 
VALUES(@iPageId, @sName, '{url_studio}module.php?name=bx_mapjoined', '', 'bx_mapjoined@modules/boonex/mapjoined/|std-icon.svg', '_bx_mapjoined', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);
