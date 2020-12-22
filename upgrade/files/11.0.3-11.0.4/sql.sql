
-- Settings

DELETE FROM `sys_options` WHERE `name` IN('sys_account_disable_login_form', 'sys_account_disable_join_form', 'sys_account_allow_plus_in_email');

SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_disable_login_form', '_adm_stg_cpt_option_sys_account_disable_login_form', '', 'checkbox', '', '', '', 40),
(@iCategoryIdAccount, 'sys_account_disable_join_form', '_adm_stg_cpt_option_sys_account_disable_join_form', '', 'checkbox', '', '', '', 42),
(@iCategoryIdAccount, 'sys_account_allow_plus_in_email', '_adm_stg_cpt_option_sys_allow_plus_in_email', 'on', 'checkbox', '', '', '', 50);

UPDATE `sys_options` SET `value` = '0' WHERE `name` IN('client_image_resize_width', 'client_image_resize_height');

-- Grids

UPDATE `sys_objects_grid` SET `responsive` = 1 WHERE `object` IN('sys_grid_relations', 'sys_grid_related_me');


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.4' WHERE (`version` = '11.0.3') AND `name` = 'system';
