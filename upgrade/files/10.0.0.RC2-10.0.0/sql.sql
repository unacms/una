
UPDATE `sys_profiles` SET `status` = 'active' WHERE `type` IN ('bx_groups', 'bx_events', 'bx_channels') AND `status` = 'pending';

UPDATE `sys_options` SET `value` = 'stable' WHERE `name` = 'sys_upgrade_channel';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.0.0' WHERE (`version` = '10.0.0.RC2' OR `version` = '10.0.0-RC2') AND `name` = 'system';

