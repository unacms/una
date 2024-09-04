-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_credits_withdrawals_common';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_withdrawals_common', '_bx_credits_page_title_sys_withdrawals_common', '_bx_credits_page_title_withdrawals_common', 'bx_credits', 5, 2147483647, 1, 'credits-withdrawals-common', 'page.php?i=credits-withdrawals-common', '', '', '', 0, 1, 0, 'BxCreditsPageWithdrawals', 'modules/boonex/credits/classes/BxCreditsPageWithdrawals.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_credits_withdrawals_common';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_withdrawals_common', 1, 'bx_credits', '_bx_credits_page_block_title_sys_withdrawals_common', '_bx_credits_page_block_title_withdrawals_common', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:21:"get_block_withdrawals";}}', 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_credits_withdrawals_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_withdrawals_administration', '_bx_credits_page_title_sys_withdrawals_administration', '_bx_credits_page_title_withdrawals_administration', 'bx_credits', 5, 192, 1, 'credits-withdrawals-administration', 'page.php?i=credits-withdrawals-administration', '', '', '', 0, 1, 0, 'BxCreditsPageWithdrawals', 'modules/boonex/credits/classes/BxCreditsPageWithdrawals.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_credits_withdrawals_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_withdrawals_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_withdrawals_administration', '_bx_credits_page_block_title_withdrawals_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:21:"get_block_withdrawals";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);


-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxCreditsMenuManage', `override_class_file`='modules/boonex/credits/classes/BxCreditsMenuManage.php' WHERE `object`='bx_credits_manage_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_credits_manage_submenu' AND `name` IN ('credits-withdrawals-common', 'credits-withdrawals-administration');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_credits_manage_submenu', 'bx_credits', 'credits-withdrawals-common', '_bx_credits_menu_item_title_system_withdrawals_common', '_bx_credits_menu_item_title_withdrawals_common', 'page.php?i=credits-withdrawals-common', '', '_self', '', '', '', 2147483646, 1, 0, 1, 5),
('bx_credits_manage_submenu', 'bx_credits', 'credits-withdrawals-administration', '_bx_credits_menu_item_title_system_withdrawals_administration', '_bx_credits_menu_item_title_withdrawals_administration', 'page.php?i=credits-withdrawals-administration', '', '_self', '', '', '', 192, 1, 0, 1, 6);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_credits_history_administration' AND `type`='independent' AND `name`='withdraw_confirm';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_credits_history_common' AND `type`='independent' AND `name`='withdraw_request';

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_credits_withdrawals_administration', 'bx_credits_withdrawals_common');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_withdrawals_administration', 'Sql', 'SELECT * FROM `bx_credits_withdrawals` WHERE 1 ', 'bx_credits_withdrawals', 'id', 'added', '', '', 20, NULL, 'start', '', 'message,order', '', 'like', '', '', 192, 'BxCreditsGridWithdrawalsAdministration', 'modules/boonex/credits/classes/BxCreditsGridWithdrawalsAdministration.php'),
('bx_credits_withdrawals_common', 'Sql', 'SELECT * FROM `bx_credits_withdrawals` WHERE 1 ', 'bx_credits_withdrawals', 'id', 'added', '', '', 20, NULL, 'start', '', 'message,order', '', 'like', '', '', 2147483647, 'BxCreditsGridWithdrawalsCommon', 'modules/boonex/credits/classes/BxCreditsGridWithdrawalsCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_credits_withdrawals_administration', 'bx_credits_withdrawals_common');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_withdrawals_administration', 'profile_id', '_bx_credits_grid_column_title_wdw_profile_id', '15%', 0, 0, '', 1),
('bx_credits_withdrawals_administration', 'amount', '_bx_credits_grid_column_title_wdw_amount', '5%', 0, 0, '', 2),
('bx_credits_withdrawals_administration', 'rate', '_bx_credits_grid_column_title_wdw_rate', '5%', 0, 0, '', 3),
('bx_credits_withdrawals_administration', 'message', '_bx_credits_grid_column_title_wdw_message', '10%', 0, 16, '', 4),
('bx_credits_withdrawals_administration', 'added', '_bx_credits_grid_column_title_wdw_added', '5%', 0, 0, '', 5),
('bx_credits_withdrawals_administration', 'performer_id', '_bx_credits_grid_column_title_wdw_performer_id', '15%', 0, 0, '', 6),
('bx_credits_withdrawals_administration', 'order', '_bx_credits_grid_column_title_wdw_order', '15%', 0, 16, '', 7),
('bx_credits_withdrawals_administration', 'confirmed', '_bx_credits_grid_column_title_wdw_confirmed', '5%', 0, 0, '', 8),
('bx_credits_withdrawals_administration', 'status', '_bx_credits_grid_column_title_wdw_status', '5%', 0, 0, '', 9),
('bx_credits_withdrawals_administration', 'actions', '', '20%', 0, 0, '', 10),

('bx_credits_withdrawals_common', 'amount', '_bx_credits_grid_column_title_wdw_amount', '10%', 0, 0, '', 1),
('bx_credits_withdrawals_common', 'rate', '_bx_credits_grid_column_title_wdw_rate', '10%', 0, 0, '', 2),
('bx_credits_withdrawals_common', 'message', '_bx_credits_grid_column_title_wdw_message', '15%', 0, 16, '', 3),
('bx_credits_withdrawals_common', 'added', '_bx_credits_grid_column_title_wdw_added', '10%', 0, 0, '', 4),
('bx_credits_withdrawals_common', 'order', '_bx_credits_grid_column_title_wdw_order', '15%', 0, 16, '', 5),
('bx_credits_withdrawals_common', 'confirmed', '_bx_credits_grid_column_title_wdw_confirmed', '10%', 0, 0, '', 6),
('bx_credits_withdrawals_common', 'status', '_bx_credits_grid_column_title_wdw_status', '10%', 0, 0, '', 7),
('bx_credits_withdrawals_common', 'actions', '', '20%', 0, 0, '', 8);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_credits_withdrawals_administration', 'bx_credits_withdrawals_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_withdrawals_administration', 'single', 'withdraw_confirm', '_bx_credits_grid_action_title_wdw_withdraw_confirm', 'check', 1, 1, 1),

('bx_credits_withdrawals_common', 'independent', 'withdraw_request', '_bx_credits_grid_action_title_wdw_withdraw_request', '', 0, 0, 1),
('bx_credits_withdrawals_common', 'single', 'withdraw_cancel', '_bx_credits_grid_action_title_wdw_withdraw_cancel', 'times', 1, 1, 1);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_credits_withdraw_canceled';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_credits', '_bx_credits_et_txt_name_withdraw_canceled', 'bx_credits_withdraw_canceled', '_bx_credits_et_txt_subject_withdraw_canceled', '_bx_credits_et_txt_body_withdraw_canceled');
