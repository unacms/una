SET @sName = 'bx_timeline';


-- SETTINGS
UPDATE `sys_options` SET `value`='5' WHERE `name`='bx_timeline_events_per_preload';
UPDATE `sys_options` SET `value`='' WHERE `name`='bx_timeline_enable_jump_to_switcher';
