SET @sName = 'bx_artificer';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_artificer_system' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_color_scheme');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_color_scheme'), '_bx_artificer_stg_cpt_option_color_scheme', 'auto', 'select', 'a:2:{s:6:"module";s:12:"bx_artificer";s:6:"method";s:24:"get_options_color_scheme";}', '', '', 4);
