-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_antispam_profanity_filter';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_antispam_profanity_filter', '_bx_antispam_adm_stg_cpt_category_profanity_filter', 7);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE 'bx_antispam_profanity_filter_%';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_profanity_filter_enable', '_bx_antispam_option_profanity_filter_enable', '', 'checkbox', '', '', '', '', 10),
(@iCategoryId, 'bx_antispam_profanity_filter_dicts', '_bx_antispam_option_profanity_filter_dicts', '', 'list', 'a:2:{s:6:"module";s:11:"bx_antispam";s:6:"method";s:26:"get_profanity_filter_dicts";}', '', '', '', 15),
(@iCategoryId, 'bx_antispam_profanity_filter_bad_words_list', '_bx_antispam_option_profanity_filter_bad_words_list', '', 'text', '', '', '', '', 20),
(@iCategoryId, 'bx_antispam_profanity_filter_char_replace', '_bx_antispam_option_profanity_filter_char_replace', '*', 'digit', '', '', '', '', 30),
(@iCategoryId, 'bx_antispam_profanity_filter_white_words_list', '_bx_antispam_option_profanity_filter_white_words_list', '', 'text', '', '', '', '', 40),
(@iCategoryId, 'bx_antispam_profanity_filter_full_words_only', '_bx_antispam_option_profanity_filter_full_words_only', 'on', 'checkbox', '', '', '', '', 50);
