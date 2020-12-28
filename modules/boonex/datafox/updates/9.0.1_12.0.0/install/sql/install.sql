-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_datafox' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='integrations' WHERE `page_id`=@iPageId;
