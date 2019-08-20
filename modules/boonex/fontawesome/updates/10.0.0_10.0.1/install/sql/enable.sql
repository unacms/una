-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_fontawesome_general' LIMIT 1);

SET @sValue = IF(
    (SELECT `value` FROM `sys_options` WHERE `name` = 'bx_fontawesome_option_light_icons') = 'on'
    OR
    (SELECT `value` FROM `sys_options` WHERE `name` = 'bx_fontawesome_option_icons_style') = 'light',
    'light', 'default'
);

DELETE FROM `sys_options` WHERE `name` IN ('bx_fontawesome_option_icons_style', 'bx_fontawesome_option_light_icons');

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_fontawesome_option_icons_style', @sValue, @iCategId, '_bx_fontawesome_option_icons_style', 'select', 'default,light,duotone', '', '', '', 10);

-- PRELOADER
DELETE FROM `sys_preloader` WHERE `module`='bx_fontawesome' AND `type`='css_system' AND `content`='fonts-duotone.css';
INSERT INTO `sys_preloader` (`module`, `type`, `content`, `active`) VALUES
('bx_fontawesome', 'css_system', 'fonts-duotone.css', 0);
