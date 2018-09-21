SET @sName = 'bx_invites';


-- PAGES
UPDATE `sys_pages_blocks` SET `title_system`='_bx_invites_page_block_title_system_invite_form' WHERE `object`='bx_invites_invite' AND `title`='_bx_invites_page_block_title_invite_form';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='account' AND `action`='delete' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'delete', @iHandler);
