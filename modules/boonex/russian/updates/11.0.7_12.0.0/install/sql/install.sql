SET @sName = 'bx_ru';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='appearance' WHERE `page_id`=@iPageId;
