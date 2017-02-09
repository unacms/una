-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_invite';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_invite', 'invite-to-group', '_bx_groups_page_title_sys_invite_to_group', '_bx_groups_page_title_invite_to_group', 'bx_groups', 5, 2147483647, 1, 'page.php?i=invite-to-group', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_invite';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_invite', 1, 'bx_groups', '_bx_groups_page_block_title_invite_to_group', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:13:\"entity_invite\";}', 0, 0, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_home' AND `title` IN ('_bx_groups_page_block_title_featured_profiles');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_groups_home', 1, 'bx_groups', '_bx_groups_page_block_title_featured_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 0);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name` IN ('invite-to-group');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'invite-to-group', '_bx_groups_menu_item_title_system_invite', '_bx_groups_menu_item_title_invite', 'page.php?i=invite-to-group&id={content_id}', '', '', 'user-plus', '', 2147483647, 1, 0, 42);


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`='bx_groups';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_groups', '1', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'featured', '', '');


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`group_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_groups_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`group_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_groups_common';

UPDATE `sys_grid_fields` SET `name`='name', `title`='_bx_groups_grid_column_title_adm_name' WHERE `object`='bx_groups_administration' AND `name`='group_name';
UPDATE `sys_grid_fields` SET `name`='name', `title`='_bx_groups_grid_column_title_adm_name' WHERE `object`='bx_groups_common' AND `name`='group_name';


-- ALERTS
UPDATE `sys_alerts` SET `action`='timeline_repost' WHERE `unit`='bx_groups' AND `action`='timeline_share';