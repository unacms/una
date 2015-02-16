

ALTER TABLE `sys_objects_search` ADD `Order` int(11) NOT NULL AFTER `Title`;

ALTER TABLE `sys_pages_blocks` ADD `title_system` varchar(255) NOT NULL AFTER `module`;


-- can be safely applied multiple times


UPDATE `sys_objects_menu` SET `template_id` = 6 WHERE `object` = 'sys_cmts_item_manage';
UPDATE `sys_objects_menu` SET `template_id` = 15 WHERE `object` = 'sys_cmts_item_actions';


DELETE FROM `sys_objects_captcha` WHERE `object` = 'sys_recaptcha_new';
INSERT INTO `sys_objects_captcha` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_recaptcha_new', 'reCAPTCHA New', 'BxTemplCaptchaReCAPTCHANew', '');


UPDATE `sys_pages_blocks` SET `deletable` = 1, `copyable` = 0 WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_login';


-- last step is to update current version


UPDATE `sys_modules` SET `version` = '8.0.0-A11' WHERE `version` = '8.0.0-A10' AND `name` = 'system';

