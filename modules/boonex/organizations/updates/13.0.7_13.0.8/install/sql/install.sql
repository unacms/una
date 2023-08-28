SET @sName = 'bx_organizations';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`=@sName WHERE `Name`=@sName;
