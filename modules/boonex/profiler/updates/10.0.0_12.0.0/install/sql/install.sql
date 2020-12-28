-- LOGS
DELETE FROM `sys_objects_logs` WHERE `object`='bx_profiler';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_profiler', 'bx_profiler', 'Auto', '_bx_profiler_log', 1, '', '');


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_profiler' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='extensions' WHERE `page_id`=@iPageId;
