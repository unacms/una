SET @sName = 'bx_media';

-- INJECTION
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:8:"bx_media";s:6:"method";s:10:"include_js";}', 0, 1);
