-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_channels_create_profile';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_create_profile';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_add_content_links' AND `name`='create-channel-profile';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_my' AND `name`='create-channel-profile';


-- ACL
SET @iIdActionProfileCreate = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_channels' AND `Name`='create entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionProfileCreate;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileCreate;


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_channels_administration' AND `name` IN ('audit_content', 'audit_profile');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_channels_administration', 'single', 'audit_content', '_bx_channels_grid_action_title_adm_audit_content', 'search', 1, 0, 3),
('bx_channels_administration', 'single', 'audit_profile', '_bx_channels_grid_action_title_adm_audit_context', 'search-location', 1, 0, 4);
