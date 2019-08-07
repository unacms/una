SET @sName = 'bx_notifications';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='sys_cmts' AND `action` IN ('doVote', 'undoVote') AND `handler_id`=@iHandler;
DELETE FROM `sys_alerts` WHERE `unit`='sys_cmts_reactions' AND `action` IN ('doVote', 'undoVote') AND `handler_id`=@iHandler;
DELETE FROM `sys_alerts` WHERE `unit`='sys_cmts' AND `action` IN ('doVoteUp', 'doVoteDown') AND `handler_id`=@iHandler;

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_cmts', 'doVote', @iHandler),
('sys_cmts', 'undoVote', @iHandler),

('sys_cmts_reactions', 'doVote', @iHandler),
('sys_cmts_reactions', 'undoVote', @iHandler),

('sys_cmts', 'doVoteUp', @iHandler),
('sys_cmts', 'doVoteDown', @iHandler);
