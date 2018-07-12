SET @sName = 'bx_notifications';


-- SETTINGS
UPDATE `sys_options` SET `value`='5' WHERE `name`='bx_notifications_events_per_preview';