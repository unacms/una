-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_credits_profiles_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_profiles_administration', '_bx_credits_page_title_sys_profiles_administration', '_bx_credits_page_title_profiles_administration', 'bx_credits', 5, 192, 1, 'credits-profiles-administration', 'page.php?i=credits-profiles-administration', '', '', '', 0, 1, 0, 'BxCreditsPageProfiles', 'modules/boonex/credits/classes/BxCreditsPageProfiles.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_credits_profiles_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_profiles_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_profiles_administration', '_bx_credits_page_block_title_profiles_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:18:"get_block_profiles";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_credits_manage_submenu' AND `name`='credits-profiles-administration';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_credits_manage_submenu', 'bx_credits', 'credits-profiles-administration', '_bx_credits_menu_item_title_system_profiles_administration', '_bx_credits_menu_item_title_profiles_administration', 'page.php?i=credits-profiles-administration', '', '_self', '', '', '', 192, 1, 0, 1, 5);


-- GRIDS:
UPDATE `sys_objects_grid` SET `source`='SELECT `th`.*, `tp`.`wdw_clearing` FROM `bx_credits_history` AS `th` LEFT JOIN `bx_credits_profiles` AS `tp` ON `th`.`first_pid`=`tp`.`id` WHERE 1 ', `filter_fields`='th`.`direction,th`.`order,th`.`info' WHERE `object`='bx_credits_history_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `th`.*, `tp`.`wdw_clearing` FROM `bx_credits_history` AS `th` LEFT JOIN `bx_credits_profiles` AS `tp` ON `th`.`first_pid`=`tp`.`id` WHERE 1 ', `filter_fields`='th`.`direction,th`.`order,th`.`info' WHERE `object`='bx_credits_history_common';

DELETE FROM `sys_objects_grid` WHERE `object`='bx_credits_profiles_administration';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_profiles_administration', 'Sql', 'SELECT * FROM `bx_credits_profiles` WHERE 1 ', 'bx_credits_profiles', 'id', '', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 192, 'BxCreditsGridProfilesAdministration', 'modules/boonex/credits/classes/BxCreditsGridProfilesAdministration.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_credits_profiles_administration';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_profiles_administration', 'id', '_bx_credits_grid_column_title_pfl_id', '40%', 0, '', '', 1),
('bx_credits_profiles_administration', 'wdw_clearing', '_bx_credits_grid_column_title_pfl_wdw_clearing', '10%', 0, '', '', 2),
('bx_credits_profiles_administration', 'wdw_minimum', '_bx_credits_grid_column_title_pfl_wdw_minimum', '10%', 0, '', '', 3),
('bx_credits_profiles_administration', 'wdw_remaining', '_bx_credits_grid_column_title_pfl_wdw_remaining', '10%', 0, '', '', 4),
('bx_credits_profiles_administration', 'balance', '_bx_credits_grid_column_title_pfl_balance', '15%', 0, '', '', 5),
('bx_credits_profiles_administration', 'balance_cleared', '_bx_credits_grid_column_title_pfl_balance_cleared', '15%', 0, '', '', 6),
('bx_credits_profiles_administration', 'actions', '', '20%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_credits_profiles_administration';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_profiles_administration', 'single', 'edit', '_bx_credits_grid_action_title_pfl_edit', 'pencil-alt', 1, 0, 1);
