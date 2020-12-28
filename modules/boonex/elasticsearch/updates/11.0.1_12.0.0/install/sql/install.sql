-- LOGS
DELETE FROM `sys_objects_logs` WHERE `object`='bx_elasticsearch';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_elasticsearch', 'bx_elasticsearch', 'Auto', '_bx_elasticsearch_log', 1, '', '');


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_elasticsearch' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='integrations' WHERE `page_id`=@iPageId;
