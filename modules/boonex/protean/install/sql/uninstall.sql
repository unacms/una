SET @sName = 'bx_protean';


-- SETTINGS
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`=@sName;
DELETE FROM `sys_options` WHERE `name` LIKE 'bx_protean_site_%';


-- MIXES
DELETE FROM `tom`, `tomo` USING `sys_options_mixes` AS `tom` LEFT JOIN `sys_options_mixes2options` AS `tomo` ON `tom`.`id`=`tomo`.`mix_id` WHERE `tom`.`type`=@sName;


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
