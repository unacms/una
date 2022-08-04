SET @sName = 'bx_artificer';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_artificer_styles_custom' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_images_custom');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_images_custom'), '_bx_artificer_stg_cpt_option_images_custom', '', 'text', '', '', '', 10);
