SET @sName = 'bx_artificer';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_system') LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_use_htmx');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_use_htmx'), '_bx_artificer_stg_cpt_option_use_htmx', '', 'checkbox', '', '', '', 5);

DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_site_logo_dark'), CONCAT(@sName, '_site_logo_dark_aspect_ratio'), CONCAT(@sName, '_site_logo_inline'));
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_site_mark_dark'), CONCAT(@sName, '_site_mark_dark_aspect_ratio'), CONCAT(@sName, '_site_mark_inline'));
SET @iSystemCategoryId = (SELECT IFNULL(`id`, @iCategoryId) FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_dark'), '', '0', 'digit', '', '', '', @iSystemCategoryOrder + 1),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_dark_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 2),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_inline'), '', '', 'text', '', '', '', @iSystemCategoryOrder + 3),
(@iSystemCategoryId, CONCAT(@sName, '_site_mark_dark'), '', '0', 'digit', '', '', '', @iSystemCategoryOrder + 4),
(@iSystemCategoryId, CONCAT(@sName, '_site_mark_dark_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 5),
(@iSystemCategoryId, CONCAT(@sName, '_site_mark_inline'), '', '', 'text', '', '', '', @iSystemCategoryOrder + 6);
