

DROP TABLE `[db_prefix]messages`;

CREATE TABLE `[db_prefix]messages` (
      `ID` int(10) unsigned NOT NULL auto_increment,
      `OwnerID` int(11) NOT NULL,
      `Message` text NOT NULL,
      `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `IP` int(10) unsigned NOT NULL,
      PRIMARY KEY (`ID`),
      KEY `IP` (`IP`)
) ENGINE=MyISAM;


UPDATE `sys_acl_actions` SET `Name` = 'shoutbox use' WHERE `Name` = 'use shoutbox';
INSERT INTO `sys_acl_actions` VALUES (NULL, 'shoutbox delete messages', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'shoutbox block by ip', NULL);


UPDATE `sys_options` SET `VALUE` = '7000' WHERE `Name` = 'shoutbox_update_time' AND `VALUE` = '5000';
UPDATE `sys_options` SET `VALUE` = '30' WHERE `Name` = 'shoutbox_allowed_messages' AND `VALUE` = '15';
UPDATE `sys_options` SET `Name` = 'shoutbox_process_smiles' WHERE `Name` = 'shoutbox_procces_smiles';
UPDATE `sys_options` SET `VALUE` = '172800' WHERE `Name` = 'shoutbox_clean_oldest' AND `VALUE` = '1300';

SET @iKategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Shoutbox' LIMIT 1);

INSERT IGNORE INTO 
    `sys_options` 
SET
    `Name` = 'shoutbox_block_sec',
    `kateg` = @iKategId,
    `desc`  = 'IP blocking time (sec)',
    `Type`  = 'digit',
    `VALUE` = '86400',
    `check` = 'return is_numeric($arg0);',
    `order_in_kateg` = 5;


UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'shoutbox' AND `version` = '1.0.3';

