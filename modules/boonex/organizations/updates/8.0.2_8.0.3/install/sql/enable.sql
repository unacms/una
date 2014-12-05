UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_organizations_home' AND `title`='_bx_orgs_page_block_title_latest_profiles';
UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='sys_home' AND `title`='_bx_orgs_page_block_title_latest_profiles';


UPDATE `sys_menu_items` SET `visible_for_levels`='2147483646' WHERE `set_name`='bx_organizations_submenu' AND `name`='organizations-manage';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_submenu' AND `name`='view-organization-profile' LIMIT 1;
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'view-organization-profile', '_bx_orgs_menu_item_title_system_view_profile_view', '_bx_orgs_menu_item_title_view_profile_view', 'page.php?i=view-organization-profile&id={content_id}', '', '', '', '', 2147483647, 1, 0, 1);


DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_organizations_administration', 'bx_organizations_moderation', 'bx_organizations_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_organizations_administration', 'bulk', 'set_acl_level', '_bx_orgs_grid_action_title_adm_set_acl_level', '', 0, 0, 1),
('bx_organizations_administration', 'bulk', 'delete', '_bx_orgs_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_organizations_administration', 'bulk', 'delete_with_content', '_bx_orgs_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_organizations_administration', 'single', 'set_acl_level', '_bx_orgs_grid_action_title_adm_set_acl_level', 'certificate', 1, 0, 1),
('bx_organizations_administration', 'single', 'delete', '_bx_orgs_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_organizations_administration', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_organizations_moderation', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 1),
('bx_organizations_common', 'bulk', 'delete', '_bx_orgs_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_organizations_common', 'bulk', 'delete_with_content', '_bx_orgs_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_organizations_common', 'single', 'delete', '_bx_orgs_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_organizations_common', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);