
-- alter tables

ALTER TABLE `Profiles` DROP INDEX `NickName_3`; 

ALTER TABLE `sys_friend_list` DROP INDEX `ID`;

ALTER TABLE `sys_ip_members_visits` ADD KEY `From` (`From`);

ALTER TABLE `sys_messages` ADD `TrashNotView` set('sender','recipient') NOT NULL;
ALTER TABLE `sys_messages` ADD KEY `TrashNotView` (`TrashNotView`);
ALTER TABLE `sys_messages` ADD KEY `Trash` (`Trash`);

ALTER TABLE `sys_page_compose` ADD `Cache` int(11) NOT NULL DEFAULT '0';

-- handlers

INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`) VALUES('bx_videos_comments_delete', 'BxDolVideoDeleteResponse', 'flash/modules/video_comments/inc/classes/BxDolVideoDeleteResponse.php');
SET @iHandlerId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_alerts`(`unit`, `action`, `handler_id`) VALUES('profile', 'commentRemoved', @iHandlerId);

DELETE FROM `sys_alerts` WHERE `unit` = 'block' AND `action` = 'add' AND `handler_id` = 5;
DELETE FROM `sys_alerts` WHERE `unit` = 'friend' AND `action` = 'request' AND `handler_id` = 5;

-- regular updates/changes

UPDATE `sys_page_compose` SET `Cache` = 3600 WHERE `Page` = 'index' AND `Func` = 'SiteStats';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'index' AND `Func` = 'QuickSearch';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'index' AND `Func` = 'LoginSection';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'index' AND `Func` = 'RSS' AND `Content` = 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'index' AND `Func` = 'Download';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'member' AND `Func` = 'RSS' AND `Content` = 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'join' AND `Func` = 'LoginSection';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'join' AND `Func` = 'PHP' AND `Content` = 'return _t(''_why_join_desc'');';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'search_home' AND `Func` = 'Keyword';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'tags_search' AND `Func` = 'Form';
UPDATE `sys_page_compose` SET `Cache` = 86400 WHERE `Page` = 'categ_search' AND `Func` = 'Form';


INSERT IGNORE INTO `sys_options` VALUES('db_clean_members_visits', '90', 11, 'Clean old IP members visits ( days )', 'digit', '', '', NULL, '');
INSERT IGNORE INTO `sys_options` VALUES('db_clean_banners_info', '60', 11, 'Clean banners views and clicks info ( days )', 'digit', '', '', NULL, '');

INSERT IGNORE INTO `sys_options` VALUES('sys_template_cache_engine', 'FileHtml', 13, 'Template cache engine (other than FileHtml option may require custom server setup)', 'select', '', '', 3, 'FileHtml,EAccelerator,Memcache');
INSERT IGNORE INTO `sys_options` VALUES('sys_template_cache_compress_enable', 'on', 13, 'Enable compression for JS/CSS files(cache should be enabled)', 'checkbox', '', '', 8, '');
UPDATE `sys_options` SET `order_in_kateg` = 4 WHERE `Name` = 'sys_template_cache_image_enable' AND `order_in_kateg` = 3;
UPDATE `sys_options` SET `order_in_kateg` = 5 WHERE `Name` = 'sys_template_cache_image_max_size' AND `order_in_kateg` = 4;
UPDATE `sys_options` SET `order_in_kateg` = 6 WHERE `Name` = 'sys_template_cache_css_enable' AND `order_in_kateg` = 5;
UPDATE `sys_options` SET `order_in_kateg` = 7 WHERE `Name` = 'sys_template_cache_js_enable' AND `order_in_kateg` = 6;
UPDATE `sys_options` SET `order_in_kateg` = 9 WHERE `Name` = 'sys_template_page_width_min' AND `order_in_kateg` = 7;
UPDATE `sys_options` SET `order_in_kateg` = 10 WHERE `Name` = 'sys_template_page_width_max' AND `order_in_kateg` = 8;

INSERT IGNORE INTO `sys_options` VALUES('sys_db_cache_enable', 'on', 3, 'Enable DB cache', 'checkbox', '', '', 20, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_db_cache_engine', 'File', 3, 'DB cache engine (other than File option may require custom server setup)', 'select', '', '', 21, 'File,EAccelerator,Memcache');
INSERT IGNORE INTO `sys_options` VALUES('sys_cache_memcache_host', '', 3, 'Memcached server host', 'digit', '', '', 30, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_cache_memcache_port', '11211', 3, 'Memcached server port', 'digit', '', '', 31, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_pb_cache_enable', 'on', 3, 'Enable page blocks cache', 'checkbox', '', '', 40, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_pb_cache_engine', 'File', 3, 'Page blocks cache engine (other than File option may require custom server setup)', 'select', '', '', 41, 'File,EAccelerator,Memcache');
INSERT IGNORE INTO `sys_options` VALUES('sys_mm_cache_engine', 'File', 3, 'Member menu cache engine (other than File option may require custom server setup)', 'select', '', '', 42, 'File,EAccelerator,Memcache');


UPDATE `sys_menu_admin_top` SET `url` = 'http://www.boonex.com/trac/dolphin/wiki/Dolphin7Docs' WHERE `name` = 'docs' AND `url` = 'http://www.boonex.com/trac/dolphin/wiki/DolphinDocs';


UPDATE `sys_email_templates` SET `Body` = '<html>\r\n<body style="font: 12px Verdana; color:#000000">\r\n    <p><b>Dear <Recipient></b>,</p>\r\n    <br />\r\n    <p><a href="<SenderLink>"><Sender></a> is inviting you to be friends. To accept/reject his/her invitation please \r\n    follow this <a href="<RequestLink>">link</a></p>\r\n    <br /> \r\n    <p><b>Thank you for using our services!</b></p> \r\n    <p>--</p>\r\n    <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! \r\n    <br />Auto-generated e-mail, please, do not reply!!!</p>\r\n</body></html>' WHERE `Name` = 't_FriendRequest' AND `Body` = '<html>\r\n<body style="font: 12px Verdana; color:#000000">\r\n    <p><b>Dear <Recipient></b>,</p>\r\n    <br />\r\n    <p><a href="<SenderLink>"><Sender></a> is inviting you to be friends. To accept/reject his/her invitation please \r\n    follow this <a href="<RequestLink>">link</a></p>\r\n    <br /> \r\n    <p><b>Thank you for using our services!</b></p> \r\n    <p>--</p>\r\n    <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! \r\n    <br />Auto-generated e-mail, please, do not reply!!!</p>\r\n</html>';


UPDATE `sys_objects_actions` SET `Eval` = 'if ({ID} == {member_id} OR is_friends({ID} , {member_id})) return;\r\n\r\n$bDisplayType = {display_type};\r\n$iWindowWidth = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = ( $bDisplayType ) \r\n ? "window.open( ''list_pop.php?action=friend&amp;ID={ID}'', '''', ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'' );" \r\n : "getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''list_pop.php?action=friend&amp;ID={ID}&amp;mode=ajax'');return false;";\r\n\r\nreturn $sOnclick;' WHERE `Type` = 'Profile' AND `Caption` = '{cpt_befriend}';
INSERT IGNORE INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`, `bDisplayInSubMenuHeader`) VALUES ('{cpt_remove_friend}', 'action_friends.png', '', '{evalResult}', 'if ({ID} == {member_id} OR !is_friends({ID} , {member_id}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = ( $bDisplayType ) \r\n    ? "window.open( ''list_pop.php?action=remove_friend&amp;ID={ID}'', '''', ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'' );" \r\n    : "getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''list_pop.php?action=remove_friend&amp;ID={ID}&amp;mode=ajax'');return false;";\r\n\r\nreturn $sOnclick;', 4, 'Profile', 0);


UPDATE `sys_injections` SET `data` = 'return getRayIntegrationJS(true);' WHERE `name` = 'flash_integration' AND `data` = 'return getRayIntegrationJS();';
DELETE FROM `sys_injections` WHERE `name` = 'profile_subscription';


UPDATE `sys_privacy_groups` SET `get_content` = '$aIds = $arg0->fromMemory($arg0->_sGroupContactsCache . $arg1, "getColumn", "SELECT `tp`.`ID` AS `id` FROM `sys_messages` AS `tm` INNER JOIN `Profiles` AS `tp` ON (`tm`.`Sender`=`tp`.`ID` AND `tm`.`Recipient`=\'" . $arg1 . "\') OR (`tm`.`Recipient`=`tp`.`ID` AND `tm`.`Sender`=\'" . $arg1 . "\')"); return in_array($arg2, $aIds);' WHERE `home_url` = 'mail.php?&mode=inbox&contacts_mode=Contacted' AND `get_content` = '$aIds = $arg0->fromMemory($arg0->_sGroupContactsCache . $arg1, "getColumn", "SELECT `tp`.`ID` AS `id` FROM `sys_messages` AS `tm` LEFT JOIN `Profiles` AS `tp` ON (`tm`.`Sender`=`tp`.`ID` AND `tm`.`Recipient`=\'" . $arg1 . "\') OR (`tm`.`Recipient`=`tp`.`ID` AND `tm`.`Sender`=\'" . $arg1 . "\')"); return in_array($arg2, $aIds);';


-- delete unused language keys 

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_guestbook', '_This guestbook disabled by it''s owner', '_use guestbook', '_view other members'' guestbooks', '_groups count', '_Groups', '_My Groups', '_Group not found', '_Group not found_desc', '_Group is hidden', '_Sorry, group is hidden', '_Group creator', '_About group', '_Group type', '_Public group', '_Private group', '_Group members', '_Edit group', '_Resign group', '_Join group', '_Are you sure want to Resign group?', '_Are you sure want to Join group?', '_Create Group', '_Group creation successful', '_Group creation unknown error', '_Edit Group', '_Groups Home', '_Groups categories', '_Group gallery', '_You cannot view gallery while not a group member', '_You cannot view group members while not a group member');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_guestbook', '_This guestbook disabled by it''s owner', '_use guestbook', '_view other members'' guestbooks', '_groups count', '_Groups', '_My Groups', '_Group not found', '_Group not found_desc', '_Group is hidden', '_Sorry, group is hidden', '_Group creator', '_About group', '_Group type', '_Public group', '_Private group', '_Group members', '_Edit group', '_Resign group', '_Join group', '_Are you sure want to Resign group?', '_Are you sure want to Join group?', '_Create Group', '_Group creation successful', '_Group creation unknown error', '_Edit Group', '_Groups Home', '_Groups categories', '_Group gallery', '_You cannot view gallery while not a group member', '_You cannot view group members while not a group member');

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_group creator', '_Are you sure want to delete this member?', '_Delete member', '_Search Groups', '_by group name', '_Sorry, no groups found', '_Groups search results', '_No my groups found', '_Hidden group', '_Members can post images', '_Members can invite', '_Group description', '_Group name already exists', '_Group action', '_Upload to group gallery error', '_Upload to group gallery', '_You cannot upload images because members of this group not allowed to upload images', '_You cannot upload images because you''re not group member', '_Group join error', '_You''re already in group', '_Group join', '_Congrats. Now you''re group member', '_Request sent to the group creator. You will become active group member when he approve you.', '_Group resign error', '_You cannot resign the group because you''re creator', '_Group resign', '_You succesfully resigned from group', '_You cannot resign the group because you''re not group member', '_Group thumnail set', '_You cannot set group thumnail because you are not group creator', '_Group image delete', '_You cannot delete image because you are not group creator');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_group creator', '_Are you sure want to delete this member?', '_Delete member', '_Search Groups', '_by group name', '_Sorry, no groups found', '_Groups search results', '_No my groups found', '_Hidden group', '_Members can post images', '_Members can invite', '_Group description', '_Group name already exists', '_Group action', '_Upload to group gallery error', '_Upload to group gallery', '_You cannot upload images because members of this group not allowed to upload images', '_You cannot upload images because you''re not group member', '_Group join error', '_You''re already in group', '_Group join', '_Congrats. Now you''re group member', '_Request sent to the group creator. You will become active group member when he approve you.', '_Group resign error', '_You cannot resign the group because you''re creator', '_Group resign', '_You succesfully resigned from group', '_You cannot resign the group because you''re not group member', '_Group thumnail set', '_You cannot set group thumnail because you are not group creator', '_Group image delete', '_You cannot delete image because you are not group creator');

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_Group member delete error', '_You cannot delete yourself from group because you are group creator', '_You cannot delete group member because you are not group creator', '_Group member approve', '_Group member approve error', '_Some error occured', '_You cannot approve group member because you are not group creator', '_Group member reject', '_Group member reject error', '_You cannot reject group member because you are not group creator', '_Group action error', '_Unknown group action', '_Group name', '_Group invite_desc', '_Sorry, no members are found', '_Back to group', '_Groups help', '_Groups help_1', '_Groups help_2', '_Groups help_4', '_Groups help_3', '_Groups help_5', '_Groups help_6', '_Groups help_7', '_Group invite', '_Group invite accept', '_You succesfully accepted group invite', '_Group invite accept error', '_You cannot accept group invite', '_Group invite reject', '_You succesfully rejected group invite', '_Group forum');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_Group member delete error', '_You cannot delete yourself from group because you are group creator', '_You cannot delete group member because you are not group creator', '_Group member approve', '_Group member approve error', '_Some error occured', '_You cannot approve group member because you are not group creator', '_Group member reject', '_Group member reject error', '_You cannot reject group member because you are not group creator', '_Group action error', '_Unknown group action', '_Group name', '_Group invite_desc', '_Sorry, no members are found', '_Back to group', '_Groups help', '_Groups help_1', '_Groups help_2', '_Groups help_4', '_Groups help_3', '_Groups help_5', '_Groups help_6', '_Groups help_7', '_Group invite', '_Group invite accept', '_You succesfully accepted group invite', '_Group invite accept error', '_You cannot accept group invite', '_Group invite reject', '_You succesfully rejected group invite', '_Group forum');

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_Group is suspended', '_Sorry, group is suspended', '_Group status', '_Groups help_8', '_You must be active member to create groups', '_My Guestbook', '_Top Groups', '_All Groups', '_No groups available', '_nick_already_in_group', '_Group invitation', '_Group join request');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_Group is suspended', '_Sorry, group is suspended', '_Group status', '_Groups help_8', '_You must be active member to create groups', '_My Guestbook', '_Top Groups', '_All Groups', '_No groups available', '_nick_already_in_group', '_Group invitation', '_Group join request');

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.3', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.3';

