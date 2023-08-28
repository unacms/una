SET @sName = 'bx_persons';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`=@sName WHERE `Name`=@sName;
