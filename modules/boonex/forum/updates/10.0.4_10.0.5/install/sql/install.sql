SET @sName = 'bx_forum';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `IsBrowseSwitch`='0', `NumberOfLevels`='1', `IsDisplaySwitch`='1' WHERE `Name`=@sName;
