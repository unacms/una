
DELETE FROM `sys_email_templates` WHERE `Name` = 't_PrivPhotosAnswer' OR `Name` = 't_PrivPhotosRequest' OR `Name` = 't_PurchaseContacts' OR `Name` = 't_Share_Media' OR `Name` = 't_Report_Media';

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.0.RC5', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.0.RC5';

