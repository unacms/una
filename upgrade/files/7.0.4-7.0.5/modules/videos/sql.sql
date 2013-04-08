
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Icon` = 'action_fave.png';
INSERT INTO `sys_objects_actions` (`Type`, `Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`) VALUES
('[db_prefix]', '{evalResult}', 'action_fave.png', '', 'getHtmlData(''ajaxy_popup_result_div_{ID}'', ''{moduleUrl}favorite/{ID}''); return false;', '$sMessage = ''{favorited}''=='''' ? ''fave'':''unfave'';\r\nreturn _t(''_[db_prefix]_action_'' . $sMessage); ', 3);

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN('_bx_videos_fav_failed');
DELETE FROM `sys_localization_keys` WHERE `Key` IN('_bx_videos_fav_failed');

UPDATE `sys_modules` SET `version` = '1.0.5' WHERE `uri` = 'videos' AND `version` = '1.0.4';

