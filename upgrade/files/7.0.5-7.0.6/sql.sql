
UPDATE `sys_box_download` SET `url` = 'https://market.android.com/details?id=com.boonex.oo',`disabled` = '0' WHERE `title` = '_sbd_Android_title';

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.6', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.6';

