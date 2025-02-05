-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_groups' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_groups_allow_in_contexts';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_groups_allow_in_contexts', 'bx_events,bx_spaces', @iCategId, '_bx_groups_option_allow_in_contexts', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:29:"get_modules_by_subtype_simple";s:6:"params";a:1:{i:0;s:7:"context";}s:5:"class";s:13:"TemplServices";}', '', '', 60);
