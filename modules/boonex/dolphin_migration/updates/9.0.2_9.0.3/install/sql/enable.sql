SET @sName = 'bx_dolphin_migration';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `Caption` = '_bx_dolphin_migration_wgt_cpt' LIMIT 1);

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_dolphin_migration_overwrite', '', @iCategId, '_bx_dolphin_migration_cpt_overwrite', 'checkbox', '', '', '', 0),
('bx_dolphin_migration_use_nickname', '', @iCategId, '_bx_dolphin_migration_cpt_use_nickname', 'checkbox', '', '', '', 0),
('bx_dolphin_migration_empty_albums', 'on', @iCategId, '_bx_dolphin_migration_cpt_empty_albums', 'checkbox', '', '', '', 0);
