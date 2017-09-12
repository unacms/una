
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_events', '_bx_events', 'bx_events@modules/boonex/events/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_events', '_bx_events', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_num_connections_quick', '4', @iCategId, '_bx_events_option_num_connections_quick', 'digit', '', '', '', 10),
('bx_events_per_page_browse', '24', @iCategId, '_bx_events_option_per_page_browse', 'digit', '', '', '', 11),
('bx_events_num_rss', '10', @iCategId, '_bx_events_option_num_rss', 'digit', '', '', '', 12),
('bx_events_time_format', 'H:i', @iCategId, '_bx_events_option_time_format', 'digit', '', '', '', 20),
('bx_events_short_date_format', 'j M y', @iCategId, '_bx_events_option_short_date_format', 'digit', '', '', '', 22),
('bx_events_searchable_fields', 'event_name,event_desc', @iCategId, '_bx_events_option_searchable_fields', 'list', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:21:"get_searchable_fields";}', '', '', 30);

-- PAGES

-- PAGE: create profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_create_profile', 'create-event-profile', '_bx_events_page_title_sys_create_profile', '_bx_events_page_title_create_profile', 'bx_events', 5, 2147483647, 1, 'page.php?i=create-event-profile', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_create_profile', 1, 'bx_events', '_bx_events_page_block_title_create_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:13:\"entity_create\";}', 0, 1, 1);

-- PAGE: view profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_view_profile', 'view-event-profile', '_bx_events_page_title_sys_view_profile', '_bx_events_page_title_view_profile', 'bx_events', 10, 2147483647, 1, 'page.php?i=view-event-profile', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `hidden_on`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_view_profile', 4, 'bx_events', '', '_bx_events_page_block_title_profile_comments', 11, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 0),
('bx_events_view_profile', 4, 'bx_events', '', '_bx_events_page_block_title_profile_description', 13, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_events_view_profile', 2, 'bx_events', '', '_bx_events_page_block_title_profile_info', 11, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_events_view_profile', 1, 'bx_events', '', '_bx_events_page_block_title_entry_social_sharing', 13, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 1, 0),
('bx_events_view_profile', 3, 'bx_events', '', '_bx_events_page_block_title_profile_calendar', 11, '', 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:8:\"calendar\";s:6:\"params\";a:2:{i:0;a:1:{s:5:\"event\";s:12:\"{content_id}\";}i:1;s:21:\"calendar_compact.html\";}}', 0, 0, 1, 0),
('bx_events_view_profile', 4, 'bx_events', '', '_bx_events_page_block_title_fans', 11, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 1),
('bx_events_view_profile', 4, 'bx_events', '', '_bx_events_page_block_title_admins', 11, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:6:\"admins\";}', 0, 0, 1, 2),
('bx_events_view_profile', 2, 'bx_events', '', '_bx_events_page_block_title_entry_location', 11, '', 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:9:\"bx_events\";i:1;s:12:\"{content_id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 1);

-- PAGE: view closed profile 

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_view_profile_closed', 'view-event-profile-closed', '_bx_events_page_title_sys_view_profile_closed', '_bx_events_page_title_view_profile', 'bx_events', 10, 2147483647, 1, 'page.php?i=view-event-profile', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_view_profile_closed', 2, 'bx_events', '', '_bx_events_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_events_view_profile_closed', 3, 'bx_events', '', '_bx_events_page_block_title_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 0);

-- PAGE: edit profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_edit_profile', 'edit-event-profile', '_bx_events_page_title_sys_edit_profile', '_bx_events_page_title_edit_profile', 'bx_events', 5, 2147483647, 1, 'page.php?i=edit-event-profile', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_edit_profile', 1, 'bx_events', '_bx_events_page_block_title_edit_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:11:\"entity_edit\";}', 0, 0, 0);

-- PAGE: edit profile cover

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_edit_profile_cover', 'edit-event-cover', '_bx_events_page_title_sys_edit_profile_cover', '_bx_events_page_title_edit_profile_cover', 'bx_events', 5, 2147483647, 1, 'page.php?i=edit-event-cover', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_edit_profile_cover', 1, 'bx_events', '_bx_events_page_block_title_edit_profile_cover', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:17:\"entity_edit_cover\";}', 0, 0, 0);

-- PAGE: invite members

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_invite', 'invite-to-event', '_bx_events_page_title_sys_invite_to_group', '_bx_events_page_title_invite_to_group', 'bx_events', 5, 2147483647, 1, 'page.php?i=invite-to-event', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_invite', 1, 'bx_events', '_bx_events_page_block_title_invite_to_group', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:13:\"entity_invite\";}', 0, 0, 0);

-- PAGE: delete profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_delete_profile', 'delete-event-profile', '_bx_events_page_title_sys_delete_profile', '_bx_events_page_title_delete_profile', 'bx_events', 5, 2147483647, 1, 'page.php?i=delete-event-profile', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_delete_profile', 1, 'bx_events', '_bx_events_page_block_title_delete_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:13:\"entity_delete\";}', 0, 0, 0);

-- PAGE: profile info

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_profile_info', 'event-profile-info', '_bx_events_page_title_sys_profile_info', '_bx_events_page_title_profile_info', 'bx_events', 5, 2147483647, 1, 'page.php?i=event-profile-info', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `hidden_on`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_profile_info', 1, 'bx_events', '', '_bx_events_page_block_title_profile_description', 13, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_events_profile_info', 2, 'bx_events', '_bx_events_page_block_title_system_profile_info', '_bx_events_page_block_title_profile_info_link', 11, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:16:\"entity_info_full\";}', 0, 0, 1, 0),
('bx_events_profile_info', 3, 'bx_events', '', '_bx_events_page_block_title_profile_calendar', 11, '', 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:8:\"calendar\";s:6:\"params\";a:2:{i:0;a:1:{s:5:\"event\";s:12:\"{content_id}\";}i:1;s:21:\"calendar_compact.html\";}}', 0, 0, 1, 0),
('bx_events_profile_info', 2, 'bx_events', '', '_bx_events_page_block_title_entry_location', 11, '', 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:9:\"bx_events\";i:1;s:12:\"{content_id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 1);

-- PAGE: event fans

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_fans', 'event-fans', '_bx_events_page_title_sys_group_fans', '_bx_events_page_title_group_fans', 'bx_events', 5, 2147483647, 1, 'page.php?i=event-fans', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_fans', 1, 'bx_events', '_bx_events_page_block_title_system_fans', '_bx_events_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1);

-- PAGE: view entry comments

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_profile_comments', '_bx_events_page_title_sys_profile_comments', '_bx_events_page_title_profile_comments', 'bx_events', 5, 2147483647, 1, 'event-profile-comments', '', '', '', '', 0, 1, 0, 'BxEventsPageEntry', 'modules/boonex/events/classes/BxEventsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_profile_comments', 1, 'bx_events', '_bx_events_page_block_title_profile_comments', '_bx_events_page_block_title_profile_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 1);

-- PAGE: module home

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_home', '_bx_events_page_title_sys_recent', '_bx_events_page_title_recent', 'bx_events', 5, 2147483647, 1, 'events-home', 'page.php?i=events-home', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_events_home', 1, 'bx_events', '_bx_events_page_block_title_featured_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 0),
('bx_events_home', 1, 'bx_events', '_bx_events_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:22:\"browse_recent_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 1);

-- PAGE: top profiles

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_top', '_bx_events_page_title_sys_top', '_bx_events_page_title_top', 'bx_events', 5, 2147483647, 1, 'events-top', 'page.php?i=events-top', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_top', 1, 'bx_events', '_bx_events_page_block_title_top_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:19:\"browse_top_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

-- PAGE: calendar

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_calendar', '_bx_events_page_title_sys_calendar', '_bx_events_page_title_calendar', 'bx_events', 5, 2147483647, 1, 'events-calendar', 'page.php?i=events-calendar', '', '', '', 0, 1, 0, 'BxEventsPageCalendar', 'modules/boonex/events/classes/BxEventsPageCalendar.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_calendar', 1, 'bx_events', '_bx_events_page_block_title_calendar', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"calendar";}', 0, 1, 0);

-- PAGE: search for entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_search', '_bx_events_page_title_sys_entries_search', '_bx_events_page_title_entries_search', 'bx_events', 5, 2147483647, 1, 'events-search', 'page.php?i=events-search', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_search', 1, 'bx_events', '_bx_events_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:9:"bx_events";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_events_search', 1, 'bx_events', '_bx_events_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:9:"bx_events";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_events_search', 1, 'bx_events', '_bx_events_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:14:"bx_events_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_events_search', 1, 'bx_events', '_bx_events_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:14:"bx_events_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);

-- PAGE: module manage own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_manage', '_bx_events_page_title_sys_manage', '_bx_events_page_title_manage', 'bx_events', 5, 2147483647, 1, 'events-manage', 'page.php?i=events-manage', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_manage', 1, 'bx_events', '_bx_events_page_block_title_system_manage', '_bx_events_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_administration', '_bx_events_page_title_sys_manage_administration', '_bx_events_page_title_manage', 'bx_events', 5, 192, 1, 'events-administration', 'page.php?i=events-administration', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_administration', 1, 'bx_events', '_bx_events_page_block_title_system_manage_administration', '_bx_events_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);

-- PAGE: user's events
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_joined', 'joined-events', '_bx_events_page_title_sys_joined_entries', '_bx_events_page_title_joined_entries', 'bx_events', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxEventsPageJoinedEntries', 'modules/boonex/events/classes/BxEventsPageJoinedEntries.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_favorites_of_author', '_bx_events_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 1),
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_joined_entries', '_bx_events_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;i:0;i:1;b:1;}}', 0, 0, 1, 2);

-- PAGE: add block to homepage
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 1, 'bx_events', '_bx_events_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:22:"browse_recent_profiles";}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);

-- PAGE: service blocks
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_events', '', '_bx_events_page_block_title_categories', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:14:"bx_events_cats";i:1;a:2:{s:10:\"show_empty\";b:1;s:21:\"show_empty_categories\";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}', 1, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENU

-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_events', 'events-home', '_bx_events_menu_item_title_system_entries_home', '_bx_events_menu_item_title_entries_home', 'page.php?i=events-home', '', '', 'calendar col-red2', 'bx_events_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu

SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_events', 'events-home', '_bx_events_menu_item_title_system_entries_home', '_bx_events_menu_item_title_entries_home', 'page.php?i=events-home', '', '', 'calendar col-red2', 'bx_events_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: add to "add content" menu

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_events', 'create-event-profile', '_bx_events_menu_item_title_system_create_profile', '_bx_events_menu_item_title_create_profile', 'page.php?i=create-event-profile', '', '', 'calendar col-red2', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

-- MENU: view actions

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_view_actions', '_bx_events_menu_title_view_profile_actions', 'bx_events_view_actions', 'bx_events', 9, 0, 1, 'BxEventsMenuView', 'modules/boonex/events/classes/BxEventsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_view_actions', 'bx_events', '_bx_events_menu_set_title_view_profile_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_events_view_actions', 'bx_events', 'profile-fan-add', '_bx_events_menu_item_title_system_become_fan', '{title_add_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_events_fans\', \'add\', \'{profile_id}\')', '', 'user-plus', '', 0, 2147483647, 1, 0, 0, 5),
('bx_events_view_actions', 'bx_events', 'profile-subscribe-add', '_bx_events_menu_item_title_system_subscribe', '_bx_events_menu_item_title_subscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'add\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, 1, 0, 1, 20),
('bx_events_view_actions', 'bx_events', 'profile-actions-more', '_bx_events_menu_item_title_system_more_actions', '_bx_events_menu_item_title_more_actions', 'javascript:void(0)', 'bx_menu_popup(''bx_events_view_actions_more'', this, {}, {profile_id:{profile_id}});', '', 'cog', 'bx_events_view_actions_more', 1, 2147483647, 1, 0, 1, 9999);

-- MENU: view actions more

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_view_actions_more', '_bx_events_menu_title_view_profile_actions_more', 'bx_events_view_actions_more', 'bx_events', 6, 0, 1, 'BxEventsMenuView', 'modules/boonex/events/classes/BxEventsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_view_actions_more', 'bx_events', '_bx_events_menu_set_title_view_profile_actions_more', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_more', 'bx_events', 'profile-fan-remove', '_bx_events_menu_item_title_system_leave_group', '{title_remove_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_events_fans\', \'remove\', \'{profile_id}\')', '', 'user-times', '', 2147483647, 1, 0, 10),
('bx_events_view_actions_more', 'bx_events', 'profile-subscribe-remove', '_bx_events_menu_item_title_system_unsubscribe', '_bx_events_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_events_view_actions_more', 'bx_events', 'edit-event-cover', '_bx_events_menu_item_title_system_edit_cover', '_bx_events_menu_item_title_edit_cover', 'page.php?i=edit-event-cover&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 30),
('bx_events_view_actions_more', 'bx_events', 'edit-event-profile', '_bx_events_menu_item_title_system_edit_profile', '_bx_events_menu_item_title_edit_profile', 'page.php?i=edit-event-profile&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 40),
('bx_events_view_actions_more', 'bx_events', 'invite-to-event', '_bx_events_menu_item_title_system_invite', '_bx_events_menu_item_title_invite', 'page.php?i=invite-to-event&id={content_id}', '', '', 'user-plus', '', 2147483647, 1, 0, 42),
('bx_events_view_actions_more', 'bx_events', 'delete-event-profile', '_bx_events_menu_item_title_system_delete_profile', '_bx_events_menu_item_title_delete_profile', 'page.php?i=delete-event-profile&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 50);

-- MENU: module sub-menu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_submenu', '_bx_events_menu_title_submenu', 'bx_events_submenu', 'bx_events', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_submenu', 'bx_events', '_bx_events_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_submenu', 'bx_events', 'events-home', '_bx_events_menu_item_title_system_entries_recent', '_bx_events_menu_item_title_entries_recent', 'page.php?i=events-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_events_submenu', 'bx_events', 'events-top', '_bx_events_menu_item_title_system_entries_top', '_bx_events_menu_item_title_entries_top', 'page.php?i=events-top', '', '', '', '', 2147483647, 1, 1, 2),
('bx_events_submenu', 'bx_events', 'events-calendar', '_bx_events_menu_item_title_system_calendar', '_bx_events_menu_item_title_calendar', 'page.php?i=events-calendar', '', '', '', '', 2147483647, 1, 1, 3),
('bx_events_submenu', 'bx_events', 'events-search', '_bx_events_menu_item_title_system_entries_search', '_bx_events_menu_item_title_entries_search', 'page.php?i=events-search', '', '', '', '', 2147483647, 1, 1, 4),
('bx_events_submenu', 'bx_events', 'events-manage', '_bx_events_menu_item_title_system_entries_manage', '_bx_events_menu_item_title_entries_manage', 'page.php?i=events-manage', '', '', '', '', 2147483646, 1, 1, 5);

-- MENU: view submenu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_view_submenu', '_bx_events_menu_title_view_profile_submenu', 'bx_events_view_submenu', 'bx_events', 8, 0, 1, 'BxEventsMenuView', 'modules/boonex/events/classes/BxEventsMenuView.php'),
('bx_events_view_submenu_cover', '_bx_events_menu_title_view_profile_submenu_cover', 'bx_events_view_submenu', 'bx_events', 7, 0, 1, 'BxEventsMenuView', 'modules/boonex/events/classes/BxEventsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_view_submenu', 'bx_events', '_bx_events_menu_set_title_view_profile_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_submenu', 'bx_events', 'view-event-profile', '_bx_events_menu_item_title_system_view_profile_view', '_bx_events_menu_item_title_view_profile_view', 'page.php?i=view-event-profile&id={content_id}', '', '', 'calendar col-red2', '', 2147483647, 1, 0, 1),
('bx_events_view_submenu', 'bx_events', 'event-profile-info', '_bx_events_menu_item_title_system_view_profile_info', '_bx_events_menu_item_title_view_profile_info', 'page.php?i=event-profile-info&id={content_id}', '', '', 'info-circle col-gray', '', 2147483647, 1, 0, 2),
('bx_events_view_submenu', 'bx_events', 'event-profile-comments', '_bx_events_menu_item_title_system_view_profile_comments', '_bx_events_menu_item_title_view_profile_comments', 'page.php?i=event-profile-comments&id={content_id}', '', '', '', '', 2147483647, 0, 0, 3),
('bx_events_view_submenu', 'bx_events', 'event-fans', '_bx_events_menu_item_title_system_view_fans', '_bx_events_menu_item_title_view_fans', 'page.php?i=event-fans&profile_id={profile_id}', '', '', 'calendar col-blue3', '', 2147483647, 1, 0, 4);

-- MENU: profile stats
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_events', 'profile-stats-manage-groups', '_bx_events_menu_item_title_system_manage_my_groups', '_bx_events_menu_item_title_manage_my_groups', 'page.php?i=events-manage', '', '_self', 'calendar col-red2', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 2);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_menu_manage_tools', '_bx_events_menu_title_manage_tools', 'bx_events_menu_manage_tools', 'bx_events', 6, 0, 1, 'BxEventsMenuManageTools', 'modules/boonex/events/classes/BxEventsMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_menu_manage_tools', 'bx_events', '_bx_events_menu_set_title_manage_tools', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_menu_manage_tools', 'bx_events', 'delete', '_bx_events_menu_item_title_system_delete', '_bx_events_menu_item_title_delete', 'javascript:void(0)', 'javascript:{js_object}.onClickDelete({content_id});', '_self', 'trash-o', '', 2147483647, 1, 0, 1),
('bx_events_menu_manage_tools', 'bx_events', 'delete-with-content', '_bx_events_menu_item_title_system_delete_with_content', '_bx_events_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 2147483647, 1, 0, 2);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_events', 'events-administration', '_bx_events_menu_item_title_system_admt_groups', '_bx_events_menu_item_title_admt_groups', 'page.php?i=events-administration', '', '_self', '', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_events', 'joined-events', '_bx_events_menu_item_title_system_view_joined_groups', '_bx_events_menu_item_title_view_joined_groups', 'page.php?i=joined-events&profile_id={profile_id}', '', '', 'calendar col-red2', '', 2147483647, 1, 0, 0);

-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_events', 'create entry', NULL, '_bx_events_acl_action_create_profile', '', 1, 1);
SET @iIdActionProfileCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_events', 'delete entry', NULL, '_bx_events_acl_action_delete_profile', '', 1, 1);
SET @iIdActionProfileDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_events', 'view entry', NULL, '_bx_events_acl_action_view_profile', '', 1, 0);
SET @iIdActionProfileView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_events', 'edit any entry', NULL, '_bx_events_acl_action_edit_any_profile', '', 1, 3);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- profile create
(@iAccount, @iIdActionProfileCreate),
(@iStandard, @iIdActionProfileCreate),
(@iUnconfirmed, @iIdActionProfileCreate),
(@iPending, @iIdActionProfileCreate),
(@iModerator, @iIdActionProfileCreate),
(@iAdministrator, @iIdActionProfileCreate),
(@iPremium, @iIdActionProfileCreate),

-- profile delete
(@iAccount, @iIdActionProfileDelete),
(@iStandard, @iIdActionProfileDelete),
(@iUnconfirmed, @iIdActionProfileDelete),
(@iPending, @iIdActionProfileDelete),
(@iModerator, @iIdActionProfileDelete),
(@iAdministrator, @iIdActionProfileDelete),
(@iPremium, @iIdActionProfileDelete),

-- profile view
(@iUnauthenticated, @iIdActionProfileView),
(@iAccount, @iIdActionProfileView),
(@iStandard, @iIdActionProfileView),
(@iUnconfirmed, @iIdActionProfileView),
(@iPending, @iIdActionProfileView),
(@iModerator, @iIdActionProfileView),
(@iAdministrator, @iIdActionProfileView),
(@iPremium, @iIdActionProfileView),

-- any profile edit
(@iModerator, @iIdActionProfileEditAny),
(@iAdministrator, @iIdActionProfileEditAny);


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_events', 'bx_events', 'bx_events_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-event-profile&id={object_id}', '', 'bx_events_data', 'id', 'author', 'event_name', 'comments', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_events', 'bx_events_views_track', '86400', '1', 'bx_events_data', 'id', 'author', 'views', '', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_events', 'bx_events_votes', 'bx_events_votes_track', '604800', '1', '1', '0', '1', 'bx_events_data', 'id', 'author', 'rate', 'votes', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_events', 'bx_events_reports', 'bx_events_reports_track', '1', 'page.php?i=view-event-profile&id={object_id}', 'bx_events_data', 'id', 'author', 'reports', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_events', 'bx_events_favorites_track', '1', '1', '1', 'page.php?i=view-event-profile&id={object_id}', 'bx_events_data', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_events', '1', '1', 'page.php?i=view-event-profile&id={object_id}', 'bx_events_data', 'id', 'author', 'featured', '', '');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_events', 'bx_events_meta_keywords', 'bx_events_meta_locations', '', '', '');

-- CATEGORY
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_events_cats', 'bx_events', 'bx_event', 'bx_events_cats', 'bx_events_data', 'event_cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`content_id` = `bx_events_data`.`id` AND `sys_profiles`.`type` = ''bx_events'')', 'AND `sys_profiles`.`status` = ''active''', '', '');

-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_events', '_bx_events', @iSearchOrder + 1, 'BxEventsSearchResult', 'modules/boonex/events/classes/BxEventsSearchResult.php');

-- CONNECTIONS
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_events_fans', 'bx_events_fans', 'mutual', '', '');

-- STATS
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_events', 'bx_events', '_bx_events', 'page.php?i=events-home', 'calendar col-red2', 'SELECT COUNT(*) FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_events'' WHERE 1 AND `tp`.`status`=''active''', @iMaxOrderStats + 1);

-- CHARTS
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_events_growth', '_bx_events_chart_growth', 'bx_events_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_events'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_events_growth_speed', '_bx_events_chart_growth_speed', 'bx_events_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_events'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');

-- GRID: connections
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_events_fans', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxEventsGridConnections', 'modules/boonex/events/classes/BxEventsGridConnections.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_events_fans', 'name', '_sys_name', '50%', '', 10),
('bx_events_fans', 'actions', '', '50%', '', 20);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_events_fans', 'single', 'accept', '_sys_accept', '', 0, 10),
('bx_events_fans', 'single', 'to_admins', '_bx_events_txt_to_admins', '', 0, 20),
('bx_events_fans', 'single', 'from_admins', '_bx_events_txt_from_admins', '', 0, 30),
('bx_events_fans', 'single', 'delete', '', 'remove', 1, 40);

-- GRIDS: administration
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_events_administration', 'Sql', 'SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_events_data', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'event_name', '', 'like', '', '', 192, 'BxEventsGridAdministration', 'modules/boonex/events/classes/BxEventsGridAdministration.php'),
('bx_events_common', 'Sql', 'SELECT `td`.*, `td`.`event_name` AS `name`, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_events_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_events'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_events_data', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'event_name', '', 'like', '', '', 2147483647, 'BxEventsGridCommon', 'modules/boonex/events/classes/BxEventsGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_events_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_events_administration', 'switcher', '_bx_events_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_events_administration', 'name', '_bx_events_grid_column_title_adm_name', '25%', 0, '', '', 3),
('bx_events_administration', 'added_ts', '_bx_events_grid_column_title_adm_added', '20%', 1, '25', '', 4),
('bx_events_administration', 'account', '_bx_events_grid_column_title_adm_account', '25%', 0, '25', '', 5),
('bx_events_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_events_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_events_common', 'name', '_bx_events_grid_column_title_adm_name', '48%', 0, '', '', 2),
('bx_events_common', 'added_ts', '_bx_events_grid_column_title_adm_added', '30%', 1, '25', '', 3),
('bx_events_common', 'actions', '', '20%', 0, '', '', 4);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_administration', 'bulk', 'delete', '_bx_events_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_events_administration', 'bulk', 'delete_with_content', '_bx_events_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_events_administration', 'single', 'settings', '_bx_events_grid_action_title_adm_more_actions', 'cog', 1, 0, 2),
('bx_events_common', 'bulk', 'delete', '_bx_events_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_events_common', 'bulk', 'delete_with_content', '_bx_events_grid_action_title_adm_delete_with_content', '', 0, 1, 2),
('bx_events_common', 'single', 'settings', '_bx_events_grid_action_title_adm_more_actions', 'cog', 1, 0, 1);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_events', 'BxEventsAlertsResponse', 'modules/boonex/events/classes/BxEventsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('bx_timeline', 'post_common', @iHandler),
('bx_events_pics', 'file_deleted', @iHandler),
('bx_events_fans', 'connection_added', @iHandler),
('bx_events_fans', 'connection_removed', @iHandler),
('profile', 'delete', @iHandler),
('bx_events', 'fan_added', @iHandler),
('bx_events', 'join_invitation', @iHandler),
('bx_events', 'join_request', @iHandler),
('bx_events', 'join_request_accepted', @iHandler),
('bx_events', 'timeline_view', @iHandler),
('bx_events', 'timeline_post', @iHandler),
('bx_events', 'timeline_delete', @iHandler),
('bx_events', 'timeline_comment', @iHandler),
('bx_events', 'timeline_vote', @iHandler),
('bx_events', 'timeline_report', @iHandler),
('bx_events', 'timeline_repost', @iHandler);

-- PRIVACY 
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_events_allow_view_to', 'bx_events', 'view', '_bx_events_form_profile_input_allow_view_to', '3', 'bx_events_data', 'id', 'author', 'BxEventsPrivacy', 'modules/boonex/events/classes/BxEventsPrivacy.php'),
('bx_events_allow_view_notification_to', 'bx_events', 'view_event', '_bx_events_form_profile_input_allow_view_notification_to', '3', 'bx_notifications_events', 'id', 'object_owner_id', 'BxEventsPrivacyNotifications', 'modules/boonex/events/classes/BxEventsPrivacyNotifications.php');

-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_events', '_bx_events_email_join_request', 'bx_events_join_request', '_bx_events_email_join_request_subject', '_bx_events_email_join_request_body'),
('bx_events', '_bx_events_email_join_reject', 'bx_events_join_reject', '_bx_events_email_join_reject_subject', '_bx_events_email_join_reject_body'),
('bx_events', '_bx_events_email_join_confirm', 'bx_events_join_confirm', '_bx_events_email_join_confirm_subject', '_bx_events_email_join_confirm_body'),
('bx_events', '_bx_events_email_fan_remove', 'bx_events_fan_remove', '_bx_events_email_fan_remove_subject', '_bx_events_email_fan_remove_body'),
('bx_events', '_bx_events_email_fan_become_admin', 'bx_events_fan_become_admin', '_bx_events_email_fan_become_admin_subject', '_bx_events_email_fan_become_admin_body'),
('bx_events', '_bx_events_email_admin_become_fan', 'bx_events_admin_become_fan', '_bx_events_email_admin_become_fan_subject', '_bx_events_email_admin_become_fan_body'),
('bx_events', '_bx_events_email_invitation', 'bx_events_invitation', '_bx_events_email_invitation_subject', '_bx_events_email_invitation_body'),
('bx_events', '_bx_events_email_reminder', 'bx_events_reminder', '_bx_events_email_reminder_subject', '_bx_events_email_reminder_body');

-- UPLOADERS
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_events_cover_crop', 1, 'BxEventsUploaderCoverCrop', 'modules/boonex/events/classes/BxEventsUploaderCoverCrop.php'),
('bx_events_picture_crop', 1, 'BxEventsUploaderPictureCrop', 'modules/boonex/events/classes/BxEventsUploaderPictureCrop.php');

-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_events_process_reminders', '55 * * * *', 'BxEventsCronProcessReminders', 'modules/boonex/events/classes/BxEventsCronProcessReminders.php', '');
