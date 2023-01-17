
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options: Hidden

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_css_tailwind_default', '_adm_stg_cpt_option_sys_css_tailwind_default', 'tailwind.min.css', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:27:"get_options_taiwind_default";s:5:"class";s:13:"TemplServices";}', '', '', '', 181);

-- Forms

UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `display_name` = 'sys_comment_post' AND `input_name` = 'cmt_submit';
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `display_name` = 'sys_comment_post' AND `input_name` = 'cmt_image';

UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_controls';
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_submit';
UPDATE `sys_form_display_inputs` SET `order` = 10 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_cancel';
UPDATE `sys_form_display_inputs` SET `order` = 11 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_image';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC2' WHERE (`version` = '13.0.0.RC1' OR `version` = '13.0.0-RC1') AND `name` = 'system';

