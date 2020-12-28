-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_shopify_author' AND `title`='_bx_shopify_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_shopify_author', 1, 'bx_shopify', '_bx_shopify_page_block_title_sys_entries_in_context', '_bx_shopify_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_shopify";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_shopify_page_block_title_sys_my_entries' WHERE `module`='bx_shopify' AND `title`='_bx_shopify_page_block_title_my_entries';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_shopify_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_shopify_administration', 'bulk', 'clear_reports', '_bx_shopify_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_shopify_administration', 'single', 'clear_reports', '_bx_shopify_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);
