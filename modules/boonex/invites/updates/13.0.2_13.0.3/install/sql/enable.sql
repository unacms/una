SET @sName = 'bx_invites';


-- ACL
SET @iAdministrator = 8;
SET @iIdActionInvite = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='invite' LIMIT 1);
UPDATE `sys_acl_matrix` SET `AllowedCount`=NULL WHERE `IDLevel`=@iAdministrator AND `IDAction`=@iIdActionInvite;
