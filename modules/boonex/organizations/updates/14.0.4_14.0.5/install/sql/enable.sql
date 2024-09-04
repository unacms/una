SET @sName = 'bx_organizations';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_fans' AND `title_system` IN ('_bx_orgs_page_block_title_system_fans', '_bx_orgs_page_block_title_system_invites');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_fans', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_fans', '_bx_orgs_page_block_title_fans_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:14:"browse_members";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:2:{s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}}', 0, 0, 1, 1);


DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_manage_item';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_manage_item', 'organization-manage', '_bx_orgs_page_title_sys_manage_profile', '_bx_orgs_page_title_manage_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-manage', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_manage_item';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_manage_item', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_fans_manage', '_bx_orgs_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1),
('bx_organizations_manage_item', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_invites_manage', '_bx_orgs_page_block_title_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='organization-manage';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_organizations_view_actions_more', 'bx_organizations', 'organization-manage', '_bx_orgs_menu_item_title_system_manage', '_bx_orgs_menu_item_title_manage', 'page.php?i=organization-manage&profile_id={profile_id}', '', '', 'users', '', 2147483647, '', 1, 0, 10);

UPDATE `sys_menu_items` SET `icon`='users col-red2' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-friend-requests' AND `icon`='group col-red2';
UPDATE `sys_menu_items` SET `icon`='sync col-red2' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-relation-requests' AND `icon`='sync col-blue3';
