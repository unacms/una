-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_courses' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_courses_enable_content', 'bx_courses_content_level_max', 'bx_courses_content_modules_st', 'bx_courses_content_modules_at');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_courses_enable_content', '', @iCategId, '_bx_courses_option_enable_content', 'checkbox', '', '', '', 5),
('bx_courses_content_level_max', '3', @iCategId, '_bx_courses_option_content_level_max', 'select', '1,2,3', '', '', 6),
('bx_courses_content_modules_st', '', @iCategId, '_bx_courses_option_content_modules_step', 'list', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:30:"get_options_content_modules_st";}', '', '', 7),
('bx_courses_content_modules_at', '', @iCategId, '_bx_courses_option_content_modules_attachment', 'list', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:30:"get_options_content_modules_at";}', '', '', 8);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_courses_view_profile' AND `title` IN ('_bx_courses_page_block_title_profile_structure_l1', '_bx_courses_page_block_title_profile_structure_l2');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_courses_view_profile', 2, 'bx_courses', '', '_bx_courses_page_block_title_profile_structure_l1', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:25:"entity_structure_l1_block";}', 0, 0, 1, 1),
('bx_courses_view_profile', 2, 'bx_courses', '', '_bx_courses_page_block_title_profile_structure_l2', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:25:"entity_structure_l2_block";}', 0, 0, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_courses_view_profile_node';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_courses_view_profile_node', 'view-course-profile-node', '_bx_courses_page_title_sys_view_profile_node', '_bx_courses_page_title_view_profile_node', 'bx_courses', 5, 2147483647, 1, 'page.php?i=view-course-profile-node', '', '', '', 0, 1, 0, 'BxCoursesPageEntry', 'modules/boonex/courses/classes/BxCoursesPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_courses_view_profile_node';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_courses_view_profile_node', 1, 'bx_courses', '_bx_courses_page_block_title_system_entry_node', '_bx_courses_page_block_title_entry_node', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_courses";s:6:"method";s:17:"entity_node_block";}', 0, 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_courses_profile_content';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_courses_profile_content', 'edit-course-content', '_bx_courses_page_title_sys_profile_content', '_bx_courses_page_title_profile_content', 'bx_courses', 5, 2147483647, 1, 'page.php?i=edit-course-content', '', '', '', 0, 1, 0, 'BxCoursesPageEntry', 'modules/boonex/courses/classes/BxCoursesPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_courses_profile_content';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_courses_profile_content', 1, 'bx_courses', '_bx_courses_page_block_title_system_profile_structure', '_bx_courses_page_block_title_profile_structure_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_courses";s:6:"method";s:30:"entity_content_structure_block";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 1),
('bx_courses_profile_content', 1, 'bx_courses', '_bx_courses_page_block_title_system_profile_data', '_bx_courses_page_block_title_profile_data_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_courses";s:6:"method";s:25:"entity_content_data_block";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_more' AND `name`='edit-course-content';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_courses_view_actions_more', 'bx_courses', 'edit-course-content', '_bx_courses_menu_item_title_system_edit_content', '_bx_courses_menu_item_title_edit_content', 'page.php?i=edit-course-content&profile_id={profile_id}', '', '', 'folder-tree', '', 2147483647, 'a:3:{s:6:"module";s:10:"bx_courses";s:6:"method";s:20:"is_content_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 42);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_courses_content_add';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `persistent`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_courses_content_add', '_bx_courses_menu_title_content_add', 'bx_courses_content_add', 'bx_courses', 6, 1, 0, 1, 'BxCoursesMenuContentAdd', 'modules/boonex/courses/classes/BxCoursesMenuContentAdd.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_courses_content_add';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_courses_content_add', 'bx_courses', '_bx_courses_menu_set_title_content_add', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_snippet_meta' AND `name` IN ('pass', 'reports');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_courses_snippet_meta', 'bx_courses', 'reports', '_bx_courses_menu_item_title_system_sm_reports', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 33),
('bx_courses_snippet_meta', 'bx_courses', 'pass', '_bx_courses_menu_item_title_system_sm_pass', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 65);


-- GRIDS
UPDATE `sys_grid_actions` SET `title`='_bx_courses_grid_action_title_adm_clear_reports' WHERE `object`='bx_courses_administration' AND `type`='bulk' AND `name`='clear_reports';

DELETE FROM `sys_objects_grid` WHERE `object`='bx_courses_cnt_structure_manage';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_courses_cnt_structure_manage', 'Sql', 'SELECT `tcn`.*, `tcs`.`parent_id`, `tcs`.`level`, `tcs`.`cn_l2`, `tcs`.`cn_l3` FROM `bx_courses_content_nodes` AS `tcn` LEFT JOIN `bx_courses_content_structure` AS `tcs` ON `tcn`.`id`=`tcs`.`node_id` WHERE 1 ', 'bx_courses_content_nodes', 'id', 'order', 'status', '', 20, NULL, 'start', '', 'title', '', 'like', '', '', 2147483647, 'BxCoursesGridCntStructureManage', 'modules/boonex/courses/classes/BxCoursesGridCntStructureManage.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_courses_cnt_structure_manage';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_courses_cnt_structure_manage', 'order', '', '4%', 0, 0, '', 1),
('bx_courses_cnt_structure_manage', 'checkbox', '_sys_select', '4%', 0, 0, '', 2),
('bx_courses_cnt_structure_manage', 'switcher', '_bx_courses_grid_column_title_cnt_active', '7%', 0, 0, '', 3),
('bx_courses_cnt_structure_manage', 'title', '_bx_courses_grid_column_title_cnt_title', '35%', 0, 0, '', 4),
('bx_courses_cnt_structure_manage', 'cn_l2', '_bx_courses_grid_column_title_cnt_cn_l2', '10%', 0, 0, '', 5),
('bx_courses_cnt_structure_manage', 'cn_l3', '_bx_courses_grid_column_title_cnt_cn_l3', '10%', 0, 0, '', 6),
('bx_courses_cnt_structure_manage', 'counters', '_bx_courses_grid_column_title_cnt_counters', '20%', 0, 0, '', 7),
('bx_courses_cnt_structure_manage', 'added', '_bx_courses_grid_column_title_cnt_added', '10%', 0, 0, '', 8),
('bx_courses_cnt_structure_manage', 'actions', '', '20%', 0, 0, '', 9);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_courses_cnt_structure_manage';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_courses_cnt_structure_manage', 'independent', 'back', '_bx_courses_grid_action_title_cnt_back', 'arrow-left', 0, 0, 1),
('bx_courses_cnt_structure_manage', 'independent', 'add', '_bx_courses_grid_action_title_cnt_add', '', 0, 0, 2),
('bx_courses_cnt_structure_manage', 'single', 'edit', '_bx_courses_grid_action_title_cnt_edit', 'pencil-alt', 1, 0, 1),
('bx_courses_cnt_structure_manage', 'single', 'delete', '_bx_courses_grid_action_title_cnt_delete', 'remove', 1, 1, 2),
('bx_courses_cnt_structure_manage', 'bulk', 'delete', '_bx_courses_grid_action_title_cnt_delete', '', 0, 1, 1);

DELETE FROM `sys_objects_grid` WHERE `object`='bx_courses_cnt_data_manage';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_courses_cnt_data_manage', 'Sql', 'SELECT * FROM `bx_courses_content_data` WHERE 1 ', 'bx_courses_content_data', 'id', 'order', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 'BxCoursesGridCntDataManage', 'modules/boonex/courses/classes/BxCoursesGridCntDataManage.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_courses_cnt_data_manage';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_courses_cnt_data_manage', 'order', '', '4%', 0, 0, '', 1),
('bx_courses_cnt_data_manage', 'checkbox', '_sys_select', '4%', 0, 0, '', 2),
('bx_courses_cnt_data_manage', 'content_id', '_bx_courses_grid_column_title_cnt_content_id', '42%', 0, 0, '', 3),
('bx_courses_cnt_data_manage', 'content_type', '_bx_courses_grid_column_title_cnt_content_type', '10%', 0, 0, '', 4),
('bx_courses_cnt_data_manage', 'usage', '_bx_courses_grid_column_title_cnt_usage', '10%', 0, 0, '', 5),
('bx_courses_cnt_data_manage', 'added', '_bx_courses_grid_column_title_cnt_added', '10%', 0, 0, '', 6),
('bx_courses_cnt_data_manage', 'actions', '', '20%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_courses_cnt_data_manage';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_courses_cnt_data_manage', 'independent', 'back', '_bx_courses_grid_action_title_cnt_back', 'arrow-left', 0, 0, 1),
('bx_courses_cnt_data_manage', 'independent', 'add_st', '_bx_courses_grid_action_title_cnt_add_step', '', 0, 0, 2),
('bx_courses_cnt_data_manage', 'independent', 'add_at', '_bx_courses_grid_action_title_cnt_add_attachment', '', 0, 0, 3),
('bx_courses_cnt_data_manage', 'single', 'delete', '_bx_courses_grid_action_title_cnt_delete', 'remove', 1, 1, 1),
('bx_courses_cnt_data_manage', 'bulk', 'delete', '_bx_courses_grid_action_title_cnt_delete', '', 0, 1, 1);
