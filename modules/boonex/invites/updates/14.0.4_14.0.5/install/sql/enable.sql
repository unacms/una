SET @sName = 'bx_invites';


-- PAGES
SET @iPBCellDashboard = 1;
SET @iPBOrderDashboard = 0;
DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `title`='_bx_invites_page_block_title_request_form';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_home', @iPBCellDashboard, @sName, '_bx_invites_page_block_title_request_form', 11, 1, 'service', 'a:2:{s:6:"module";s:10:"bx_invites";s:6:"method";s:22:"get_block_form_request";}', 0, 0, 0, 1, @iPBOrderDashboard);
