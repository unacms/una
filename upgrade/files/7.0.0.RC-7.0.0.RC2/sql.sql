
-- ------------------------
INSERT IGNORE INTO `sys_email_templates` VALUES(54, 't_FriendRequest', 'Friend request', '<html>\r\n<body style="font: 12px Verdana; color:#000000">\r\n    <p><b>Dear <Recipient></b>,</p>\r\n    <br />\r\n    <p><a href="<SenderLink>"><Sender></a> is inviting you to be friends. To accept/reject his/her invitation please \r\n    follow this <a href="<RequestLink>">link</a></p>\r\n    <br /> \r\n    <p><b>Thank you for using our services!</b></p> \r\n    <p>--</p>\r\n    <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! \r\n    <br />Auto-generated e-mail, please, do not reply!!!</p>\r\n</html>', 'Friend request message', 0);

-- ------------------------

DELETE FROM `sys_alerts_handlers` WHERE `id` < 5;
DELETE FROM `sys_alerts` WHERE `handler_id` < 5;

INSERT INTO `sys_alerts_handlers` (`id`, `name`, `class`, `file`) VALUES
(1, 'system', 'BxDolAlertsResponseSystem', 'inc/classes/BxDolAlertsResponseSystem.php'),
(2, 'profile', 'BxDolAlertsResponseProfile', 'inc/classes/BxDolAlertsResponseProfile.php'),
(3, 'membersData', 'BxDolUpdateMembersCache', 'inc/classes/BxDolUpdateMembersCache.php'),
(4, 'profileMatch', 'BxDolAlertsResponceMatch', 'inc/classes/BxDolAlertsResponceMatch.php');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'begin', 1),
('profile', 'before_join', 2),
('profile', 'join', 2),
('profile', 'before_login', 2),
('profile', 'login', 2),
('profile', 'logout', 2),
('profile', 'join', 3),
('profile', 'edit', 3),
('profile', 'delete', 3),
('profile', 'join', 4),
('profile', 'edit', 4),
('profile', 'delete', 4),
('profile', 'change_status', 4);

-- ------------------------

DELETE FROM `sys_options` WHERE `Name` = 'search_start_age' LIMIT 1;
DELETE FROM `sys_options` WHERE `Name` = 'search_end_age' LIMIT 1;
INSERT INTO `sys_options` VALUES('search_start_age', '18', 1, 'Lowest age possible for site members', 'digit', 'return setSearchStartAge((int)$arg0);', '', 20, '');
INSERT INTO `sys_options` VALUES('search_end_age', '75', 1, 'Highest age possible for site members', 'digit', 'return setSearchEndAge((int)$arg0);', '', 21, '');

-- ------------------------

INSERT IGNORE INTO `sys_options` VALUES('sys_security_impact_threshold_log', '9', 3, 'Total security impact threshold to send report', 'digit', '', '', 0, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_security_impact_threshold_block', '27', 3, 'Total security impact threshold to send report and block aggressor', 'digit', '', '', 0, '');
INSERT IGNORE INTO `sys_options` VALUES('friends_per_page', '14', 1, 'Number of friends to display per page in profile', 'digit', '', '', 0, '');

-- delete all language strings and keys for undefined categories

DELETE `sys_localization_strings` FROM `sys_localization_strings` INNER JOIN `sys_localization_keys` WHERE `sys_localization_keys`.`IDCategory` = 100 AND `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey`;
DELETE FROM `sys_localization_keys` WHERE `IDCategory` = 100;

-- last step is to update current version

INSERT INTO `sys_options` VALUES('sys_tmp_version', '7.0.0.RC2', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '');

