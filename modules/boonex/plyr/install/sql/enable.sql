
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_plyr', '_bx_plyr_adm_stg_cpt_type', 'bx_plyr@modules/boonex/plyr/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_plyr_general', '_bx_plyr_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_plyr_option_todo', '123', @iCategId, '_bx_plyr_option_todo', 'digit', '', '', '', 10);

-- Editor

INSERT INTO `sys_objects_player` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('bx_plyr', 'Plyr', '', 'BxPlyrPlayer', 'modules/boonex/plyr/classes/BxPlyrPlayer.php');

UPDATE `sys_options` SET `value` = 'bx_plyr' WHERE `name` = 'sys_player_default';

