SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='2' WHERE `object`='bx_timeline_item';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_item' AND `title`='_bx_timeline_page_block_title_item_info';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_item', 2, 'bx_timeline', '', '_bx_timeline_page_block_title_item_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_item_info";}', 0, 0, 1, 1);


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `table_field_author`='owner_id' WHERE `object`='bx_timeline_privacy_view';
