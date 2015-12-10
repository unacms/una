

ALTER TABLE `sys_cmts_votes_track` ADD `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;


ALTER TABLE `sys_menu_items` ADD `submenu_popup` tinyint(4) NOT NULL DEFAULT '0' AFTER `submenu_object`;


-- can be safely applied multiple times



ALTER TABLE  `sys_options_types` CHANGE  `icon`  `icon` VARCHAR(255) NOT NULL;



UPDATE `sys_options` SET `value` = 'sys_recaptcha' WHERE `name` = 'sys_captcha_default' AND `value` = 'sys_recaptcha_new';



SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_studio_settings_save_design' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_studio_settings_save_design', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:28:"alert_response_settings_save";s:6:"params";a:0:{}s:5:"class";s:25:"TemplStudioDesignServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iIdHandler);



UPDATE `sys_form_inputs` SET `editable` = 1 WHERE `object` = 'sys_account' AND `module` = 'system' AND `name` = 'captcha';



UPDATE `sys_menu_items` SET `submenu_popup` = 1 WHERE `set_name` = 'sys_toolbar_member' AND `module` = 'system' AND `name` = 'account';



UPDATE `sys_objects_transcoder` SET `source_params` = 'a:1:{s:6:"object";s:10:"sys_images";}' WHERE `object` = 'sys_icon_favicon';



TRUNCATE TABLE `sys_pages_design_boxes`;

INSERT INTO `sys_pages_design_boxes` (`id`, `title`, `template`, `order`) VALUES
(0, '_sys_designbox_0', 'designbox_0.html', '2'),
(1, '_sys_designbox_1', 'designbox_1.html', '8'),
(2, '_sys_designbox_2', 'designbox_2.html', '1'),
(3, '_sys_designbox_3', 'designbox_3.html', '4'),
(4, '_sys_designbox_4', 'designbox_4.html', '6'),
(10, '_sys_designbox_10', 'designbox_10.html', '3'),
(11, '_sys_designbox_11', 'designbox_11.html', '9'),
(13, '_sys_designbox_13', 'designbox_13.html', '5'),
(14, '_sys_designbox_14', 'designbox_14.html', '7');



-- last step is to update current version


UPDATE `sys_modules` SET `version` = '8.0.0-RC' WHERE `version` = '8.0.0-B2' AND `name` = 'system';

