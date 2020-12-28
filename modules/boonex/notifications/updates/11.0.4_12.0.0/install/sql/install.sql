SET @sName = 'bx_notifications';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='extensions' WHERE `page_id`=@iPageId;
