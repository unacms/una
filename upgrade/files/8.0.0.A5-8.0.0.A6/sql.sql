

DELETE FROM `sys_email_templates` WHERE `Name` = 't_BgOperationFailed';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_bg_operation_failed', 't_BgOperationFailed', '_sys_et_txt_subject_bg_operation_failed', '_sys_et_txt_body_bg_operation_failed');


DELETE FROM `sys_options` WHERE  `name` = 'sys_oauth_user' OR `name` = 'sys_account_default_acl_level';


CREATE TABLE IF NOT EXISTS `sys_objects_metatags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `table_keywords` varchar(255) NOT NULL,
  `table_locations` varchar(255) NOT NULL,
  `table_mentions` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A6' WHERE `version` = '8.0.0-A5' AND `name` = 'system';

