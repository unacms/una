
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Caption` = '_[db_prefix]_action_share' AND `Icon` = 'action_share.png';
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Caption` = '_[db_prefix]_action_report' AND `Icon` = 'action_report.png';
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Caption` = '{evalResult}' AND `Icon` = 'action_fave.png';
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Caption` = '{evalResult}' AND `Icon` = 'edit.png';
DELETE FROM `sys_objects_actions` WHERE `Type` = '[db_prefix]' AND `Caption` = '{evalResult}' AND `Icon` = 'action_block.png';

INSERT INTO `sys_objects_actions` (`Type`, `Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`) VALUES
('[db_prefix]', '_[db_prefix]_action_share', 'action_share.png', '', 'window.open(''{moduleUrl}share/{fileUri}'', ''_blank'', ''width=500,height=380,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'')', '', 1),
('[db_prefix]', '{evalResult}', 'action_report.png', '', 'window.open(''{moduleUrl}report/{fileUri}'', ''_blank'', ''width=500,height=380,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'')', 'if ({iViewer}!=0)\r\nreturn _t(''_[db_prefix]_action_report'');\r\nelse\r\nreturn null;', 2),
('[db_prefix]', '{evalResult}', 'action_fave.png', '', 'getHtmlData(''ajaxy_popup_result_div_{ID}'', ''{moduleUrl}favorite/{ID}''); return false;', 'if ({iViewer}==0)\r\nreturn false;\r\n$sMessage = ''{favorited}''=='''' ? ''fave'':''unfave'';\r\nreturn _t(''_[db_prefix]_action_'' . $sMessage); ', 3),
('[db_prefix]', '{evalResult}', 'edit.png', '', 'window.open(''{moduleUrl}edit/{ID}'', ''_blank'', ''width=500,height=380,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no'') ', '$sTitle = _t(''_Edit'');\r\nif ({Owner} == {iViewer})\r\nreturn $sTitle;\r\n$mixedCheck = BxDolService::call(''sounds'', ''check_action'', array(''edit'',''{ID}''));\r\nif ($mixedCheck !== false)\r\nreturn $sTitle;\r\nelse\r\n return null;', 5),
('[db_prefix]', '{evalResult}', 'action_block.png', '', 'getHtmlData(''ajaxy_popup_result_div_{ID}'', ''{moduleUrl}delete/{ID}/{AlbumUri}/{OwnerName}'');return false;', '$sTitle = _t(''_Delete'');\r\nif ({Owner} == {iViewer})\r\nreturn $sTitle;\r\n$mixedCheck = BxDolService::call(''sounds'', ''check_delete'', array({ID}));\r\nif ($mixedCheck !== false)\r\nreturn $sTitle;\r\nelse\r\nreturn null;', 6);

UPDATE `sys_modules` SET `version` = '1.0.6' WHERE `uri` = 'sounds' AND `version` = '1.0.5';

