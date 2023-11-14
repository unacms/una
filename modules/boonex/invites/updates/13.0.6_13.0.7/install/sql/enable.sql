SET @sName = 'bx_invites';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`=@sName AND `title_system`='_bx_invites_page_block_title_system_invite_with_redirect';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, @sName, '_bx_invites_page_block_title_system_invite_with_redirect', '_bx_invites_page_block_title_invite', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_invites";s:6:"method";s:16:"get_block_invite";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 1, @iBlockOrder + 1);
