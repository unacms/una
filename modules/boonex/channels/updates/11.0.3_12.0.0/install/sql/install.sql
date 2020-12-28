-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_channels' WHERE `name`='bx_channels';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_channels' WHERE `name`='bx_channels';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_channels' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='extensions' WHERE `page_id`=@iPageId;
