SET @sName = 'bx_artificer';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_artificer_system' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_header_search');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_search'), '_bx_artificer_stg_cpt_option_header_search', 'on', 'checkbox', '', '', '', 12);
