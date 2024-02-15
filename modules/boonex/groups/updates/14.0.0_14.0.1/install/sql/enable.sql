-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_fans' AND `title` IN ('_bx_groups_page_block_title_fans_link', '_bx_groups_page_block_title_fans_invites');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_fans', 1, 'bx_groups', '_bx_groups_page_block_title_system_fans', '_bx_groups_page_block_title_fans_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:14:"browse_members";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:2:{s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_manage_item';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_manage_item', 'group-manage', '_bx_groups_page_title_sys_manage_profile', '_bx_groups_page_title_manage_profile', 'bx_groups', 5, 2147483647, 1, 'page.php?i=manage', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_manage_item';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_manage_item', 1, 'bx_groups', '_bx_groups_page_block_title_system_fans_manage', '_bx_groups_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1),
('bx_groups_manage_item', 1, 'bx_groups', '_bx_groups_page_block_title_system_invites_manage', '_bx_groups_page_block_title_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 2),
('bx_groups_manage_item', 1, 'bx_groups', '_bx_groups_page_block_title_system_bans_manage', '_bx_groups_page_block_title_bans', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:10:"bans_table";}', 0, 0, 1, 3);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_submenu' AND `name`='group-manage';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_submenu', 'bx_groups', 'group-manage', '_bx_groups_menu_item_title_system_view_manage', '_bx_groups_menu_item_title_view_manage', 'page.php?i=group-manage&profile_id={profile_id}', '', '', 'users col-blue3', '', '', 0, 2147483647, 1, 0, 5);


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_groups_fans' AND `name`='name';
UPDATE `sys_grid_fields` SET `width`='30%' WHERE `object`='bx_groups_fans' AND `name`='actions';

UPDATE `sys_grid_actions` SET `title`='_bx_groups_txt_delete' WHERE `object`='bx_groups_fans' AND `type`='single' AND `name`='delete';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_fans' AND `type`='single' AND `name`='delete_and_ban';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_fans', 'single', 'delete_and_ban', '_bx_groups_txt_delete_and_ban', 'user-slash', 1, 1, 41);

DELETE FROM `sys_objects_grid` WHERE `object`='bx_groups_bans';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_bans', 'Sql', 'SELECT `p`.`id`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 1, 'BxGroupsGridBans', 'modules/boonex/groups/classes/BxGroupsGridBans.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_groups_bans';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_groups_bans', 'name', '_sys_name', '70%', '', 10),
('bx_groups_bans', 'added', '_bx_groups_txt_ban_added', '10%', '', 20),
('bx_groups_bans', 'actions', '', '20%', '', 30);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_bans';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_bans', 'single', 'delete', '', 'remove', 1, 1, 10);
