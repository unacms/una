SET @sName = 'bx_decorous';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `cnt_actions`='a:4:{s:6:"module";s:11:"bx_decorous";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:6:"Module";}' WHERE `page_id`=@iPageId AND `module`=@sName;
