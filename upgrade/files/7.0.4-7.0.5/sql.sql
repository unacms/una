
-- alter tables

ALTER TABLE `sys_sessions` ADD INDEX `date` (`date`);

-- regular updates/changes

DELETE FROM `sys_admin_dashboard` WHERE `name` = 'sys_licenses_90days' OR `name` = 'sys_licenses_400days';

UPDATE `sys_email_templates` SET `Desc` = 'Admin notification - new user joined' WHERE `Name` = 't_UserJoined' AND `Desc` = 'Profile activation message template.';
UPDATE `sys_email_templates` SET `Desc` = 'Admin notification - user confirmed email' WHERE `Name` = 't_UserConfirmed' AND `Desc` = 'Profile activation message template.';

UPDATE `sys_options` SET `desc` = 'Enable TinyMCE in comments' WHERE `Name` = 'enable_tiny_in_comments' AND `desc` = 'Enable TinyMCS in comments';
UPDATE `sys_options` SET `Type` = 'checkbox' WHERE `Name` = 'news_enable';
UPDATE `sys_options` SET `VALUE` = 'on' WHERE `Name` = 'news_enable' AND `VALUE` = '1';
UPDATE `sys_options` SET `VALUE` = '' WHERE `Name` = 'news_enable' AND `VALUE` = '0';
INSERT IGNORE INTO `sys_options` VALUES('feeds_enable', 'on', 3, 'Show boonex feeds in admin panel', 'checkbox', '', '', 7, '');

DELETE FROM `sys_pre_values` WHERE `Key` = 'Country' AND `Value` = 'JP' AND `LKey` = '__Jersey';

DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_fave}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_befriend}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_greet}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_get_mail}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_report}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_block}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_remove_friend}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'Profile' AND `Caption` = '{cpt_unblock}';
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`, `bDisplayInSubMenuHeader`) VALUES
('{cpt_fave}', 'action_fave.png', '', '{evalResult}', 'if ({ID} == {member_id}) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=hot'', ''action_hot_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=hot'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;\r\n', 3, 'Profile', 0),
('{cpt_befriend}', 'action_friends.png', '', '{evalResult}', 'if ({ID} == {member_id} OR is_friends({ID} , {member_id})) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=friend'', ''action_friend_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=friend'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\n    \r\nreturn $sOnclick;', 4, 'Profile', 0),
('{cpt_greet}', 'action_greet.png', '', '{evalResult}', 'if ({ID} == {member_id}) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''greet.php'', ''action_greet_profile'', new Array(''sendto''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''greet.php'', { sendto: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;\r\n', 5, 'Profile', 0),
('{cpt_get_mail}', 'action_email.png', '', '{evalResult}', 'if ({ID} == {member_id}) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$bAnonymousMode  = ''{anonym_mode}'';\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\nif ( !$bAnonymousMode ) {\r\n\r\n    $sOnclick = $bDisplayType\r\n    	? "openWindowWithParams(''freemail.php'', ''action_get_mail_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n    	: "$.post(''freemail.php'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\n	return $sOnclick;\r\n}\r\nelse \r\n	return null;', 6, 'Profile', 0),
('{cpt_report}', 'action_report.png', '', '{evalResult}', 'if ({ID} == {member_id}) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=spam'', ''action_spam_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=spam'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;\r\n', 8, 'Profile', 0),
('{cpt_block}', 'action_block.png', '', '{evalResult}', 'if ( {ID} == {member_id} || isBlocked({member_id}, {ID}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=block'', ''action_block_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=block'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;', 9, 'Profile', 0),
('{cpt_remove_friend}', 'action_friends.png', '', '{evalResult}', 'if ({ID} == {member_id} OR !is_friends({ID} , {member_id}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=remove_friend'', ''action_remove_friend'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=remove_friend'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;', 4, 'Profile', 0),
('{cpt_unblock}', 'action_block.png', '', '{evalResult}', 'if ({ID} == {member_id} || !isBlocked({member_id}, {ID}) ) return;\r\n\r\n$bDisplayType  = {display_type};\r\n$iWindowWidth  = {window_width};\r\n$iWindowHeight = {window_height};\r\n\r\n$sOnclick = $bDisplayType\r\n	? "openWindowWithParams(''list_pop.php?action=unblock'', ''action_unblock_profile'', new Array(''ID''), new Array(''{ID}''), ''width={$iWindowWidth},height={$iWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'', ''post'');" \r\n	: "$.post(''list_pop.php?action=unblock'', { ID: ''{ID}'' }, function(sData){ $(''#ajaxy_popup_result_div_{ID}'').html(sData) } );return false;";\r\n\r\nreturn $sOnclick;', 9, 'Profile', 0);

-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.5', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.5';

