SET @sName = 'bx_media';

-- INJECTION
DELETE FROM `sys_injections` WHERE `name`= @sName;