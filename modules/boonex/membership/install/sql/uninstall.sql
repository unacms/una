SET @sModuleName = 'Membership';

SET @iCategoryId = (SELECT `ID` FROM `sys_options_cats` WHERE `name`=@sModuleName LIMIT 1);
DELETE FROM `sys_options_cats` WHERE `name`=@sModuleName LIMIT 1;
DELETE FROM `sys_options` WHERE `kateg`=@iCategoryId OR `Name`='permalinks_module_membership';

DELETE FROM `sys_permalinks` WHERE `check`='permalinks_module_membership';

DELETE FROM `sys_menu_top` WHERE `Name`='My Membership';
DELETE FROM `sys_menu_member` WHERE `Name`='bx_membership' LIMIT 1;

DELETE FROM `sys_page_compose_pages` WHERE `Name`='bx_mbp_my_membership';
DELETE FROM `sys_page_compose` WHERE `Page`='bx_mbp_my_membership';

DELETE FROM `bx_pmt_modules` WHERE `uri`='membership' LIMIT 1;

SET @keyID = (SELECT `ID` FROM `sys_localization_keys` WHERE `Key`='_MEMBERSHIP_UPGRADE_FROM_STANDARD');
UPDATE `sys_localization_strings` SET `String`='' WHERE `IDKey`=@keyID;