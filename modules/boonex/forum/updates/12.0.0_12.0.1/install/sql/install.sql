SET @sName = 'bx_forum';

-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `ext_mode`='allow-deny', `ext_allow`='jpg,jpeg,jpe,gif,png', `ext_deny`='' WHERE `object`='bx_forum_covers';
