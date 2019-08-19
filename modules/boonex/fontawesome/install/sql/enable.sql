
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_fontawesome', '_bx_fontawesome_adm_stg_cpt_type', 'bx_fontawesome@modules/boonex/fontawesome/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_fontawesome_general', '_bx_fontawesome_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_fontawesome_option_icons_style', '', @iCategId, '_bx_fontawesome_option_icons_style', 'select', 'default,light,duotone', '', '', 10);


-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_fontawesome', `class` = 'BxFontAwesomeAlerts', `file` = 'modules/boonex/fontawesome/classes/BxFontAwesomeAlerts.php';

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name` = 'bx_fontawesome');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandlerId);

-- CSS Loader

UPDATE `sys_preloader` SET `active` = 0 WHERE `module` = 'system' AND `type` = 'css_system' AND `content` = 'icons.css';

INSERT INTO `sys_preloader` (`module`, `type`, `content`, `active`) VALUES
('bx_fontawesome', 'css_system', 'icons.css', 1),
('bx_fontawesome', 'css_system', 'fonts-all.css', 1),
('bx_fontawesome', 'css_system', 'fonts-duotone.css', 0),
('bx_fontawesome', 'css_system', 'fonts-light.css', 0);

