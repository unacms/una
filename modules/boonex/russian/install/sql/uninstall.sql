--
-- General section.
--
SET @sName = 'bx_ru';

DELETE FROM `tll`, `tls`
USING `sys_localization_languages` AS `tll` LEFT JOIN `sys_localization_strings` AS `tls` ON `tll`.`ID`=`tls`.`IDLanguage`
WHERE `tll`.`Name`='ru';


--
-- Studio page and widget.
--
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id`=`tw`.`page_id` AND `tw`.`id`=`tpw`.`widget_id` AND `tp`.`name`=@sName;


--
-- Settings.
--
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`=@sName;