-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_joined' AND `title` IN ('_bx_events_page_block_title_entries_actions', '_bx_events_page_block_title_entries_of_author');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_entries_actions', '_bx_events_page_block_title_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:18:"my_entries_actions";}', 0, 0, 1, 1),
('bx_events_joined', 1, 'bx_events', '_bx_events_page_block_title_sys_entries_of_author', '_bx_events_page_block_title_entries_of_author', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:22:"browse_created_entries";}', 0, 0, 0, 2);

UPDATE `sys_pages_blocks` SET `order`='3' WHERE `object`='bx_events_joined' AND `title`='_bx_events_page_block_title_favorites_of_author';
UPDATE `sys_pages_blocks` SET `order`='4' WHERE `object`='bx_events_joined' AND `title`='_bx_events_page_block_title_joined_entries';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_events_my';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_my', '_bx_events_menu_title_entries_my', 'bx_events_my', 'bx_events', 9, 0, 1, 'BxEventsMenu', 'modules/boonex/events/classes/BxEventsMenu.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_events_my';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_my', 'bx_events', '_bx_events_menu_set_title_entries_my', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_my';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_my', 'bx_events', 'create-event-profile', '_bx_events_menu_item_title_system_create_profile', '_bx_events_menu_item_title_create_profile', 'page.php?i=create-event-profile', '', '', 'plus', '', 2147483647, 1, 0, 0);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_events_snippet_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_events_snippet_meta', 'bx_events', 15, 0, 1, 'BxEventsMenuSnippetMeta', 'modules/boonex/events/classes/BxEventsMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_events_snippet_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_events_snippet_meta', 'bx_events', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_snippet_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_events_snippet_meta', 'bx_events', 'join', '_sys_menu_item_title_system_sm_join', '_sys_menu_item_title_sm_join', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_events_snippet_meta', 'bx_events', 'leave', '_sys_menu_item_title_system_sm_leave', '_sys_menu_item_title_sm_leave', '', '', '', '', '', 2147483647, 0, 0, 1, 2),
('bx_events_snippet_meta', 'bx_events', 'subscribe', '_sys_menu_item_title_system_sm_subscribe', '_sys_menu_item_title_sm_subscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 3),
('bx_events_snippet_meta', 'bx_events', 'unsubscribe', '_sys_menu_item_title_system_sm_unsubscribe', '_sys_menu_item_title_sm_unsubscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_events_snippet_meta', 'bx_events', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_events_snippet_meta', 'bx_events', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 6),
('bx_events_snippet_meta', 'bx_events', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 7),
('bx_events_snippet_meta', 'bx_events', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 0, 0, 1, 8),
('bx_events_snippet_meta', 'bx_events', 'members', '_sys_menu_item_title_system_sm_members', '_sys_menu_item_title_sm_members', '', '', '', '', '', 2147483647, 1, 0, 1, 9),
('bx_events_snippet_meta', 'bx_events', 'subscribers', '_sys_menu_item_title_system_sm_subscribers', '_sys_menu_item_title_sm_subscribers', '', '', '', '', '', 2147483647, 0, 0, 1, 10);
