-- LOGS
DELETE FROM `sys_objects_logs` WHERE `object`='bx_smtp_mailer';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_smtp_mailer', 'system', 'Auto', '_bx_smtp_mailer_log', 1, '', '');
