SET @sName = 'bx_decorous';


-- injections
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:11:"bx_decorous";s:6:"method";s:14:"include_css_js";}', 0, 1);