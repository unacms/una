SET @sName = 'bx_dolphin_migration';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = @sName LIMIT 1);

DELETE FROM `sys_options`  WHERE `name` IN('bx_dolphin_migration_overwrite', 'bx_dolphin_migration_use_nickname', 'bx_dolphin_migration_empty_albums');

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_dolphin_migration_overwrite', '', @iCategId, '_bx_dolphin_migration_cpt_overwrite', 'checkbox', '', '', '', 0),
('bx_dolphin_migration_use_nickname', '', @iCategId, '_bx_dolphin_migration_cpt_use_nickname', 'checkbox', '', '', '', 0),
('bx_dolphin_migration_empty_albums', 'on', @iCategId, '_bx_dolphin_migration_cpt_empty_albums', 'checkbox', '', '', '', 0);