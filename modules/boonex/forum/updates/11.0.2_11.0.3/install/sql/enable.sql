SET @sName = 'bx_forum';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='bx_forum_files_cmts' AND `action`='file_deleted' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_forum_files_cmts', 'file_deleted', @iHandler);
