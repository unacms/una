SET @sName = 'bx_events';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_view_profile' AND `title`='_bx_events_page_block_title_profile_sessions';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `hidden_on`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_view_profile', 3, 'bx_events', '', '_bx_events_page_block_title_profile_sessions', 11, '', 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"sessions";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 0, 0, 1, 1);


DELETE FROM `sys_objects_page` WHERE `object`='bx_events_questionnaire';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_questionnaire', 'edit-event-questionnaire', '_bx_events_page_title_sys_edit_questionnaire', '_bx_events_page_title_edit_questionnaire', 'bx_events', 5, 2147483647, 1, 'page.php?i=edit-event-questionnaire', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_questionnaire';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_questionnaire', 1, 'bx_events', '_bx_events_page_block_title_edit_questionnaire', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:25:"entity_edit_questionnaire";}', 0, 0, 0);


DELETE FROM `sys_objects_page` WHERE `object`='bx_events_profile_sessions';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_profile_sessions', 'edit-event-sessions', '_bx_events_page_title_sys_profile_sessions', '_bx_events_page_title_profile_sessions', 'bx_events', 5, 2147483647, 1, 'page.php?i=edit-event-sessions', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_profile_sessions';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_profile_sessions', 1, 'bx_events', '_bx_events_page_block_title_system_profile_sessions', '_bx_events_page_block_title_profile_sessions_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"entity_sessions";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 1);


DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_fans' AND `title` IN ('_bx_events_page_block_title_fans_link', '_bx_events_page_block_title_subscribers_link', '_bx_events_page_block_title_fans_invites');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_fans', 1, 'bx_events', '_bx_events_page_block_title_system_fans', '_bx_events_page_block_title_fans_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:14:"browse_members";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:2:{s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_events_manage_item';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_manage_item', 'event-manage', '_bx_events_page_title_sys_manage_profile', '_bx_events_page_title_manage_profile', 'bx_events', 5, 2147483647, 1, 'page.php?i=manage', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_manage_item';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_manage_item', 1, 'bx_events', '_bx_events_page_block_title_system_fans_manage', '_bx_events_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1),
('bx_events_manage_item', 1, 'bx_events', '_bx_events_page_block_title_system_subscribers_manage', '_bx_events_page_block_title_subscribers_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:19:"subscribed_me_table";s:5:"class";s:23:"TemplServiceConnections";}', 0, 0, 1, 2),
('bx_events_manage_item', 1, 'bx_events', '_bx_events_page_block_title_system_invites_manage', '_bx_events_page_block_title_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 3);


DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_joined' AND `title`='_bx_events_page_block_title_joined_calendar';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_joined_calendar', '_bx_events_page_block_title_joined_calendar', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"calendar";s:6:"params";a:1:{i:0;a:1:{s:10:"profile_id";s:12:"{profile_id}";}}}', 0, 0, 1, 5);


-- MENUS
UPDATE `sys_menu_items` SET `onclick`="{onclick_add_fan}" WHERE `set_name`='bx_events_view_actions' AND `name`='profile-fan-add';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions' AND `name`='profile-check-in';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_events_view_actions', 'bx_events', 'profile-check-in', '_bx_events_menu_item_title_system_check_in', '_bx_events_menu_item_title_check_in', 'javascript:void(0)', 'javascript:{js_object_entry}.checkIn(this, {content_id});', '', 'check', '', 0, 2147483647, '', 1, 0, 1, 25);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_more' AND `name` IN ('edit-event-questionnaire', 'edit-event-sessions');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_more', 'bx_events', 'edit-event-questionnaire', '_bx_events_menu_item_title_system_edit_questionnaire', '_bx_events_menu_item_title_edit_questionnaire', 'page.php?i=edit-event-questionnaire&profile_id={profile_id}', '', '', 'check-double', '', 2147483647, '', 1, 0, 41),
('bx_events_view_actions_more', 'bx_events', 'edit-event-sessions', '_bx_events_menu_item_title_system_edit_sessions', '_bx_events_menu_item_title_edit_sessions', 'page.php?i=edit-event-sessions&profile_id={profile_id}', '', '', 'calendar-day', '', 2147483647, '', 1, 0, 42);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name` IN ('profile-check-in', 'edit-event-questionnaire', 'edit-event-sessions');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_all', 'bx_events', 'profile-check-in', '_bx_events_menu_item_title_system_check_in', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 45),
('bx_events_view_actions_all', 'bx_events', 'edit-event-questionnaire', '_bx_events_menu_item_title_system_edit_questionnaire', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 411),
('bx_events_view_actions_all', 'bx_events', 'edit-event-sessions', '_bx_events_menu_item_title_system_edit_sessions', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 412);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_submenu' AND `name`='event-manage';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_submenu', 'bx_events', 'event-manage', '_bx_events_menu_item_title_system_view_manage', '_bx_events_menu_item_title_view_manage', 'page.php?i=event-manage&profile_id={profile_id}', '', '', 'calendar col-blue3', '', '', 0, 2147483647, 1, 0, 5);


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_events_fans' AND `name`='name';
UPDATE `sys_grid_fields` SET `width`='30%' WHERE `object`='bx_events_fans' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_fans' AND `type`='single' AND `name`='questionnaire';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_fans', 'single', 'questionnaire', '_bx_events_txt_view_answers', 'check-double', 1, 0, 5);

DELETE FROM `sys_objects_grid` WHERE `object`='bx_events_questions_manage';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_events_questions_manage', 'Sql', 'SELECT * FROM `bx_events_qnr_questions` WHERE 1 ', 'bx_events_qnr_questions', 'id', 'order', '', '', 100, NULL, 'start', '', 'question', '', 'like', '', '', 2147483647, 'BxEventsGridQuestionsManage', 'modules/boonex/events/classes/BxEventsGridQuestionsManage.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_events_questions_manage';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_events_questions_manage', 'checkbox', '_sys_select', '1%', 0, '', '', 1),
('bx_events_questions_manage', 'order', '', '1%', 0, '', '', 2),
('bx_events_questions_manage', 'question', '_bx_events_grid_column_title_qn_question', '78%', 0, 64, '', 3),
('bx_events_questions_manage', 'actions', '', '20%', 0, '', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_questions_manage';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_questions_manage', 'independent', 'add', '_bx_events_grid_action_title_qn_add', '', 0, 0, 1),
('bx_events_questions_manage', 'single', 'edit', '_bx_events_grid_action_title_qn_edit', 'pencil-alt', 1, 0, 1),
('bx_events_questions_manage', 'single', 'delete', '_bx_events_grid_action_title_qn_delete', 'remove', 1, 1, 2),
('bx_events_questions_manage', 'bulk', 'delete', '_bx_events_grid_action_title_qn_delete', '', 0, 1, 1);


DELETE FROM `sys_objects_grid` WHERE `object`='bx_events_sessions_manage';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_events_sessions_manage', 'Sql', 'SELECT * FROM `bx_events_sessions` WHERE 1 ', 'bx_events_sessions', 'id', 'order', '', '', 100, NULL, 'start', '', 'title,description', '', 'like', '', '', 2147483647, 'BxEventsGridSessionsManage', 'modules/boonex/events/classes/BxEventsGridSessionsManage.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_events_sessions_manage';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_events_sessions_manage', 'checkbox', '_sys_select', '1%', 0, '', '', 1),
('bx_events_sessions_manage', 'order', '', '1%', 0, '', '', 2),
('bx_events_sessions_manage', 'title', '_bx_events_grid_column_title_sn_title', '28%', 0, 16, '', 3),
('bx_events_sessions_manage', 'description', '_bx_events_grid_column_title_sn_description', '30%', 0, 32, '', 4),
('bx_events_sessions_manage', 'date_start', '_bx_events_grid_column_title_sn_date_start', '10%', 0, 0, '', 5),
('bx_events_sessions_manage', 'date_end', '_bx_events_grid_column_title_sn_date_end', '10%', 0, 0, '', 6),
('bx_events_sessions_manage', 'actions', '', '20%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_sessions_manage';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_sessions_manage', 'independent', 'add', '_bx_events_grid_action_title_sn_add', '', 0, 0, 1),
('bx_events_sessions_manage', 'single', 'edit', '_bx_events_grid_action_title_sn_edit', 'pencil-alt', 1, 0, 1),
('bx_events_sessions_manage', 'single', 'delete', '_bx_events_grid_action_title_sn_delete', 'remove', 1, 1, 2),
('bx_events_sessions_manage', 'bulk', 'delete', '_bx_events_grid_action_title_sn_delete', '', 0, 1, 1);

