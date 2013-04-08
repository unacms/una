
SET @iCategoryId = (SELECT `kateg` FROM `sys_options` WHERE `Name` = 'feedback_per_page' LIMIT 1);
INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('feedback_rss_length', '10', @iCategoryId, 'The number of items shown in the RSS feed', 'digit', '', '', 7);

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'feedback' AND `version` = '1.0.2';

