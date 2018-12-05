SET @sName = 'bx_lucid';


UPDATE `sys_modules` SET `help_url`='http://feed.una.io/?section={module_name}' WHERE `name`=@sName;
