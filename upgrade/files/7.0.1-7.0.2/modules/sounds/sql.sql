
UPDATE `sys_objects_actions` SET `Eval` = '$sTitle = _t(''_Edit'');\r\nif ({Owner} == {iViewer})\r\nreturn $sTitle;\r\n$mixedCheck = BxDolService::call(''sounds'', ''check_action'', array(''edit'',''{ID}''));\r\nif ($mixedCheck !== false)\r\nreturn $sTitle;\r\nelse\r\n return null;' WHERE `Type` = '[db_prefix]' AND `Icon` = 'edit.png';
UPDATE `sys_objects_actions` SET `Eval` = '$sTitle = _t(''_Delete'');\r\nif ({Owner} == {iViewer})\r\nreturn $sTitle;\r\n$mixedCheck = BxDolService::call(''sounds'', ''check_delete'', array({ID}));\r\nif ($mixedCheck !== false)\r\nreturn _t(''_Delete'');\r\nelse\r\nreturn null;' WHERE `Type` = '[db_prefix]' AND `Icon` = 'action_block.png';

UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'sounds' AND `version` = '1.0.1';

