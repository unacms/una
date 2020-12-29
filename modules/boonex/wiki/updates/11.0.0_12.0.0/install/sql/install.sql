-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_wiki' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
