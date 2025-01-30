--PAGES
DELETE FROM  `sys_pages_blocks` WHERE `object`='bx_courses_joined' AND `title_system`='_bx_courses_page_block_title_sys_joined_entries_summary';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_courses_joined', 1, 'bx_courses', '_bx_courses_page_block_title_sys_joined_entries_summary', '_bx_courses_page_block_title_joined_entries_summary', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:22:"joined_entries_summary";}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `order`='5' WHERE `object`='bx_courses_joined' AND `title_system`='_bx_courses_page_block_title_sys_joined_entries';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_more' AND `name` IN ('hide-course-profile', 'unhide-course-profile');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_courses_view_actions_more', 'bx_courses', 'hide-course-profile', '_bx_courses_menu_item_title_system_hide_profile', '_bx_courses_menu_item_title_hide_profile', 'javascript:void(0);', 'javascript:{js_object}.perform(this, ''hide-course-profile'', {content_id});', '', 'stop-circle', '', 2147483647, '', 1, 0, 35),
('bx_courses_view_actions_more', 'bx_courses', 'unhide-course-profile', '_bx_courses_menu_item_title_system_unhide_profile', '_bx_courses_menu_item_title_unhide_profile', 'javascript:void(0);', 'javascript:{js_object}.perform(this, ''unhide-course-profile'', {content_id});', '', 'play-circle', '', 2147483647, '', 1, 0, 36);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_courses_cnt_data_manage' AND `type`='single' AND `name`='edit';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_courses_cnt_data_manage', 'single', 'edit', '_bx_courses_grid_action_title_cnt_edit', 'pencil-alt', 1, 0, 1);

UPDATE `sys_grid_actions` SET `order`='2' WHERE `object`='bx_courses_cnt_data_manage' AND `type`='single' AND `name`='delete';
