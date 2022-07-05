SET @sName = 'bx_ocean';


-- SETTINGS
SET @iSystemCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_ocean_site_logo_aspect_ratio';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_ocean_site_logo_width', 'bx_ocean_site_logo_height');
