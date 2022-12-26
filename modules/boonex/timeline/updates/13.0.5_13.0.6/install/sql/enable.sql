SET @sName = 'bx_timeline';


-- PRELOADER
DELETE FROM `sys_preloader` WHERE `module`=@sName;
