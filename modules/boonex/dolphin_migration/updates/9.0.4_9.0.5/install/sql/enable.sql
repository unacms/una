SET @sName = 'bx_dolphin_migration';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = @sName LIMIT 1);

UPDATE `sys_options` SET `order` = 1 WHERE `category_id`=@iCategId AND `name`='bx_dolphin_migration_salt';
UPDATE `sys_options` SET `order` = 2 WHERE `category_id`=@iCategId AND `name`='bx_dolphin_migration_overwrite';
UPDATE `sys_options` SET `order` = 3 WHERE `category_id`=@iCategId AND `name`='bx_dolphin_migration_use_nickname';
UPDATE `sys_options` SET `order` = 4 WHERE `category_id`=@iCategId AND `name`='bx_dolphin_migration_empty_albums';

DELETE FROM `sys_options` WHERE `name` = 'bx_dolphin_migration_default_privacy';

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_dolphin_migration_default_privacy', 3, @iCategId, '_bx_dolphin_migration_cpt_default_privacy', 'select', '', '', 'a:2:{s:6:"module";s:20:"bx_dolphin_migration";s:6:"method";s:18:"get_privacy_groups";}', 5);