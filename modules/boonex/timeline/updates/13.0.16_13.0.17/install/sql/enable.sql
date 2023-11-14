SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_timeline' AND `title`='_bx_timeline_page_block_title_view_channels';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_channels', '_bx_timeline_page_block_title_view_channels', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:2:{s:4:"view";s:8:"timeline";s:4:"type";s:8:"channels";}}}', 0, 1, 1, @iBlockOrder + 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_share' AND `name`='item-copy';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `active_api`, `copyable`, `order`) VALUES
('bx_timeline_menu_item_share', 'bx_timeline', 'item-copy', '_bx_timeline_menu_item_title_system_item_copy', '_bx_timeline_menu_item_title_item_copy', 'javascript:void(0)', 'javascript:{view_js_object}.copyToClipboard(this, ''{content_url}'')', '_self', 'copy', '', 2147483647, 1, 1, 0, 5);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-report';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-report', '_bx_timeline_menu_item_title_system_item_report', '', 'javascript:void(0)', '', '', '', '', '', 1, 2147483647, 0, 0, 1, 57);
