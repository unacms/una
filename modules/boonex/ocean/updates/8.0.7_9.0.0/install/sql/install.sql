SET @sName = 'bx_ocean';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_system') LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_site_logo'), CONCAT(@sName, '_site_logo_alt'), CONCAT(@sName, '_site_logo_width'), CONCAT(@sName, '_site_logo_height'));
SET @iSystemCategoryId = (SELECT IFNULL(`id`, @iCategoryId) FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo'), '', '0', 'digit', '', '', '', @iSystemCategoryOrder + 1),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_alt'), '', '', 'text', '', '', '', @iSystemCategoryOrder + 2),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_width'), '', '240', 'digit', '', '', '', @iSystemCategoryOrder + 3),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_height'), '', '48', 'digit', '', '', '', @iSystemCategoryOrder + 4);