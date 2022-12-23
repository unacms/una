-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_edit_profile_cover';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_edit_profile_cover';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name`='edit-group-cover';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_all' AND `name`='edit-group-cover';
