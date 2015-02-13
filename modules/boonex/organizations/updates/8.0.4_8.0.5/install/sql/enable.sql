-- PAGES
UPDATE `sys_pages_blocks` SET `title_system`='_bx_orgs_page_block_title_system_manage' WHERE `object`='bx_organizations_manage' AND `title`='_bx_orgs_page_block_title_manage';

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_moderation';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_moderation';

UPDATE `sys_objects_page` SET `title_system`='_bx_orgs_page_title_sys_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_organizations_administration';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_orgs_page_block_title_system_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_organizations_administration' AND `title`='_bx_orgs_page_block_title_manage';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='delete-organization-account';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', 'delete-organization-account', '_bx_orgs_menu_item_title_system_delete_account', '_bx_orgs_menu_item_title_delete_account', 'page.php?i=account-settings-delete&id={account_id}', '', '', 'remove', '', 128, 1, 0, 50);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_menu_manage_tools' AND `name`='delete';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_menu_manage_tools', 'bx_organizations', 'delete', '_bx_orgs_menu_item_title_system_delete', '_bx_orgs_menu_item_title_delete', 'javascript:void(0)', 'javascript:{js_object}.onClickDelete({content_id});', '_self', 'trash-o', '', 2147483647, 1, 0, 1);

UPDATE `sys_menu_items` SET `visible_for_levels`='2147483647' AND `order`='2' WHERE `set_name`='bx_organizations_menu_manage_tools' AND `name`='delete-with-content';

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='organizations-moderation';

UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='organizations-administration';


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
UPDATE `sys_objects_search` SET `Order`=@iSearchOrder + 1 WHERE `ObjectName`='bx_organizations';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_organizations_moderation';
DELETE FROM `sys_grid_fields` WHERE `object`='bx_organizations_moderation';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_moderation';

UPDATE `sys_objects_grid` SET `field_order`='last_online' WHERE `object` IN ('bx_organizations_administration', 'bx_organizations_common');

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_administration' AND `type`='single' AND `name`='delete';
UPDATE `sys_grid_actions` SET `order`='2' WHERE `object`='bx_organizations_administration' AND `type`='single' AND `name`='settings';

UPDATE `sys_grid_actions` SET `order`='1' WHERE `object`='bx_organizations_common' AND `type`='bulk' AND `name`='delete';
UPDATE `sys_grid_actions` SET `order`='2' WHERE `object`='bx_organizations_common' AND `type`='bulk' AND `name`='delete_with_content';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_common' AND `type`='single' AND `name`='delete';
UPDATE `sys_grid_actions` SET `order`='1' WHERE `object`='bx_organizations_common' AND `type`='single' AND `name`='settings';