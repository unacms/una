
-- ACL

UPDATE `sys_acl_actions` SET `Title` = '_sys_acl_action_view_view_viewers_own' WHERE `Module` = 'system' AND `Name` = 'view_view_viewers_own';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.0-RC2' WHERE (`version` = '11.0.0.RC1' OR `version` = '11.0.0-RC1') AND `name` = 'system';
