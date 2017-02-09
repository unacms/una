-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_home' AND `title` IN ('_bx_market_page_block_title_featured_entries_view_extended');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_home', 2, 'bx_market', '', '_bx_market_page_block_title_featured_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 1, 0);


-- MENU
DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_view_popup';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_popup', '_bx_market_menu_title_view_entry_popup', 'bx_market_view', 'bx_market', 4, 0, 1, 'BxMarketMenuView', 'modules/boonex/market/classes/BxMarketMenuView.php');

UPDATE `sys_menu_items` SET `icon`='credit-card' WHERE `set_name`='bx_market_view' AND `name`='subscribe';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_snippet';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_snippet', '_bx_market_menu_title_snippet', 'bx_market_snippet', 'bx_market', 17, 0, 1, 'BxMarketMenuSnippetActions', 'modules/boonex/market/classes/BxMarketMenuSnippetActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_snippet';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_snippet', 'bx_market', '_bx_market_menu_set_title_snippet', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_snippet' AND `name` IN ('snippet-more');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_market_snippet', 'bx_market', 'snippet-more', '_bx_market_menu_item_title_system_snippet_more', '_bx_market_menu_item_title_snippet_more', 'javascript:void(0)', 'bx_menu_popup(''bx_market_view_popup'', this, {''id'':''bx_market_snippet_{content_id}''}, {id:{content_id}});', '', 'ellipsis-v', '', 'bx_market_view_popup', 1, 2147483647, 1, 0, 0, 1);