
-- alter tables

ALTER TABLE `sys_albums_objects` ADD INDEX ( `id_object` );


ALTER TABLE `sys_ip_list` ADD INDEX `From` (`From`);
ALTER TABLE `sys_ip_list` ADD INDEX `To` (`To`);


ALTER TABLE `sys_admin_ban_list` CHANGE `ProfID` `ProfID` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `sys_admin_ban_list` CHANGE `Time` `Time` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0';


ALTER TABLE `sys_ip_members_visits` ADD `MemberID` int(10) unsigned NOT NULL AFTER `ID`;


ALTER TABLE `sys_localization_keys` CHANGE `ID` `ID` int(10) unsigned NOT NULL auto_increment;
ALTER TABLE `sys_localization_string_params` CHANGE `IDKey` `IDKey` int(10) unsigned NOT NULL default '0';
ALTER TABLE `sys_localization_strings` CHANGE `IDKey` `IDKey` int(10) unsigned NOT NULL default '0';


-- new tables 

CREATE TABLE IF NOT EXISTS `sys_dnsbl_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chain` enum('spammers','whitelist','uridns') NOT NULL,
  `zonedomain` varchar(255) NOT NULL,
  `postvresp` varchar(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `recheck` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `added` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

TRUNCATE TABLE `sys_dnsbl_rules`;

INSERT INTO `sys_dnsbl_rules` (`id`, `chain`, `zonedomain`, `postvresp`, `url`, `recheck`, `comment`, `added`, `active`) VALUES
(1, 'whitelist', 'au.countries.nerd.dk.', '127.0.0.2', 'http://countries.nerd.dk/', '', 'Country based zone, any ip from Australia is whitelisted', 1287642420, 0),
(2, 'spammers', 'sbl.spamhaus.org.', 'any', 'http://www.spamhaus.org/sbl/', 'http://www.spamhaus.org/query/bl?ip=%s', 'Any non-failure result from sbl.spamhaus.org is a positive match', 1287642420, 1),
(3, 'spammers', 'zomgbl.spameatingmonkey.net.', 'any', 'http://spameatingmonkey.com/index.html', '', 'This zone is guaranteed to block 100% of all IPs because it lists everything (0.0.0.0/0). This list should never be used in production but exists to verify overall functionality of the blacklist servers.', 1287642420, 0),
(4, 'spammers', 'cn.countries.nerd.dk.', '127.0.0.2', 'http://countries.nerd.dk/', '', 'Country based zone, any ip from China is blocked', 1287642420, 0),
(5, 'uridns', 'multi.surbl.org.', 'any', 'http://www.surbl.org/', 'http://george.surbl.org/lookup.html', 'SURBLs are lists of web sites that have appeared in unsolicited messages. Unlike most lists, SURBLs are not lists of message senders.', 1287642420, 1);

CREATE TABLE IF NOT EXISTS `sys_antispam_block_log` (
  `ip` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `extra` text NOT NULL,
  `added` int(11) NOT NULL,
  KEY `ip` (`ip`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_dnsbluri_zones` (
  `level` tinyint(4) NOT NULL,
  `zone` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- regular updates/changes

SET @iParentId = (SELECT `id` FROM `sys_menu_admin` WHERE `name` = 'tools' LIMIT 1);
DELETE FROM `sys_menu_admin` WHERE `name` = 'antispam' AND `parent_id` = @iParentId;
INSERT INTO `sys_menu_admin`(`parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(@iParentId, 'antispam', '_adm_mmi_antispam', '{siteAdminUrl}antispam.php', 'Antispam Tools', 'mmi_antispam.png', '', '', 9);


UPDATE `sys_email_templates` SET `Body` = '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>You have requested <strong><profileNickName></strong>''s contact information.</p>\r\n\r\n<p><profileContactInfo></p>\r\n\r\n<p>View member''s profile: <a href="<Domain>profile.php?ID=<profileID>"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>' WHERE `Name` = 't_FreeEmail' AND `Body` = '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>You have requested <strong><profileNickName></strong>''s contact information.</p>\r\n\r\n<p><ContactInfo></p>\r\n\r\n<p>View member''s profile: <a href="<Domain>profile.php?ID=<profileID>"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>';

DELETE FROM `sys_email_templates` WHERE `Name` = 't_SpamReportAuto';
INSERT IGNORE INTO `sys_email_templates` VALUES(NULL, 't_SpamReportAuto', '<SiteName> Automatic Spam Report', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n\r\n\r\n<p>Spam report details:</p>\r\n<p>\r\n\r\n<b>Profile:</b> <a href="<SpammerUrl>"><SpammerNickName></a><br />\r\n\r\n<b>Page:</b> <Page><br />\r\n\r\n<b>GET variables:</b>\r\n<pre>\r\n<Get>\r\n</pre>\r\n\r\n<b>Spam Content:</b>\r\n<pre>\r\n<SpamContent>\r\n</pre>\r\n\r\n</p>\r\n\r\n\r\n<p>-----</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\nAuto-generated e-mail, please, do not reply!!!</p></body></html>', 'Automatic spam report template', 0);


UPDATE `sys_menu_member` SET `PopupMenu` = '$iCurrentMemberId = ''{ID}'';\r\n\r\n$aLinks = array(\r\n    ''activity'' => array(\r\n        ''url''     => ''communicator.php'',\r\n        ''icon''    => ''memeber_menu_sub_activity.png'',\r\n        ''caption'' => _t( ''_Activity'' ),\r\n        ''onclick'' => null,\r\n    ),\r\n);    \r\n\r\n$sOutputCode = null;\r\nforeach( $aLinks as $sKey => $aItems )\r\n{\r\n    $sIcon = $GLOBALS[''oFunctions''] -> getTemplateIcon($aLinks[$sKey][''icon'']);\r\n    $aTemplateKeys = array (\r\n\r\n        ''bx_if:item_img'' => array (\r\n            ''condition'' =>  ( $sIcon ),\r\n            ''content''   => array (\r\n                ''item_img_src''      => $sIcon,\r\n                ''item_img_alt''      => $aLinks[$sKey][''caption''],\r\n                ''item_img_width''    => 16,\r\n                ''item_img_height''   => 16,\r\n            ),\r\n        ),\r\n\r\n        ''item_link''    => ( $aLinks[$sKey][''url''] )     ? $aLinks[$sKey][''url''] : ''javascript:void(0)'',\r\n        ''item_onclick'' => ( $aLinks[$sKey][''onclick''] ) ? ''onclick="'' . $aLinks[$sKey][''onclick''] . '';return false"'' : null,\r\n        ''item_title''   => $aLinks[$sKey][''caption''],\r\n        ''extra_info''   => null,\r\n    );\r\n\r\n    $sOutputCode .= $GLOBALS[''oSysTemplate''] -> parseHtmlByName( ''member_menu_sub_item.html'', $aTemplateKeys );\r\n}\r\n\r\nreturn $sOutputCode ;\r\n' WHERE `Name` = 'Dashboard';

UPDATE `sys_menu_member` SET `Order` = 3 WHERE `Name` = 'Dashboard' AND `Order` = 2;


UPDATE `sys_options` SET `VALUE` = '0' WHERE `Name` = 'db_clean_profiles' AND `VALUE` = '9999';
UPDATE `sys_options` SET `VALUE` = '2' WHERE `Name` = 'ipBlacklistMode' AND `VALUE` = '1';
UPDATE `sys_options` SET `AvailableValues` = 'FileHtml,EAccelerator,Memcache,APC,XCache' WHERE `Name` = 'sys_template_cache_engine';
UPDATE `sys_options` SET `AvailableValues` = 'File,EAccelerator,Memcache,APC,XCache' WHERE `Name` = 'sys_db_cache_engine';
UPDATE `sys_options` SET `AvailableValues` = 'File,EAccelerator,Memcache,APC,XCache' WHERE `Name` = 'sys_pb_cache_engine';
UPDATE `sys_options` SET `AvailableValues` = 'File,EAccelerator,Memcache,APC,XCache' WHERE `Name` = 'sys_mm_cache_engine';

INSERT IGNORE INTO `sys_options` VALUES('sys_dnsbl_enable', '', 14, 'Enable DNS Block Lists', 'checkbox', '', '', 20, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_uridnsbl_enable', '', 14, 'Enable URI DNS Block Lists', 'checkbox', '', '', 22, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_akismet_enable', '', 14, 'Enable Akismet', 'checkbox', '', '', 24, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_akismet_api_key', '', 14, 'Akismet API Key', 'digit', '', '', 25, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_antispam_block', '', 14, 'Total block all spam content', 'checkbox', '', '', 27, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_antispam_report', 'on', 14, 'Send report to admin if spam content discovered', 'checkbox', '', '', 28, '');


UPDATE `sys_page_compose` SET `Cache` = 0 WHERE `Page` = 'index' AND `Desc` = 'Quick search form' AND `Cache` = 86400;


UPDATE `sys_menu_top` SET `Link` = 'profile_info.php' WHERE `Name` = 'Profile Info' AND `Link` = 'profile_info.php?ID={profileID}&my';


UPDATE `sys_objects_actions` SET `Eval` = 'if ( {ID} == {member_id} || isBlocked({member_id}, {ID}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = ( $bDisplayType ) \r\n	? "window.open( ''list_pop.php?action=block&amp;ID={ID}'', '''', ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'' );" \r\n	: "getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''list_pop.php?action=block&amp;ID={ID}&amp;mode=ajax'');return false;";\r\n	\r\nreturn $sOnclick;' WHERE `Caption` = '{cpt_block}' AND `Type` = 'Profile';

DELETE FROM `sys_objects_actions` WHERE `Caption` = '{cpt_unblock}' AND `Type` = 'Profile';
INSERT INTO `sys_objects_actions` (`ID`, `Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`, `bDisplayInSubMenuHeader`) VALUES
(NULL, '{cpt_unblock}', 'action_block.png', '', '{evalResult}', 'if ({ID} == {member_id} || !isBlocked({member_id}, {ID}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = ( $bDisplayType ) \r\n	? "window.open( ''list_pop.php?action=unblock&amp;ID={ID}'', '''', ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'' );" \r\n	: "getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''list_pop.php?action=unblock&amp;ID={ID}&amp;mode=ajax'');return false;";\r\n	\r\nreturn $sOnclick;\r\n', 9, 'Profile', 0);


-- delete unused language keys 

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_Hot or Not');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_Hot or Not');

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.4', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.4';

