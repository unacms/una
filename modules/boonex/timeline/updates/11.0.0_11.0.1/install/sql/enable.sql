SET @sName = 'bx_timeline';


-- SETTINGS
UPDATE `sys_options` SET `value`='' WHERE `name`='bx_timeline_enable_infinite_scroll';
