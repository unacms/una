
ALTER TABLE `sys_menu_member` ADD `Movable` tinyint(4) NOT NULL default '3' AFTER `Active`;
ALTER TABLE `sys_menu_member` ADD `Clonable` tinyint(1) NOT NULL default '1' AFTER `Movable`;

UPDATE `sys_menu_member` SET `Deletable` = 0, `Clonable` =  0 WHERE `ID` = 8;
UPDATE `sys_menu_member` SET `Eval` = '$iProfileID = getLoggedId();\r\n\r\nreturn getProfileLink($iProfileID);' WHERE `ID` = 1;



ALTER TABLE `sys_menu_top` ADD `Movable` tinyint(4) NOT NULL default '3' AFTER `Check`;
ALTER TABLE `sys_menu_top` ADD `Clonable` tinyint(1) NOT NULL default '1' AFTER `Movable`;

UPDATE `sys_menu_top` SET `Movable` = 1, `Clonable` = 0 WHERE `ID` = 4;
UPDATE `sys_menu_top` SET `Movable` = 1, `Clonable` = 0, `Deletable` = 0 WHERE `ID` = 118;
UPDATE `sys_menu_top` SET `Caption` = '_Account Home' WHERE `ID` = 101 AND `Caption` = '_Home';



UPDATE `sys_menu_admin_top` SET `url` = 'http://www.boonex.com/unity/blog/posts/Andrew Boon' WHERE `name` = 'boonex_news';



UPDATE `sys_options` SET `VALUE` = '365' WHERE `Name` = 'db_clean_msg' AND `VALUE` = '180';
UPDATE `sys_options` SET `VALUE` = '9999' WHERE `Name` = 'db_clean_profiles' AND `VALUE` = '180';

UPDATE `sys_options` SET `kateg` = 0, `VALUE` = '' WHERE `Name` = 'sys_template_cache_image_enable';
UPDATE `sys_options` SET `kateg` = 0, `VALUE` = '5' WHERE `Name` = 'sys_template_cache_image_max_size';



INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('block',   'add', 5),
('friend',  'request', 5);



CREATE TABLE IF NOT EXISTS `RayChatMembershipsSettings` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(20) NOT NULL default '' UNIQUE,
  `Caption` varchar(255) NOT NULL default '',
  `Type` enum('boolean','number','custom') NOT NULL default 'boolean',
  `Default` varchar(255) NOT NULL default '',
  `Range` int(3) NOT NULL default '3',
  `Error` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
);
TRUNCATE TABLE `RayChatMembershipsSettings`;

INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('RoomCreate', 'New Rooms Creating:', 'boolean', 'true', '1', 'RayzRoomCreate');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('PrivateRoomCreate', 'Private Rooms Creating:', 'boolean', 'true', '1', 'RayzPrivateRoomCreate');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('AVCasting', 'Audio/Video Casting:', 'boolean', 'true', '1', 'RayzAVCasting');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('AVPlaying', 'Audio/Video Playing (for Messenger):', 'boolean', 'true', '1', 'RayzAVPlaying');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('AVLargeWindow', 'Enable Large Video Window:', 'boolean', 'true', '1', 'RayzAVLargeWindow');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('FileSend', 'Files Sending:', 'boolean', 'true', '1', 'RayzFileSend');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('WhisperMessages', 'Whispering Messages:', 'boolean', 'true', '1', 'RayzWhisperMessages');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('DirectMessages', 'Addressed Messages:', 'boolean', 'true', '1', 'RayzDirectMessages');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('RoomsNumber', 'Maximum Rooms Number:', 'number', '100', '3', 'RayzRoomsNumber');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('ChatsNumber', 'Maximum Private Chats Number:', 'number', '100', '3', 'RayzChatsNumber');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('AVWindowsNumber', 'Maximum Video Windows Number:', 'number', '100', '3', 'RayzAVWindowsNumber');
INSERT INTO `RayChatMembershipsSettings`(`Name`, `Caption`, `Type`, `Default`, `Range`, `Error`) VALUES('RestrictedRooms', 'Restricted Rooms:', 'custom', '', '1', 'RayzRestrictedRooms');

CREATE TABLE IF NOT EXISTS `RayChatMemberships` (
  `ID` int(11) NOT NULL auto_increment,
  `Setting` int(11) NOT NULL default '0',
  `Value` varchar(255) NOT NULL default '',
  `Membership` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);
TRUNCATE TABLE `RayChatMemberships`;



-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.2', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.2';

