-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name`='bx_fontawesome';
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_fontawesome', 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:14:"bx_fontawesome";s:6:"method";s:9:"injection";}', 0, 1);
