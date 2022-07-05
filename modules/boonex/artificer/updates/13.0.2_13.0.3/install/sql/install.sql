SET @sName = 'bx_artificer';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_artificer_system' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_header_stretched');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_stretched'), '_bx_artificer_stg_cpt_option_header_stretched', '', 'checkbox', '', '', '', 3);


SET @iSystemCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_artificer_site_logo_width', 'bx_artificer_site_logo_height', 'bx_artificer_site_mark', 'bx_artificer_site_logo_aspect_ratio', 'bx_artificer_site_mark_aspect_ratio');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_mark'), '', '0', 'digit', '', '', '', @iSystemCategoryOrder + 1),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 2),
(@iSystemCategoryId, CONCAT(@sName, '_site_mark_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 3);
