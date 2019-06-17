-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_events_public_subscribed_me';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_events_public_subscribed_me', 'on', @iCategId, '_bx_events_option_public_subscribed_me', 'checkbox', '', '', '', '', 40);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_fans' AND `title`='_bx_events_page_block_title_subscribers_link';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_fans', 1, 'bx_events', '_bx_events_page_block_title_system_subscribers', '_bx_events_page_block_title_subscribers_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:19:"subscribed_me_table";s:5:"class";s:23:"TemplServiceConnections";}', 0, 0, 1, 2);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_home' AND `title`='_bx_events_page_block_title_past_profiles';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_events_home', 1, 'bx_events', '', '_bx_events_page_block_title_past_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:20:"browse_past_profiles";s:6:"params";a:1:{i:0;b:0;}}', 0, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_events_past';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_past', '_bx_events_page_title_sys_past', '_bx_events_page_title_past', 'bx_events', 5, 2147483647, 1, 'events-past', 'page.php?i=events-past', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_past';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_past', 1, 'bx_events', '', '_bx_events_page_block_title_past_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:20:"browse_past_profiles";s:6:"params";a:1:{i:0;b:1;}}', 0, 0, 0);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"calendar";s:12:"ignore_cache";b:1;}' WHERE `object`='bx_events_calendar' AND `title`='_bx_events_page_block_title_calendar';


-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxEventsMenuViewActions', `override_class_file`='modules/boonex/events/classes/BxEventsMenuViewActions.php' WHERE `object`='bx_events_view_actions';
UPDATE `sys_objects_menu` SET `override_class_name`='BxEventsMenuViewActions', `override_class_file`='modules/boonex/events/classes/BxEventsMenuViewActions.php' WHERE `object`='bx_events_view_actions_more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name`='social-sharing-googleplus';


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_submenu' AND `name`='events-past';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_submenu', 'bx_events', 'events-past', '_bx_events_menu_item_title_system_entries_past', '_bx_events_menu_item_title_entries_past', 'page.php?i=events-past', '', '', '', '', '', 2147483647, '', 1, 1, 3);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_submenu' AND `name`='more-auto';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_submenu', 'bx_events', 'more-auto', '_bx_events_menu_item_title_system_view_profile_more_auto', '_bx_events_menu_item_title_view_profile_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_snippet_meta' AND `name`='nl';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_events_snippet_meta', 'bx_events', 'nl', '_sys_menu_item_title_system_sm_nl', '_sys_menu_item_title_sm_nl', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 9);

UPDATE `sys_menu_items` SET `order`='1' WHERE `set_name`='bx_events_snippet_meta' AND `name`='date';
UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_events_snippet_meta' AND `name`='tags';
UPDATE `sys_menu_items` SET `order`='3' WHERE `set_name`='bx_events_snippet_meta' AND `name`='views';
UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_events_snippet_meta' AND `name`='comments';
UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_events_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_events_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `order`='7' WHERE `set_name`='bx_events_snippet_meta' AND `name`='country';
UPDATE `sys_menu_items` SET `order`='8' WHERE `set_name`='bx_events_snippet_meta' AND `name`='country-city';
UPDATE `sys_menu_items` SET `order`='10' WHERE `set_name`='bx_events_snippet_meta' AND `name`='join';
UPDATE `sys_menu_items` SET `order`='11' WHERE `set_name`='bx_events_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `order`='12' WHERE `set_name`='bx_events_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `order`='13' WHERE `set_name`='bx_events_snippet_meta' AND `name`='unsubscribe';

UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='events-administration' AND `icon`='';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_events' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_events' AND `action` IN ('timeline_score', 'timeline_pin', 'timeline_promote') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_events', 'timeline_score', @iHandler),
('bx_events', 'timeline_pin', @iHandler),
('bx_events', 'timeline_promote', @iHandler);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_events_allow_post_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_events_allow_post_to', 'bx_events', 'post', '_bx_events_form_profile_input_allow_post_to', 'p', '', 'bx_events_data', 'id', 'author', 'BxEventsPrivacyPost', 'modules/boonex/events/classes/BxEventsPrivacyPost.php');
