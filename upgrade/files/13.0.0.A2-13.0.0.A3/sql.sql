
-- Options: Hidden

-- TODO: move install time to sys_modules table
DELETE FROM `sys_options` WHERE `name` = 'sys_install_time';

DELETE FROM `sys_options` WHERE `name` IN('sys_quill_insert_as_plain_text', 'sys_quill_toolbar_mini', 'sys_quill_toolbar_standard', 'sys_quill_toolbar_full', 'sys_csp_frame_ancestors', 'sys_notify_to_approve_by_role');
SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_quill_insert_as_plain_text', '_adm_stg_cpt_option_sys_quill_insert_as_plain_text', '', 'checkbox', '', '', '', '', 65),
(@iCategoryIdHidden, 'sys_quill_toolbar_mini', '_adm_stg_cpt_option_sys_quill_toolbar_mini', '[\'bold\',\'italic\',\'underline\',\'clean\',{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{ \'align\':\'\'},{\'align\':\'center\'},{\'align\':\'right\'},\'blockquote\',\'link\',\'image\',\'embed\']', 'digit', '', '', '', '', 70),
(@iCategoryIdHidden, 'sys_quill_toolbar_standard', '_adm_stg_cpt_option_sys_quill_toolbar_standard', '[\'bold\',\'italic\',\'underline\',\'clean\',{ \'header\': [1, 2, 3, 4, 5, 6, false] },{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'},{ \'align\':\'\'},{\'align\':\'center\'},{\'align\':\'right\'},{\'align\':\'justify\'},\'blockquote\',\'link\',\'image\',\'embed\']', 'digit', '', '', '', '', 73),
(@iCategoryIdHidden, 'sys_quill_toolbar_full', '_adm_stg_cpt_option_sys_quill_toolbar_full', '[{ \'header\': [1, 2, 3, 4, 5, 6, false] },\'bold\',\'italic\',\'underline\',\'clean\'],
  [{ \'align\': [] },{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'},\'blockquote\',{ \'color\': [] }, { \'background\': [] },{ \'direction\': \'rtl\' },\'link\',\'image\',\'embed\',\'code-block\']', 'digit', '', '', '', '', 76),
(@iCategoryIdHidden, 'sys_csp_frame_ancestors', '_adm_stg_cpt_option_sys_csp_frame_ancestors', '*', 'digit', '', '', '', '', 150),
(@iCategoryIdHidden, 'sys_notify_to_approve_by_role', '_adm_stg_cpt_option_sys_notify_to_approve_by_role', '', 'checkbox', '', '', '', '', 160);

UPDATE `sys_options` SET `name` = 'sys_curl_ssl_allow_untrusted' WHERE `name` = 'curl_ssl_allow_untrusted';

-- Options: Langs

DELETE FROM `sys_options` WHERE `name` IN('sys_format_input_date', 'sys_format_input_datetime', 'sys_format_input_24h');
SET @iCategoryIdLangs = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'languages');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdLangs, 'sys_format_input_date', '_adm_stg_cpt_option_sys_format_input_date', 'F j, Y', 'digit', '', '', '', 20),
(@iCategoryIdLangs, 'sys_format_input_datetime', '_adm_stg_cpt_option_sys_format_input_datetime', 'F j, Y H:i', 'digit', '', '', '', 22),
(@iCategoryIdLangs, 'sys_format_input_24h', '_adm_stg_cpt_option_sys_format_input_24h', 'on', 'checkbox', '', '', '', 24);


-- Options: Templates

DELETE FROM `sys_options` WHERE `name` IN('sys_pt_default_visitor', 'sys_pt_default_member');
SET @iCategoryIdTempl = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'templates');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdTempl, 'sys_pt_default_visitor', '_adm_stg_cpt_option_sys_pt_default_visitor', '3', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:14:"get_page_types";s:5:"class";s:21:"TemplTemplateServices";}', '', '', 10),
(@iCategoryIdTempl, 'sys_pt_default_member', '_adm_stg_cpt_option_sys_pt_default_member', '3', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:14:"get_page_types";s:5:"class";s:21:"TemplTemplateServices";}', '', '', 11);


-- Options: Permalinks

DELETE FROM `sys_options` WHERE `name` IN('permalinks_seo_links', 'permalinks_seo_links_redirects');
SET @iCategoryIdPermalinks = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'permalinks');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdPermalinks, 'permalinks_seo_links', '_adm_stg_cpt_option_permalinks_seo_links', '', 'checkbox', '', '', '', 10),
(@iCategoryIdPermalinks, 'permalinks_seo_links_redirects', '_adm_stg_cpt_option_permalinks_seo_links_redirects', 'on', 'checkbox', '', '', '', 12);

-- Options: Security

DELETE FROM `sys_options` WHERE `name` IN('sys_cf_enable', 'sys_cf_enable_comments', 'sys_cf_prohibited', 'sys_lock_from_unauthenticated', 'sys_lock_from_unauthenticated_exceptions');
SET @iCategoryIdSecurity = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSecurity, 'sys_cf_enable', '_adm_stg_cpt_option_sys_cf_enable', '', 'checkbox', '', '', '', 40),
(@iCategoryIdSecurity, 'sys_cf_enable_comments', '_adm_stg_cpt_option_sys_cf_enable_comments', '', 'checkbox', '', '', '', 41),
(@iCategoryIdSecurity, 'sys_cf_prohibited', '_adm_stg_cpt_option_sys_cf_prohibited', '', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_options_cf_prohibited";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', '', '', 42),

(@iCategoryIdSecurity, 'sys_lock_from_unauthenticated', '_adm_stg_cpt_option_sys_lock_from_unauthenticated', '', 'checkbox', '', '', '', 50),
(@iCategoryIdSecurity, 'sys_lock_from_unauthenticated_exceptions', '_adm_stg_cpt_option_sys_lock_from_unauthenticated_exceptions', 'login,forgot-password,create-account,confirm-email,terms,privacy,contact,about,home', 'text', '', '', '', 52);


-- Options: Site Settings

DELETE FROM `sys_options` WHERE `name` IN('sys_vote_reactions_quick_mode', 'sys_cmts_enable_auto_approve');
SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'sys_vote_reactions_quick_mode', '_adm_stg_cpt_option_sys_vote_reactions_quick_mode', 'on', 'checkbox', '', '', '', 60),
(@iCategoryIdSiteSettings, 'sys_cmts_enable_auto_approve', '_adm_stg_cpt_option_sys_cmts_enable_auto_approve', 'on', 'checkbox', '', '', '', 70);


-- Options: Account

DELETE FROM `sys_options` WHERE `name` IN('sys_account_accounts_pruning', 'sys_account_accounts_pruning_interval');
SET @iCategoryIdSiteAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteAccount, 'sys_account_accounts_pruning', '_adm_stg_cpt_option_sys_accounts_pruning', 'no', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:28:"get_options_pruning_interval";s:5:"class";s:18:"BaseServiceAccount";}', '', '', 55),
(@iCategoryIdSiteAccount, 'sys_account_accounts_pruning_interval', '_adm_stg_cpt_option_sys_accounts_pruning_interval', '0', 'digit', '', '', '', 56);


-- Extended search

CREATE TABLE IF NOT EXISTS `sys_search_extended_sorting_fields` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `object` varchar(64) NOT NULL  default '',
  `name` varchar(255) NOT NULL  default '',
  `direction` varchar(32) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `field` (`object`(64), `name`(127) , `direction`(32))
);


-- Wiki

CREATE TABLE IF NOT EXISTS `sys_wiki_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `sys_wiki_images_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);


-- Alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_settings_change_kands' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_settings_change_kands', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:27:"alert_response_change_kands";s:6:"params";a:0:{}s:5:"class";s:27:"TemplStudioSettingsServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iIdHandler);


-- Badges

ALTER TABLE `sys_badges` CHANGE `icon` `icon` TEXT NOT NULL;


-- Privacy

SET @iMaxId := (SELECT `id` FROM `sys_privacy_groups` ORDER BY `id` DESC LIMIT 1);
UPDATE `sys_privacy_groups` SET `id` = @iMaxId + 1 WHERE `id` = 99 AND `title` != '_sys_ps_group_title_custom';

UPDATE `sys_privacy_groups` SET `id` = 99 WHERE `id` = 9 AND `title` = '_sys_ps_group_title_custom';

DELETE FROM `sys_privacy_groups` WHERE `id` = '9';
INSERT INTO `sys_privacy_groups`(`id`, `title`, `check`, `active`, `visible`) VALUES
('9', '_sys_ps_group_title_memberships_selected', '@memberships_selected_by_object', 1, 1);


CREATE TABLE IF NOT EXISTS `sys_privacy_groups_custom_memberships` (
  `group_id` int(11) NOT NULL default '0',
  `membership_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`group_id`, `membership_id`)
);


-- Cron

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_profile';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_profile', '0 0 * * *', 'BxDolCronProfile', 'inc/classes/BxDolCronProfile.php', '');


-- Storage

DELETE FROM `sys_objects_storage` WHERE `object` IN('sys_wiki_files', 'sys_wiki_images_resized');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_wiki_files', 'Local', '', 360, 2592000, 3, 'sys_wiki_files', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('sys_wiki_images_resized', 'Local', '', 360, 2592000, 3, 'sys_wiki_images_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);


-- Forms

DELETE FROM `object` IN('sys_profile', 'sys_acl');
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_profile', 'system', '_sys_form_profile', '', '', 'do_submit', 'sys_profiles', 'id', '', '', '', 0, 1, 'BxTemplFormProfile', ''),
('sys_acl', 'system', '_sys_form_acl', '', '', 'do_submit', '', '', '', '', '', 0, 1, '', '');


DELETE FROM `sys_form_displays` WHERE `display_name` IN('sys_profile_cf_set', 'sys_profile_cf_manage', 'sys_privacy_group_custom_members', 'sys_privacy_group_custom_memberships', 'sys_acl_set', 'sys_privacy_group_custom_manage');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_profile_cf_set', 'system', 'sys_profile', '_sys_form_display_profile_cf_set', 0),
('sys_profile_cf_manage', 'system', 'sys_profile', '_sys_form_display_profile_cf_manage', 0),

('sys_privacy_group_custom_members', 'system', 'sys_privacy_group_custom', '_sys_form_display_ps_gc_members', 0),
('sys_privacy_group_custom_memberships', 'system', 'sys_privacy_group_custom', '_sys_form_display_ps_gc_memberships', 0),

('sys_acl_set', 'system', 'sys_acl', '_sys_form_display_acl_set', 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_profile';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_profile', 'system', 'cfw_value', '', '#!sys_content_filter', 0, 'checkbox_set', '_sys_form_profile_input_sys_cfw_value', '_sys_form_profile_input_cfw_value', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 0),
('sys_profile', 'system', 'cfu_items', '', '#!sys_content_filter', 0, 'checkbox_set', '_sys_form_profile_input_sys_cfu_items', '_sys_form_profile_input_cfu_items', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 0),
('sys_profile', 'system', 'cfu_locked', '1', '', 0, 'switcher', '_sys_form_profile_input_sys_cfu_locked', '_sys_form_profile_input_cfu_locked', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('sys_profile', 'system', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_profile', 'system', 'do_submit', '_sys_form_profile_input_do_submit', '', 0, 'submit', '_sys_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_profile', 'system', 'do_cancel', '_sys_form_profile_input_do_cancel', '', 0, 'button', '_sys_form_profile_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:5:"class";s:22:"bx-def-margin-sec-left";s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";}', '', '', '', '', '', '', '', 0, 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_comment' AND `name` = 'cmt_cf';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_comment', 'system', 'cmt_cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_review' AND `name` = 'cmt_cf';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_review', 'system', 'cmt_cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


UPDATE `sys_form_inputs` SET `attrs` = 'a:1:{s:7:"onclick";s:29:"{js_object}.showNewList(this)";}'  WHERE `object` = 'sys_favorite' AND `name` = 'new_list';


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_privacy_group_custom' AND `name` = 'memberships';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_privacy_group_custom', 'system', 'memberships', '', '', 0, 'checkbox_set', '_sys_form_ps_gc_input_caption_system_memberships', '_sys_form_ps_gc_input_caption_memberships', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_wiki' AND `name` = 'files';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_wiki', 'system', 'files', 'a:1:{i:0;s:9:"sys_html5";}', 'a:1:{s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '', '_sys_form_wiki_input_caption_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_acl';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_acl', 'system', 'profile_id', '', '', 0, 'hidden', '_sys_form_acl_input_sys_profile_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_acl', 'system', 'card', '', '', 0, 'hidden', '_sys_form_acl_input_sys_card', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_acl', 'system', 'level_id', '', '', 0, 'radio_set', '_sys_form_acl_input_sys_level_id', '_sys_form_acl_input_level_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('sys_acl', 'system', 'duration', '', '', 0, 'text', '_sys_form_acl_input_sys_duration', '_sys_form_acl_input_duration', '_sys_form_acl_input_duration_inf', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('sys_acl', 'system', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_acl', 'system', 'do_submit', '_sys_form_acl_input_do_submit', '', 0, 'submit', '_sys_form_acl_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_acl', 'system', 'do_cancel', '_sys_form_acl_input_do_cancel', '', 0, 'button', '_sys_form_acl_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:5:"class";s:22:"bx-def-margin-sec-left";s:7:"onclick";s:65:"$(this).parents(''.bx-popup-applied:visible:first'').dolPopupHide()";}', '', '', '', '', '', '', '', 0, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_profile_cf_set', 'sys_profile_cf_manage');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_profile_cf_set', 'cfw_value', 2147483647, 1, 1),
('sys_profile_cf_set', 'do_submit', 2147483647, 1, 2),

('sys_profile_cf_manage', 'cfu_items', 2147483647, 1, 1),
('sys_profile_cf_manage', 'cfu_locked', 2147483647, 1, 2),
('sys_profile_cf_manage', 'controls', 2147483647, 1, 3),
('sys_profile_cf_manage', 'do_submit', 2147483647, 1, 4),
('sys_profile_cf_manage', 'do_cancel', 2147483647, 1, 5);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_comment_post' AND `input_name` = 'cmt_cf';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_comment_post', 'cmt_cf', 2147483647, 1, 7);
UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `order` = 7 AND `display_name` = 'sys_comment_post' AND `input_name` = 'cmt_image';
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `order` = 8 AND `display_name` = 'sys_comment_post' AND `input_name` = 'cmt_submit';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_cf';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_comment_edit', 'cmt_cf', 2147483647, 1, 7);
UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `order` = 7 AND `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_image';
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `order` = 8 AND `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_controls';
UPDATE `sys_form_display_inputs` SET `order` = 10 WHERE `order` = 9 AND `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_submit';
UPDATE `sys_form_display_inputs` SET `order` = 11 WHERE `order` = 10 AND `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_cancel';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_review_post' AND `input_name` = 'cmt_cf';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_review_post', 'cmt_cf', 2147483647, 1, 9);
UPDATE `sys_form_display_inputs` SET `order` = 10 WHERE `order` = 9 AND `display_name` = 'sys_review_post' AND `input_name` = 'cmt_submit';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_review_edit' AND `input_name` = 'cmt_cf';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_review_edit', 'cmt_cf', 2147483647, 1, 9);
UPDATE `sys_form_display_inputs` SET `order` = 10 WHERE `order` = 9 AND `display_name` = 'sys_review_edit' AND `input_name` = 'cmt_submit';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_privacy_group_custom_members', 'sys_privacy_group_custom_memberships', 'sys_privacy_group_custom_manage');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_privacy_group_custom_members', 'profile_id', 2147483647, 1, 1),
('sys_privacy_group_custom_members', 'content_id', 2147483647, 1, 2),
('sys_privacy_group_custom_members', 'object', 2147483647, 1, 3),
('sys_privacy_group_custom_members', 'action', 2147483647, 1, 4),
('sys_privacy_group_custom_members', 'group_id', 2147483647, 1, 5),
('sys_privacy_group_custom_members', 'search', 2147483647, 1, 6),
('sys_privacy_group_custom_members', 'list', 2147483647, 1, 7),
('sys_privacy_group_custom_members', 'controls', 2147483647, 1, 8),
('sys_privacy_group_custom_members', 'do_submit', 2147483647, 1, 9),
('sys_privacy_group_custom_members', 'do_cancel', 2147483647, 1, 10),

('sys_privacy_group_custom_memberships', 'profile_id', 2147483647, 1, 1),
('sys_privacy_group_custom_memberships', 'content_id', 2147483647, 1, 2),
('sys_privacy_group_custom_memberships', 'object', 2147483647, 1, 3),
('sys_privacy_group_custom_memberships', 'action', 2147483647, 1, 4),
('sys_privacy_group_custom_memberships', 'group_id', 2147483647, 1, 5),
('sys_privacy_group_custom_memberships', 'memberships', 2147483647, 1, 6),
('sys_privacy_group_custom_memberships', 'controls', 2147483647, 1, 7),
('sys_privacy_group_custom_memberships', 'do_submit', 2147483647, 1, 8),
('sys_privacy_group_custom_memberships', 'do_cancel', 2147483647, 1, 9);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_wiki_edit' AND `input_name` = 'files';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_wiki_edit', 'files', 2147483647, 1, 4);
UPDATE `sys_form_display_inputs` SET `order` = 5 WHERE `order` = 4 AND `display_name` = 'sys_wiki_edit' AND `input_name` = 'notes';
UPDATE `sys_form_display_inputs` SET `order` = 6 WHERE `order` = 5 AND `display_name` = 'sys_wiki_edit' AND `input_name` = 'do_submit';
UPDATE `sys_form_display_inputs` SET `order` = 7 WHERE `order` = 6 AND `display_name` = 'sys_wiki_edit' AND `input_name` = 'close';
UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `order` = 7 AND `display_name` = 'sys_wiki_edit' AND `input_name` = 'buttons';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_wiki_translate' AND `input_name` = 'files';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_wiki_translate', 'files', 2147483647, 1, 5);
UPDATE `sys_form_display_inputs` SET `order` = 6 WHERE `order` = 5 AND `display_name` = 'sys_wiki_translate' AND `input_name` = 'notes';
UPDATE `sys_form_display_inputs` SET `order` = 7 WHERE `order` = 6 AND `display_name` = 'sys_wiki_translate' AND `input_name` = 'do_submit';
UPDATE `sys_form_display_inputs` SET `order` = 8 WHERE `order` = 7 AND `display_name` = 'sys_wiki_translate' AND `input_name` = 'close';
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `order` = 8 AND `display_name` = 'sys_wiki_translate' AND `input_name` = 'buttons';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_acl_set';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_acl_set', 'profile_id', 2147483647, 1, 1),
('sys_acl_set', 'card', 2147483647, 1, 2),
('sys_acl_set', 'level_id', 2147483647, 1, 3),
('sys_acl_set', 'duration', 2147483647, 1, 4),
('sys_acl_set', 'controls', 2147483647, 1, 5),
('sys_acl_set', 'do_submit', 2147483647, 1, 6),
('sys_acl_set', 'do_cancel', 2147483647, 1, 7);


-- Pre-lists

DELETE FROM `sys_form_pre_lists` WHERE `key` = 'sys_content_filter';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('sys_content_filter', '_sys_pre_lists_content_filter', 'system', '1', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_content_filter';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_content_filter', 1, 1, '_sys_pre_lists_content_filter_g', '', ''),
('sys_content_filter', 2, 2, '_sys_pre_lists_content_filter_pg', '', ''),
('sys_content_filter', 3, 3, '_sys_pre_lists_content_filter_pg13', '', ''),
('sys_content_filter', 4, 4, '_sys_pre_lists_content_filter_r', '', ''),
('sys_content_filter', 5, 5, '_sys_pre_lists_content_filter_x', '', '');


-- Menu

SET @iMaxIdTemplate = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_menu_templates`);

UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 1 WHERE `id` = 30 AND `template` != 'menu_panel.html';
UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 2 WHERE `id` = 31 AND `template` != 'menu_main_in_panel.html';

DELETE FROM `sys_menu_templates` WHERE `id` IN(30, 31);
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(30, 'menu_panel.html', '_sys_menu_template_title_panel', 0),
(31, 'menu_main_in_panel.html', '_sys_menu_template_title_main_in_panel', 0);


DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_site_in_panel', 'sys_application', 'sys_site_panel');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_site_in_panel', '_sys_menu_title_main_in_panel', 'sys_site', 'system', 31, 0, 1, '', ''),
('sys_application', '_sys_menu_title_application', 'sys_application', 'system', 28, 0, 1, 'BxTemplMenuSite', ''),
('sys_site_panel', '_sys_menu_title_panel', 'sys_site_panel', 'system', 30, 0, 1, 'BxTemplMenuPanel', '');


DELETE FROM `sys_menu_sets` WHERE `set_name` IN('sys_application', 'sys_site_panel');
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_application', 'system', '_sys_menu_set_title_application', 0),
('sys_site_panel', 'system', '_sys_menu_set_title_panel', 0);


-- panel menu
DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_site_panel';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_site_panel', 'system', 'member-avatar', '_sys_menu_item_title_system_member_avatar', '', '', '', '', '', '', 2147483646, 1, 0, 1),
('sys_site_panel', 'system', 'public-menu', '_sys_menu_item_title_system_public_menu', '', '', '', '', '', 'sys_site_in_panel', 2147483647, 0, 0, 2),
('sys_site_panel', 'system', 'member-menu', '_sys_menu_item_title_system_member_menu', '', '', '', '', '', 'sys_profile_stats', 2147483646, 1, 0, 3),
('sys_site_panel', 'system', 'member-followings', '_sys_menu_item_title_system_member_followings', '', '', '', '', '', 'sys_profile_followings', 2147483646, 1, 0, 4);

-- application menu
DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_application';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_application', 'system', 'home', '_sys_menu_item_title_system_home', '_sys_menu_item_title_home', 'index.php', '', '', 'home col-gray-dark', '', 2147483647, 1, 1, 1),
('sys_application', 'system', 'about', '_sys_menu_item_title_system_about', '_sys_menu_item_title_about', 'page.php?i=about', '', '', 'info-circle col-blue3-dark', '', 2147483647, 1, 1, 2),
('sys_application', 'system', 'more-auto', '_sys_menu_item_title_system_more_auto', '_sys_menu_item_title_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', 2147483647, 1, 0, 9999);


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_settings' AND `name` = 'profile-settings-cfilter';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_account_settings', 'system', 'profile-settings-cfilter', '_sys_menu_item_title_system_profile_settings_cfilter', '_sys_menu_item_title_profile_settings_cfilter', 'page.php?i=profile-settings-cfilter', '', '', 'filter', '', 2147483646, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"is_enabled_cfilter";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 1, 1, 5);


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_social_sharing' AND `name` IN('social-sharing-linked_in', 'social-sharing-whatsapp');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_social_sharing', 'system', 'social-sharing-linked_in', '_sys_menu_item_title_system_social_sharing_linked_in', '_sys_menu_item_title_social_sharing_linked_in',  'https://www.linkedin.com/shareArticle?mini=true&url={url_encoded}', '', '_blank', 'fab linkedin', '', 2147483647, 1, 1, 4),
('sys_social_sharing', 'system', 'social-sharing-whatsapp', '_sys_menu_item_title_system_social_sharing_whatsapp', '_sys_menu_item_title_social_sharing_whatsapp', 'https://wa.me/?text={url_encoded}', '', '_blank', 'fab whatsapp', '', 2147483647, 1, 1, 5);


-- dashboard content
DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_dashboard_content_manage' AND `name` = 'cmts';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_dashboard_content_manage', 'system', 'cmts', '_sys_menu_item_title_system_cmts_administration', '_sys_menu_item_title_cmts_administration', 'page.php?i=dashboard-content&module=cmts', '', '', '', '', '', 192, 1, 0, 1);


-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_search_forms_sortable_fields';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_search_forms_sortable_fields', 'Sql', 'SELECT * FROM `sys_search_extended_sorting_fields` WHERE 1 AND `object`=?', 'sys_search_extended_sorting_fields', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'type', 'caption', 'like', '', '', 'BxTemplStudioFormsSearchSortableFields', '');


DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_search_forms' AND `name` = 'sortable_fields';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_search_forms', 'sortable_fields', '_adm_form_txt_search_forms_sortable_fields', '10%', 0, '13', '', 5);

UPDATE `sys_grid_fields` SET `width` = '35%' WHERE `object` = 'sys_studio_search_forms' AND `name` = 'title';
UPDATE `sys_grid_fields` SET `width` = '10%' WHERE `object` = 'sys_studio_search_forms' AND `name` = 'fields';
UPDATE `sys_grid_fields` SET `order` = 6 WHERE `object` = 'sys_studio_search_forms' AND `name` = 'actions';


DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_search_forms_sortable_fields';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_search_forms_sortable_fields', 'order', '', '1%', 0, '', '', 1),
('sys_studio_search_forms_sortable_fields', 'switcher', '', '9%', 0, '', '', 2),
('sys_studio_search_forms_sortable_fields', 'caption', '_adm_form_txt_search_forms_sortable_fields_caption', '50%', 1, '38', '', 3),
('sys_studio_search_forms_sortable_fields', 'direction', '_adm_form_txt_search_forms_sortable_fields_direction', '40%', 0, '', '', 4);



DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_search_forms_sortable_fields';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_search_forms_sortable_fields', 'independent', 'reset', '_adm_form_btn_search_forms_sortable_fields_reset', '', 0, 1);


UPDATE `sys_objects_grid` SET `field_active` = 'status_admin' WHERE `object` = 'sys_cmts_administration';


DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_cmts_administration' AND `name` = 'switcher';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_cmts_administration', 'switcher', '_sys_cmts_administration_grid_column_title_adm_active', '8%', 0, '', '', 2);


UPDATE `sys_grid_fields` SET `order` = 3 WHERE `object` = 'sys_cmts_administration' AND `name` = 'reports';
UPDATE `sys_grid_fields` SET `order` = 4, `width` = '30%' WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_text';
UPDATE `sys_grid_fields` SET `order` = 5, `width` = '15%' WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_time';
UPDATE `sys_grid_fields` SET `order` = 6 WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_author_id';
UPDATE `sys_grid_fields` SET `order` = 7 WHERE `object` = 'sys_cmts_administration' AND `name` = 'actions';


-- GRID: queues
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_queues';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `order_get_field`, `order_get_dir`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `filter_get`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_queues', 'Array', '', '', 'id', '', '', 'order_field', 'order_dir', '', 10, NULL, 'start', '', '', '', 'auto', 'filter', '', '', 128, 0, 0, 'BxDolGridQueues', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_queues';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_queues', 'name', '_Name', '40%', 1, 0, '', '', 1),
('sys_queues', 'all', '_all', '20%', 0, 0, '', '', 2),
('sys_queues', 'failed', '_Failed', '20%', 0, 0, '', '', 3),
('sys_queues', 'actions', '', '20%', 0, 0, '', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_queues';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_queues', 'single', 'clear', '', 'eraser', 0, 1, 1);


-- Transcoders

DELETE FROM `sys_objects_transcoder` WHERE `object` = 'sys_wiki_images_preview';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_wiki_images_preview', 'sys_wiki_images_resized', 'Storage', 'a:1:{s:6:"object";s:14:"sys_wiki_files";}', 'no', '1', '2592000', '0', '', '');


DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'sys_wiki_images_preview';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_wiki_images_preview', 'Resize', 'a:4:{s:1:"w";s:2:"52";s:1:"h";s:2:"52";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- Pages

UPDATE `sys_objects_page` SET `cover` = 0 WHERE `object` = 'sys_login';

DELETE FROM `sys_objects_page` WHERE `object` IN('sys_profile_settings_cfilter', 'sys_sub_wiki_pages_list', 'sys_sub_wiki_page_contents');
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `sticky_columns`) VALUES
('sys_profile_settings_cfilter', 'profile-settings-cfilter', '_sys_page_title_system_profile_settings_cfilter', '_sys_page_title_profile_settings_cfilter', 'system', 1, 5, '', 2147483647, 1, 'page.php?i=profile-settings-cfilter', '', '', '', 0, 1, 0, '', '', 0),
('sys_sub_wiki_pages_list', 'wiki-pages-list', '', '_sys_page_title_wiki_pages_list', 'system', 1, 5, '', 2147483647, 1, '', '', '', '', 0, 1, 0, '', '', 0),
('sys_sub_wiki_page_contents', 'wiki-page-contents', '', '_sys_page_title_wiki_page_contents', 'system', 1, 5, '', 2147483647, 1, '', '', '', '', 0, 1, 0, '', '', 0);


SET @iMaxIdPageType = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_pages_types`);

UPDATE `sys_pages_types` SET `id` = @iMaxIdPageType + 1 WHERE `id` = 3 AND `title` != '_sys_page_type_standard';
UPDATE `sys_pages_types` SET `id` = @iMaxIdPageType + 2 WHERE `id` = 4 AND `title` != '_sys_page_type_application';

DELETE FROM `sys_pages_types` WHERE `id` IN(3,4);
INSERT INTO `sys_pages_types` (`id`, `title`, `template`, `order`) VALUES
(3, '_sys_page_type_standard', 'pt_standard.html', 3),
(4, '_sys_page_type_application', 'pt_application.html', 4);


UPDATE `sys_pages_types` SET `template` = '' WHERE `id` = 1;
UPDATE `sys_pages_types` SET `template` = 'pt_wo_hf.html', `title` = '_sys_page_type_wo_hf' WHERE `id` = 2;


SET @iMaxIdPageLayout = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_pages_layouts`);

UPDATE `sys_pages_layouts` SET `id` = @iMaxIdPageLayout + 1 WHERE `id` = 20 AND `name` != '1_column_wiki';

DELETE FROM `sys_pages_layouts` WHERE `id` = 20;
INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES
(20, '1_column_wiki', 'layout_1_column_wiki.png', '_sys_layout_1_column_wiki', 'layout_1_column_wiki.html', 1);


SET @iMaxIdBlock = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0);
DELETE FROM `sys_pages_blocks` WHERE `object` = '' AND `title` = '_sys_page_block_title_author';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'system', '_sys_page_block_title_sys_author', '_sys_page_block_title_author', 3, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_block_author";s:6:"params";a:2:{i:0;s:8:"{module}";i:1;s:4:"{id}";}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, @iMaxIdBlock + 1);


UPDATE `sys_pages_blocks` SET `designbox_id` = 13, `copyable` = 1 WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_profile_avatar';
UPDATE `sys_pages_blocks` SET `designbox_id` = 13, `copyable` = 1 WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_profile_menu';
UPDATE `sys_pages_blocks` SET `designbox_id` = 13, `copyable` = 1 WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_profile_followings';


DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_profile_settings_cfilter' AND `title` = '_sys_page_block_title_profile_settings_cfilter';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_profile_settings_cfilter', 1, 'system', '', '_sys_page_block_title_profile_settings_cfilter', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:24:"profile_settings_cfilter";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1, 2);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_sub_wiki_pages_list' AND `title` = '_sys_page_block_title_wiki_pages_list';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_sub_wiki_page_contents' AND `title` = '_sys_page_block_title_wiki_page_contents';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_sub_wiki_pages_list', 1, 'system', '', '_sys_page_block_title_wiki_pages_list', 0, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:10:"pages_list";s:6:"params";a:0:{}s:5:"class";s:16:"TemplServiceWiki";}', 0, 1, 1, 1),
('sys_sub_wiki_page_contents', 1, 'system', '', '_sys_page_block_title_wiki_page_contents', 0, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"page_contents";s:6:"params";a:0:{}s:5:"class";s:16:"TemplServiceWiki";}', 0, 1, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_std_dashboard' AND `title` = '_sys_page_block_title_std_dash_queues';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_std_dashboard', 2, 'system', '', '_sys_page_block_title_std_dash_queues', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_block_queues";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', 0, 0, 1, 3);


-- SEO links

CREATE TABLE IF NOT EXISTS `sys_seo_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `page_uri` varchar(255) NOT NULL,
  `param_name` varchar(32) NOT NULL,
  `param_value` varchar(32) NOT NULL,
  `uri` varchar(50) NOT NULL,
  `added` int(48) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_page_param` (`module`,`page_uri`(109),`param_value`),
  UNIQUE KEY `module_page_uri` (`module`,`page_uri`(109),`uri`),
  KEY `param_name_value` (`param_name`,`param_value`)
);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-A3' WHERE (`version` = '13.0.0.A2' OR `version` = '13.0.0-A2') AND `name` = 'system';

