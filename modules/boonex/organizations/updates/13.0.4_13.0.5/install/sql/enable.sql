-- PAGES 
DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_edit_profile_cover';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_edit_profile_cover';

-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='edit-organization-cover';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='edit-organization-cover';
