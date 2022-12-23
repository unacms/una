-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_courses_edit_profile_cover';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_courses_edit_profile_cover';

-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_more' AND `name`='edit-course-cover';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_all' AND `name`='edit-course-cover';
