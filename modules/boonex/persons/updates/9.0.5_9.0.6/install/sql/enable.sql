-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_persons_page_block_title_profile_subscriptions', '_bx_persons_page_block_title_profile_subscribed_me');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_persons_view_profile', 2, 'bx_persons', '', '_bx_persons_page_block_title_profile_subscriptions', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:21:\"profile_subscriptions\";}', 0, 1, 0, 0),
('bx_persons_view_profile', 2, 'bx_persons', '', '_bx_persons_page_block_title_profile_subscribed_me', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:21:\"profile_subscribed_me\";}', 0, 1, 0, 0);

UPDATE `sys_pages_blocks` SET `copyable`='1' WHERE `object`='bx_persons_view_profile' AND `title`='_bx_persons_page_block_title_profile_friends';

UPDATE `sys_pages_blocks` SET `copyable`='0' WHERE `object`='bx_persons_profile_subscriptions' AND `title` IN ('_bx_persons_page_block_title_profile_subscriptions', '_bx_persons_page_block_title_profile_subscribed_me');


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_persons_snippet_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_persons_snippet_meta', 'bx_persons', 15, 0, 1, 'BxPersonsMenuSnippetMeta', 'modules/boonex/persons/classes/BxPersonsMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_persons_snippet_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_persons_snippet_meta', 'bx_persons', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_snippet_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_persons_snippet_meta', 'bx_persons', 'befriend', '_sys_menu_item_title_system_sm_befriend', '_sys_menu_item_title_sm_befriend', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_persons_snippet_meta', 'bx_persons', 'unfriend', '_sys_menu_item_title_system_sm_unfriend', '_sys_menu_item_title_sm_unfriend', '', '', '', '', '', 2147483647, 0, 0, 1, 2),
('bx_persons_snippet_meta', 'bx_persons', 'subscribe', '_sys_menu_item_title_system_sm_subscribe', '_sys_menu_item_title_sm_subscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 3),
('bx_persons_snippet_meta', 'bx_persons', 'unsubscribe', '_sys_menu_item_title_system_sm_unsubscribe', '_sys_menu_item_title_sm_unsubscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_persons_snippet_meta', 'bx_persons', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_persons_snippet_meta', 'bx_persons', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 6),
('bx_persons_snippet_meta', 'bx_persons', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 7),
('bx_persons_snippet_meta', 'bx_persons', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 0, 0, 1, 8),
('bx_persons_snippet_meta', 'bx_persons', 'friends', '_sys_menu_item_title_system_sm_friends', '_sys_menu_item_title_sm_friends', '', '', '', '', '', 2147483647, 1, 0, 1, 9),
('bx_persons_snippet_meta', 'bx_persons', 'subscribers', '_sys_menu_item_title_system_sm_subscribers', '_sys_menu_item_title_sm_subscribers', '', '', '', '', '', 2147483647, 0, 0, 1, 10);


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName`='bx_persons_cmts';
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_persons_cmts', '_bx_persons_cmts', @iSearchOrder + 1, 'BxPersonsCmtsSearchResult', 'modules/boonex/persons/classes/BxPersonsCmtsSearchResult.php');
