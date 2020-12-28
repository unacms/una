-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_author' AND `title`='_bx_market_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_author', 1, 'bx_market', '_bx_market_page_block_title_sys_entries_in_context', '_bx_market_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_market_page_block_title_sys_view_profile' WHERE `module`='bx_market' AND `title`='_bx_market_page_block_title_view_profile';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view_actions' AND `name`='notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_market_view_actions', 'bx_market', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_market_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_submenu' AND `name`='more-auto';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_market_submenu', 'bx_market', 'more-auto', '_sys_menu_item_title_system_more_auto', '_sys_menu_item_title_more_auto', 'javascript:void(0)', '', '', '', '', '', '', 2147483647, '', 1, 0, 9999);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_market_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_market_administration', 'bulk', 'clear_reports', '_bx_market_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_market_administration', 'single', 'clear_reports', '_bx_market_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);
