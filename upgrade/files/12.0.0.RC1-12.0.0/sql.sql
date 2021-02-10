
UPDATE `sys_options` SET `value` = 'stable' WHERE `name` = 'sys_upgrade_channel';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.0.0' WHERE (`version` = '12.0.0.RC1' OR `version` = '12.0.0-RC1') AND `name` = 'system';

