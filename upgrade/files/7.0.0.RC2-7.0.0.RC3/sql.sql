
DELETE FROM `sys_box_download` WHERE `id` = 1 OR `id` = 2;
INSERT INTO `sys_box_download` (`id`, `title`, `url`, `onclick`, `desc`, `icon`, `order`, `disabled`) VALUES
(1, '_sbd_iPhone_title', 'http://www.boonex.com/products/mobile/iphone/', '', '_sbd_iPhone_desc', 'iphone.png', 2, 0),
(2, '_sbd_Android_title', 'javascript:void(0);', '', '_sbd_Android_desc', 'android.png', 3, 1);

--

INSERT IGNORE INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES ('profile', 'edit', 2);

-- 

UPDATE `sys_menu_top` SET `Link` = '{memberLink}|{memberNick}|change_status.php' WHERE `ID` = 4;
UPDATE `sys_menu_top` SET `Link` = '{memberLink}|{memberNick}|profile.php?ID={memberID}' WHERE `ID` = 11;
UPDATE `sys_menu_top` SET `Link` = '{profileLink}|{profileNick}|profile.php?ID={profileID}' WHERE `ID` = 60;

-- 

DELETE FROM `sys_profile_fields` WHERE `ID` = 68;
INSERT INTO `sys_profile_fields` VALUES(68, 'allow_view_to', 'system', NULL, '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);

-- 

ALTER TABLE `sys_ip_list` CHANGE `LastDT` `LastDT` INT( 11 ) UNSIGNED NOT NULL;

-- Burma was changes to Myanmar some time ago

UPDATE `sys_countries` SET `Country` = 'Myanmar' WHERE `ISO2` = 'MM';

-- profile edit page supports page builders now

INSERT INTO `sys_page_compose_pages` VALUES('pedit', 'Profile Edit', 8, 1);
INSERT INTO `sys_page_compose` VALUES(NULL, 'pedit', '998px', 'Profile fields', '_edit_profile_info', 1, 1, 'Info', '', 1, 50, 'memb', 0);
INSERT INTO `sys_page_compose` VALUES(NULL, 'pedit', '998px', 'Profile privacy', '_edit_profile_privacy', 2, 1, 'Privacy', '', 1, 50, 'memb', 0);

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.0.RC3', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.0.RC3';

