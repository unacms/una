-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_shopify' WHERE `name`='bx_shopify';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_shopify' WHERE `name`='bx_shopify';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_shopify' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='integrations' WHERE `page_id`=@iPageId;
