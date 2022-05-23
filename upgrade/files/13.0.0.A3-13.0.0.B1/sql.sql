
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- Email templates

DELETE FROM `sys_email_templates` WHERE `Name` = 't_MemProlonged';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_mem_prolonged', 't_MemProlonged', '_sys_et_txt_subject_mem_prolonged', '_sys_et_txt_body_mem_prolonged');

-- Options: hidden

DELETE FROM `sys_options` WHERE `name` IN('sys_samesite_cookies', 'sys_fixed_header');
SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_samesite_cookies', '_adm_stg_cpt_option_sys_samesite_cookies', 'Lax', 'select', 'None,Lax,Strict', '', '', '', 152),
(@iCategoryIdHidden, 'sys_fixed_header', '_adm_stg_cpt_option_sys_fixed_header', '', 'checkbox', '', '', '', '', 170);

-- Options: account

DELETE FROM `sys_options` WHERE `name` IN('sys_account_switch_to_profile_redirect', 'sys_account_switch_to_profile_redirect_custom');
SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_switch_to_profile_redirect', '_adm_stg_cpt_option_sys_account_switch_to_profile_redirect', 'back', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:38:"get_options_switch_to_profile_redirect";s:5:"class";s:18:"BaseServiceAccount";}', '', '', 60),
(@iCategoryIdAccount, 'sys_account_switch_to_profile_redirect_custom', '_adm_stg_cpt_option_sys_account_switch_to_profile_redirect_custom', '', 'digit', '', '', '', 61);


-- Menu

DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_cmts_item_counters';
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts_item_counters', '_sys_menu_title_cmts_item_counters', 'sys_cmts_item_counters', 'system', 15, 0, 1, 'BxTemplCmtsMenuActions', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_cmts_item_counters';
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_cmts_item_counters', 'system', '_sys_menu_set_title_cmts_item_counters', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_counters';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_counters', 'system', 'item-vote', '_sys_menu_item_title_system_cmts_item_vote', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 0),
('sys_cmts_item_counters', 'system', 'item-reaction', '_sys_menu_item_title_system_cmts_item_reaction', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 1),
('sys_cmts_item_counters', 'system', 'item-score', '_sys_menu_item_title_system_cmts_item_score', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 2);


-- Page layouts

SET @iMaxIdPageLayout = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_pages_layouts`);

UPDATE `sys_pages_layouts` SET `id` = @iMaxIdPageLayout + 1 WHERE `id` = 21 AND `name` != 'topbottom_area_col2_col5_col3';

DELETE FROM `sys_pages_layouts` WHERE `id` = 21;
INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES
(21, 'topbottom_area_col2_col5_col3', 'layout_topbottom_area_col2_col5_col3.png', '_sys_layout_topbottom_area_col2_col5_col3', 'layout_topbottom_area_col2_col5_col3.html', 5);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-B1' WHERE (`version` = '13.0.0.A3' OR `version` = '13.0.0-A3') AND `name` = 'system';

