SET @sName = 'bx_stripe_connect';


-- LOGS
DELETE FROM `sys_objects_logs` WHERE `object`='bx_stripe_connect';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_stripe_connect', 'bx_stripe_connect', 'Auto', '_bx_stripe_connect_log', 1, '', '');


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='integrations' WHERE `page_id`=@iPageId;
