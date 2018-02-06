SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_timeline_item_brief';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_item_brief', '_bx_timeline_page_title_sys_item_brief', '_bx_timeline_page_title_item_brief', 'bx_timeline', 2, 2147483647, 1, 'timeline-item-quick', 'page.php?i=timeline-item-quick', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_item_brief';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_item_brief', 1, 'bx_timeline', '', '_bx_timeline_page_block_title_item_content', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_item_content";}', 0, 0, 1, 1),
('bx_timeline_item_brief', 2, 'bx_timeline', '', '_bx_timeline_page_block_title_item_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_item_info";}', 0, 0, 1, 1),
('bx_timeline_item_brief', 2, 'bx_timeline', '', '_bx_timeline_page_block_title_item_comments', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_item_comments";}', 0, 0, 1, 2);


-- SETTINGS
UPDATE `sys_options` SET `value`='12' WHERE `name`='bx_timeline_events_per_page_profile';
UPDATE `sys_options` SET `value`='12' WHERE `name`='bx_timeline_events_per_page_account';
UPDATE `sys_options` SET `value`='24' WHERE `name`='bx_timeline_events_per_page_home';
UPDATE `sys_options` SET `value`='24' WHERE `name`='bx_timeline_events_per_page';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_timeline';


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_timeline_meta_mentions' WHERE `object`='bx_timeline';
