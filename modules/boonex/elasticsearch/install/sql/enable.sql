
-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_mlc', '_bx_mlc', 'bx_mlc@modules/boonex/mlc/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_mlc', '_bx_mlc', 1);
SET @iCategId = LAST_INSERT_ID();

--INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
--('bx_mlc_autoapproval', 'on', @iCategId, '_bx_mlc_option_autoapproval', 'checkbox', '', '', '', 1);


-- GRID
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_mlc_keys', 'Sql', 'SELECT `tlk`.`id` AS `id`, `tlk`.`id` AS `key`, `tls`.`string` AS `string` FROM `bx_mlc_keys` AS `tlk` LEFT JOIN `bx_mlc_strings` AS `tls` ON `tlk`.`id`=`tls`.`key_id` WHERE `tls`.`orig`=\'1\' ', 'bx_mlc_keys', 'id', '', '', 20, NULL, 'start', '', 'string', '', 'like', 'string', 128, 'BxMlcGridKeys', 'modules/boonex/mlc/classes/BxMlcGridKeys.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_mlc_keys', 'checkbox', '', '2%', 0, '', '', 1),
('bx_mlc_keys', 'key', '_bx_mlc_grid_column_title_key', '25%', 0, '16', '', 2),
('bx_mlc_keys', 'string', '_bx_mlc_grid_column_title_string', '38%', 0, '32', '', 3),
('bx_mlc_keys', 'languages', '_bx_mlc_grid_column_title_languages', '15%', 0, '', '', 4),
('bx_mlc_keys', 'actions', '', '20%', 0, '', '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_mlc_keys', 'bulk', 'delete', '_bx_mlc_grid_action_title_delete', '', 0, 1, 1),
('bx_mlc_keys', 'single', 'edit', '_bx_mlc_grid_action_alt_edit', 'pencil', 1, 0, 1),
('bx_mlc_keys', 'single', 'delete', '_bx_mlc_grid_action_alt_delete', 'remove', 1, 1, 2);


-- FORM
UPDATE `sys_form_inputs` SET `type`='text_mlc' WHERE `type`='text' AND `module` NOT IN ('bx_developer', 'bx_payment') AND `object` NOT IN ('sys_login', 'sys_account', 'sys_forgot_password') AND `name` NOT IN ('email', 'keyword', 'price_single', 'trial_recurring') AND `checker_func` NOT IN ('EmailUniq', 'EmailExist');
UPDATE `sys_form_inputs` SET `type`='textarea_mlc' WHERE `type`='textarea' AND `module` NOT IN ('bx_developer', 'bx_payment');


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_mlc', 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:6:"bx_mlc";s:6:"method";s:14:"include_css_js";}', 0, 1);


-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_mlc_translator', '* * * * *', 'BxMlcCronTranslator', 'modules/boonex/mlc/classes/BxMlcCronTranslator.php', '');
