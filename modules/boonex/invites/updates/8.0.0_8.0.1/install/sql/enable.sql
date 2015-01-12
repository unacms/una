DELETE FROM `sys_email_templates` WHERE `Module`='bx_invites' AND `Name`='bx_invites_invite_by_request_message' LIMIT 1;
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_invites', '_bx_invites_et_invite_by_request_message', 'bx_invites_invite_by_request_message', '_bx_invites_et_invite_by_request_message_subject', '_bx_invites_et_invite_by_request_message_body');


SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_invites' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action`='delete' AND `handler_id`=@iHandler LIMIT 1;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES ('profile', 'delete', @iHandler);