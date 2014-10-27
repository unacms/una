
ALTER TABLE `sys_menu_items` ADD `editable` tinyint(4) NOT NULL DEFAULT '1' AFTER `copyable`;

-- can be safely applied multiple times

UPDATE `sys_objects_form` SET `override_class_name` = 'BxTemplCmtsForm' WHERE `object` = 'sys_comment';

DELETE FROM `sys_menu_templates` WHERE `id` = 15;
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`) VALUES
(15, 'menu_custom.html', '_sys_menu_template_title_custom');


-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A7' WHERE `version` = '8.0.0-A6' AND `name` = 'system';

