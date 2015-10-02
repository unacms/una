SET @sName = 'bx_air';


-- PAGES & BLOCKS
SET @iPBCellHome = 1;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('sys_home', @iPBCellHome, @sName, '_bx_air_page_block_title_splash_home', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_air";s:6:"method";s:10:"get_splash";}', 0, 0, @iPBOrderHome + 1);