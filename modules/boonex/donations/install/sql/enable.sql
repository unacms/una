SET @sName = 'bx_donations';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_donations', 'bx_donations@modules/boonex/donations/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_donations', 10);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_donations_show_title', '', @iCategId, '_bx_donations_option_show_title', 'checkbox', '', '', '', 1),
('bx_donations_enable_other', '', @iCategId, '_bx_donations_option_enable_other', 'checkbox', '', '', '', 2),
('bx_donations_amount_precision', '2', @iCategId, '_bx_donations_option_amount_precision', 'digit', '', '', '', 10);


-- PAGE: make
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_donations_make', '_bx_donations_page_title_sys_make', '_bx_donations_page_title_make', @sName, 5, 2147483647, 1, 'donations-make', 'page.php?i=donations-make', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_donations_make', 1, @sName, '_bx_donations_page_block_title_system_make', '_bx_donations_page_block_title_make', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:12:"bx_donations";s:6:"method";s:14:"get_block_make";}', 0, 1, 1);

-- PAGE: list own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_donations_list', '_bx_donations_page_title_sys_list', '_bx_donations_page_title_list', @sName, 5, 2147483647, 1, 'donations-list', '', '', '', '', 0, 1, 0, 'BxDonationsPageList', 'modules/boonex/donations/classes/BxDonationsPageList.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `submenu`, `tabs`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_donations_list', 1, @sName, '', '_bx_donations_page_block_title_list', 11, 'bx_donations_list_submenu', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:12:"bx_donations";s:6:"method";s:14:"get_block_list";}', 0, 0, 1, 1);

-- PAGE: list all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_donations_list_all', '_bx_donations_page_title_sys_list_all', '_bx_donations_page_title_list_all', @sName, 5, 192, 1, 'donations-list-all', '', '', '', '', 0, 1, 0, 'BxDonationsPageList', 'modules/boonex/donations/classes/BxDonationsPageList.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `submenu`, `tabs`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_donations_list_all', 1, @sName, '', '_bx_donations_page_block_title_list_all', 11, 'bx_donations_list_submenu', 0, 192, 'service', 'a:2:{s:6:"module";s:12:"bx_donations";s:6:"method";s:18:"get_block_list_all";}', 0, 0, 1, 0);


-- MENU: licenses submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_donations_list_submenu', '_bx_donations_menu_title_list_submenu', 'bx_donations_list_submenu', @sName, 26, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_donations_list_submenu', @sName, '_bx_donations_menu_set_title_list_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_donations_list_submenu', @sName, 'donations-list-all', '_bx_donations_menu_item_title_system_list_submenu_list_all', '_bx_donations_menu_item_title_list_submenu_list_all', 'page.php?i=donations-list-all', '', '_self', '', '', '', 192, 1, 0, 1, 1),
('bx_donations_list_submenu', @sName, 'donations-list', '_bx_donations_menu_item_title_system_list_submenu_list', '_bx_donations_menu_item_title_list_submenu_list', 'page.php?i=donations-list', '', '_self', '', '', '', 2147483646, 1, 0, 1, 2);

-- MENU: add to site menu
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 AND `order` < 9999 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', @sName, 'donations-make', '_bx_donations_menu_item_title_system_make', '_bx_donations_menu_item_title_make', 'page.php?i=donations-make', '', '', 'donate col-blue3', '', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', @sName, 'donations-make', '_bx_donations_menu_item_title_system_make', '_bx_donations_menu_item_title_make', 'page.php?i=donations-make', '', '', 'donate col-blue3', '', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: account dashboard
SET @iDashboardMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', @sName, 'dashboard-donations-list', '_bx_donations_menu_item_title_system_list', '_bx_donations_menu_item_title_list', 'page.php?i=donations-list', '', '', 'donate col-blue3', '', '', 2147483646, 1, 0, 1, @iDashboardMenuOrder + 1);


-- GRIDS: types
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_donations_types', 'Sql', 'SELECT * FROM `bx_donations_types` WHERE 1 AND `custom`=''0'' ', 'bx_donations_types', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'name,period,period_unit,amount', 'title', 'like', '', '', 192, 'BxDonationsGridTypes', 'modules/boonex/donations/classes/BxDonationsGridTypes.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_donations_types', 'checkbox', '_sys_select', '1%', 0, 0, '', 1),
('bx_donations_types', 'order', '', '1%', 0, 0, '', 2),
('bx_donations_types', 'switcher', '_bx_donations_grid_column_active', '8%', 0, '', '', 3),
('bx_donations_types', 'name', '_bx_donations_grid_column_name', '20%', 0, 32, '', 4),
('bx_donations_types', 'title', '_bx_donations_grid_column_title', '20%', 1, 32, '', 5),
('bx_donations_types', 'amount', '_bx_donations_grid_column_amount', '15%', 0, 8, '', 6),
('bx_donations_types', 'period', '_bx_donations_grid_column_period', '15%', 0, 8, '', 7),
('bx_donations_types', 'actions', '', '20%', 0, '', '', 8);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_donations_types', 'independent', 'add', '_bx_donations_grid_action_add', '', 0, 0, 1),
('bx_donations_types', 'single', 'edit', '_bx_donations_grid_action_edit', 'pencil-alt', 1, 0, 1),
('bx_donations_types', 'single', 'delete', '_bx_donations_grid_action_delete', 'remove', 1, 1, 2),
('bx_donations_types', 'bulk', 'delete', '_bx_donations_grid_action_delete', '', 0, 1, 1);

-- GRIDS: entries list
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('bx_donations_list_all', 'Sql', 'SELECT `te`.`id` AS `id`, `te`.`profile_id` AS `profile_id`, `te`.`type_id` AS `type_id`, `tt`.`title` AS `type_title`, `tt`.`amount` AS `type_amount`, `tt`.`period` AS `type_period`, `tt`.`period_unit` AS `type_period_unit`, `te`.`order` AS `transaction`, `te`.`added` AS `added` FROM `bx_donations_entries` AS `te` LEFT JOIN `bx_donations_types` AS `tt` ON `te`.`type_id`=`tt`.`id` WHERE 1 ', 'bx_donations_entries', 'id', 'added', '', '', 20, NULL, 'start', '', 'tt`.`name,tt`.`period,tt`.`period_unit,tt`.`amount,te`.`order', 'tt`.`title', 'like', '', '', 192, 0, 'BxDonationsGridListAll', 'modules/boonex/donations/classes/BxDonationsGridListAll.php'),
('bx_donations_list', 'Sql', 'SELECT `te`.`id` AS `id`, `te`.`profile_id` AS `profile_id`, `te`.`type_id` AS `type_id`, `tt`.`title` AS `type_title`, `tt`.`amount` AS `type_amount`, `tt`.`period` AS `type_period`, `tt`.`period_unit` AS `type_period_unit`, `te`.`order` AS `transaction`, `te`.`added` AS `added` FROM `bx_donations_entries` AS `te` LEFT JOIN `bx_donations_types` AS `tt` ON `te`.`type_id`=`tt`.`id` WHERE 1 ', 'bx_donations_entries', 'id', 'added', '', '', 20, NULL, 'start', '', 'tt`.`name,tt`.`period,tt`.`period_unit,tt`.`amount,te`.`order', 'tt`.`title', 'like', '', '', 2147483647, 0, 'BxDonationsGridList', 'modules/boonex/donations/classes/BxDonationsGridList.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_donations_list_all', 'profile_id', '_bx_donations_grid_column_title_lst_profile_id', '20%', 0, 0, '', 1),
('bx_donations_list_all', 'entry', '_bx_donations_grid_column_title_lst_entry', '30%', 0, 0, '', 2),
('bx_donations_list_all', 'billing_type', '_bx_donations_grid_column_title_lst_billing_type', '10%', 0, 0, '', 3),
('bx_donations_list_all', 'transaction', '_bx_donations_grid_column_title_lst_transaction', '30%', 0, 32, '', 4),
('bx_donations_list_all', 'added', '_bx_donations_grid_column_title_lst_added', '10%', 0, 0, '', 5),

('bx_donations_list', 'entry', '_bx_donations_grid_column_title_lst_entry', '45%', 0, 0, '', 1),
('bx_donations_list', 'billing_type', '_bx_donations_grid_column_title_lst_billing_type', '10%', 0, 0, '', 2),
('bx_donations_list', 'transaction', '_bx_donations_grid_column_title_lst_transaction', '35%', 0, 32, '', 3),
('bx_donations_list', 'added', '_bx_donations_grid_column_title_lst_added', '10%', 0, 0, '', 4);


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:12:"bx_donations";s:6:"method";s:14:"include_css_js";}', 0, 1);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_donations_et_txt_name_donated', 'bx_donations_donated', '_bx_donations_et_txt_subject_donated', '_bx_donations_et_txt_body_donated');
