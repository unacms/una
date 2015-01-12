SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ocean_system' LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id`=@iCategoryId AND `name`='bx_ocean_page_width' LIMIT 1;
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_ocean_page_width', '_bx_ocean_stg_cpt_option_page_width', '1000', 'digit', '', '', '', 2);