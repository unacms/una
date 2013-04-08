


-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.0', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.0';

