
-- Editors

DELETE FROM `sys_objects_editor` WHERE `object` IN('sys_quill', 'sys_tinymce');
INSERT INTO `sys_objects_editor` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('sys_quill', 'Quill', 'snow', 'BxTemplEditorQuill', '');

-- Embeds

DELETE FROM `sys_objects_embeds` WHERE `object` = 'sys_oembed';
INSERT INTO `sys_objects_embeds` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_oembed', 'Oembed', 'BxTemplEmbedOembed', '');

-- Captcha

DELETE FROM `sys_objects_captcha` WHERE `object` IN('sys_hcaptcha', 'sys_recaptcha');
INSERT INTO `sys_objects_captcha` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_hcaptcha', 'hCaptcha', 'BxTemplCaptchaHCaptcha', '');

UPDATE `sys_objects_captcha` SET `title` = 'reCAPTCHA' WHERE `object` = 'sys_recaptcha_new';

-- Email templates

DELETE FROM `sys_email_templates` WHERE `Name` = 't_ManageApprove';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_manage_approve', 't_ManageApprove', '_sys_et_txt_subject_manage_approve', '_sys_et_txt_body_manage_approve');

-- Options

UPDATE `sys_options` SET `value` = 'sys_quill' WHERE `name` = 'sys_editor_default' AND `value` = 'sys_tinymce';
UPDATE `sys_options` SET `type` = 'select', `extra` = 'sys_recaptcha_new,sys_recaptcha_invisible,sys_hcaptcha' WHERE `name` = 'sys_editor_default';

UPDATE `sys_options` SET `value` = 'sys_recaptcha_new' WHERE `name` = 'sys_captcha_default' AND `value` = 'sys_recaptcha';
UPDATE `sys_options` SET `type` = 'select', `extra` = 'sys_recaptcha_new,sys_recaptcha_invisible,sys_hcaptcha' WHERE `name` = 'sys_captcha_default';

DELETE FROM `sys_options` WHERE `name` IN('sys_tinymce_plugins_mini', 'sys_tinymce_toolbar_mini', 'sys_tinymce_plugins_standard', 'sys_tinymce_toolbar_standard', 'sys_tinymce_plugins_full', 'sys_tinymce_toolbar_full');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

DELETE FROM `sys_options` WHERE `name` IN('sys_quill_toolbar_mini', 'sys_quill_toolbar_standard', 'sys_quill_toolbar_full');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_quill_toolbar_mini', '_adm_stg_cpt_option_sys_quill_toolbar_mini', '[\'bold\',\'italic\',\'underline\',\'clean\'],[{\'list\':\'ordered\'}, {\'list\':\'bullet\'}],[{ \'align\':\'\'},{\'align\':\'center\'},{\'align\':\'right\'}],[\'blockquote\'],[\'link\',\'image\']', 'digit', '', '', '', '', 70),
(@iCategoryIdHidden, 'sys_quill_toolbar_standard', '_adm_stg_cpt_option_sys_quill_toolbar_standard', '[\'bold\',\'italic\',\'underline\',\'clean\'],[{ \'header\': [1, 2, 3, 4, 5, 6, false] }],[{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'}],[{ \'align\':\'\'},{\'align\':\'center\'},{\'align\':\'right\'},{\'align\':\'justify\'}],[\'blockquote\'],[\'link\',\'image\']', 'digit', '', '', '', '', 73),
(@iCategoryIdHidden, 'sys_quill_toolbar_full', '_adm_stg_cpt_option_sys_quill_toolbar_full', '[{ \'header\': [1, 2, 3, 4, 5, 6, false] }],[\'bold\',\'italic\',\'underline\',\'clean\'],
  [{ \'align\': [] }],[{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'}],[\'blockquote\'],[{ \'color\': [] }, { \'background\': [] }],[{ \'direction\': \'rtl\' }],[{ \'script\': \'sub\'}, { \'script\': \'super\' }],[\'link\',\'image\',\'code-block\']', 'digit', '', '', '', '', 76);

DELETE FROM `sys_options` WHERE `name` IN('curl_ssl_allow_untrusted');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'curl_ssl_allow_untrusted', '_adm_stg_cpt_option_sys_ssl_allow_untrusted', '', 'checkbox', '', '', '', '', 145);


SET @iCategoryIdSystem = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');

DELETE FROM `sys_options` WHERE `name` IN('sys_site_cover_disabled');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSystem, 'sys_site_cover_disabled', '', '', 'checkbox', '', '', '', 29);

UPDATE `sys_options` SET `value` = 'sys_oembed' WHERE `name` = 'sys_embed_default' AND `value` = '';

-- ACL

ALTER TABLE `sys_acl_levels` CHANGE `Icon` `Icon` TEXT NOT NULL;

UPDATE `sys_acl_levels` SET `Icon` = 'user-secret col-blue3' WHERE `ID` IN(7, 8) AND `Icon` = 'user-secret bx-def-font-color';

-- Module

UPDATE `sys_modules` SET `date` = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_install_time' LIMIT 1)  WHERE `name` = 'system' AND `date` = 0;

-- Storage, transcoder & uploader

CREATE TABLE IF NOT EXISTS `sys_images_editor` (
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

CREATE TABLE IF NOT EXISTS `sys_images_editor_resized` (
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

DELETE FROM `sys_objects_storage` WHERE `object` IN('sys_images_editor', 'sys_images_editor_resized');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_images_editor', 'Local', '', 360, 2592000, 3, 'sys_images_editor', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('sys_images_editor_resized', 'Local', '', 360, 2592000, 3, 'sys_images_editor_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

DELETE FROM `sys_objects_uploader` WHERE `object` = 'sys_cmts_html5';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts_html5', 1, 'BxTemplCmtsUploaderHTML5', '');

-- Forms

DELETE FROM `sys_objects_form` WHERE `object` = 'sys_manage';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_manage', 'system', '_sys_form_manage', '', '', 'a:2:{i:0;s:7:"do_send";i:1;s:9:"do_submit";}', '', '', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `display_name` = 'sys_manage_approve';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_manage_approve', 'system', 'sys_manage', '_sys_form_display_manage_approve', 0);

UPDATE `sys_form_inputs` SET `value` = 'a:1:{i:0;s:14:"sys_cmts_html5";}', `values` = 'a:1:{s:14:"sys_cmts_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object` = 'sys_comment';
UPDATE `sys_form_inputs` SET `value` = 'a:1:{i:0;s:14:"sys_cmts_html5";}', `values` = 'a:1:{s:14:"sys_cmts_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object` = 'sys_review';

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_manage';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_manage', 'system', 'content_id', '', '', 0, 'hidden', '', '_sys_form_manage_input_sys_content_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_manage', 'system', 'notes', '', '', 0, 'textarea', '_sys_form_manage_input_sys_notes', '_sys_form_manage_input_notes', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_manage', 'system', 'controls', '', 'do_send,do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_manage', 'system', 'do_send', '_sys_form_manage_input_do_send', '', 0, 'submit', '_sys_form_manage_input_sys_do_send', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_manage', 'system', 'do_submit', '_sys_form_manage_input_do_submit', '', 0, 'submit', '_sys_form_manage_input_sys_do_submit', '', '', 0, 0, 0, 'a:1:{s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),
('sys_manage', 'system', 'do_cancel', '_sys_form_manage_input_do_cancel', '', 0, 'button', '_sys_form_manage_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:5:"class";s:22:"bx-def-margin-sec-left";s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";}', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_display_inputs` SET `order` = 6 WHERE `display_name` = 'sys_login' AND `input_name` = 'submit_text' AND `order` = 7;
UPDATE `sys_form_display_inputs` SET `order` = 7 WHERE `display_name` = 'sys_login' AND `input_name` = 'login' AND `order` = 6;

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_manage_approve';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_manage_approve', 'content_id', 2147483647, 1, 1),
('sys_manage_approve', 'notes', 2147483647, 1, 2),
('sys_manage_approve', 'controls', 2147483647, 1, 3),
('sys_manage_approve', 'do_send', 2147483647, 1, 4),
('sys_manage_approve', 'do_submit', 2147483647, 1, 5),
('sys_manage_approve', 'do_cancel', 2147483647, 1, 6);

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_vote_reactions';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_vote_reactions', 'like', 1, '_sys_pre_lists_vote_reactions_like', '', 'a:5:{s:5:"emoji";s:4:"👍";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-gray";s:6:"weight";s:1:"1";s:7:"default";s:9:"thumbs-up";}'),
('sys_vote_reactions', 'love', 2, '_sys_pre_lists_vote_reactions_love', '', 'a:4:{s:5:"emoji";s:4:"💓";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-red1";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'joy', 3, '_sys_pre_lists_vote_reactions_joy', '', 'a:4:{s:5:"emoji";s:4:"😆";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'surprise', 4, '_sys_pre_lists_vote_reactions_surprise', '', 'a:4:{s:5:"emoji";s:4:"😲";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'sadness', 5, '_sys_pre_lists_vote_reactions_sadness', '', 'a:4:{s:5:"emoji";s:4:"😥";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'anger', 6, '_sys_pre_lists_vote_reactions_anger', '', 'a:4:{s:5:"emoji";s:4:"😠";s:4:"icon";s:0:"";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}');

-- Menu

UPDATE `sys_menu_templates` SET `visible` = 0 WHERE `id` IN(8, 18);

SET @iMaxIdTemplate = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_menu_templates`);

UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 1 WHERE `id` = 27 AND `template` != 'menu_profile_followings.html';
UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 2 WHERE `id` = 28 AND `template` != 'menu_main.html';
UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 3 WHERE `id` = 29 AND `template` != 'menu_add_content.html';

DELETE FROM `sys_menu_templates` WHERE `id` IN(27, 28, 29);
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(27, 'menu_profile_followings.html', '_sys_menu_template_title_profile_followings', 0),
(28, 'menu_main.html', '_sys_menu_template_title_main', 0),
(29, 'menu_add_content.html', '_sys_menu_template_title_add_content', 0);

UPDATE `sys_objects_menu` SET `template_id` = 28 WHERE `object` = 'sys_site' AND `template_id` = 7;
UPDATE `sys_objects_menu` SET `template_id` = 29 WHERE `object` = 'sys_add_content' AND `template_id` = 7;

DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_account_settings_more';

DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_profile_followings', 'sys_dashboard_content', 'sys_dashboard_reports');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_profile_followings', '_sys_menu_title_profile_followings', 'sys_profile_followings', 'system', 27, 0, 1, 'BxTemplMenuProfileFollowings', ''),
('sys_dashboard_content', '_sys_menu_title_dashboard_content_manage', 'sys_dashboard_content_manage', 'system', 15, 0, 1, 'BxTemplMenuDashboardContentManage', ''),
('sys_dashboard_reports', '_sys_menu_title_dashboard_reports_manage', 'sys_dashboard_reports_manage', 'system', 15, 0, 1, 'BxTemplMenuDashboardReportsManage', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` IN('sys_dashboard_content_manage', 'sys_dashboard_reports_manage', 'sys_profile_followings', 'sys_account_settings_more');
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_dashboard_content_manage', 'system', '_sys_menu_set_title_dashboard_content_manage', 0),
('sys_dashboard_reports_manage', 'system', '_sys_menu_set_title_dashboard_reports_manage', 0),
('sys_profile_followings', 'system', '_sys_menu_set_title_profile_followings', 0);

UPDATE `sys_menu_items` SET `icon` = 'una.svg' WHERE `set_name` = 'sys_footer' AND `name` = 'powered_by' AND `icon` = 'una.png';

UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(\'#bx-sliding-menu-sys_add_content\', $(\'#bx-menu-toolbar-item-add-content a\').get(0), \'site\');' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'add-content';

UPDATE `sys_menu_items` SET `name` = 'account-profile-switcher' WHERE `set_name` = 'sys_account_settings' AND `name` = 'account-settings-profile-context';

DELETE FROM `sys_menu_items` WHERE `set_name` IN('sys_account_settings', 'sys_account_settings_more') AND `name` = 'account-settings-delete';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_settings', 'system', 'account-settings-delete', '_sys_menu_item_title_system_account_settings_delete', '_sys_menu_item_title_account_settings_delete', 'page.php?i=account-settings-delete', '', '', 'remove', '', 2147483646, 1, 1, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard' AND `name` IN('dashboard-content', 'dashboard-reports', 'dashboard-audit');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_dashboard', 'system', 'dashboard-content', '_sys_menu_item_title_system_account_dashboard_content', '_sys_menu_item_title_account_dashboard_content', 'page.php?i=dashboard-content', '', '', 'copy', '', '', 2147483646, 1, 1, 5),
('sys_account_dashboard', 'system', 'dashboard-reports', '_sys_menu_item_title_system_account_dashboard_reports', '_sys_menu_item_title_account_dashboard_reports', 'page.php?i=dashboard-reports', '', '', 'exclamation-circle', '', '', 2147483646, 1, 1, 6),
('sys_account_dashboard', 'system', 'dashboard-audit', '_sys_menu_item_title_system_account_dashboard_audit', '_sys_menu_item_title_account_dashboard_audit', 'page.php?i=dashboard-audit', '', '', 'history', '', '', 2147483646, 1, 1, 7);

-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_studio_groups_roles', 'sys_reports_administration');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_groups_roles', 'Sql', 'SELECT * FROM `sys_form_pre_values` WHERE 1 ', 'sys_form_pre_values', 'id', 'Order', '', '', 20, NULL, 'start', '', '', 'LKey', 'like', '', '', 'BxTemplStudioFormsGroupsRoles', ''),
('sys_reports_administration', 'Sql', 'WHERE 1 ', '', 'id', 'date', '', '', 20, NULL, 'start', '', 'text,type', '', 'like', '', '', 'BxTemplReportsGrid', '');

UPDATE `sys_objects_grid` SET `sorting_fields` = 'content_module,profile_id,content_id,author_id,context_profile_id,added', `sorting_fields_translatable` = 'action_lang_key' WHERE `object` = 'sys_audit_administration';

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_studio_groups_roles');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_groups_roles', 'order', '', '2%', 0, 0, '', 1),
('sys_studio_groups_roles', 'LKey', '_adm_rl_txt_title', '40%', 1, 0, '', 2),
('sys_studio_groups_roles', 'actions_list', '_adm_rl_txt_actions', '10%', 0, 35, '', 3),
('sys_studio_groups_roles', 'actions', '', '48%', 0, 0, '', 4);

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_audit_administration');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_audit_administration', 'added', '_adm_form_txt_audit_added', '15%', 1, 25, '', 1),
('sys_audit_administration', 'profile_id', '_adm_form_txt_audit_profile', '15%', 1, 25, '', 2),
('sys_audit_administration', 'content_id', '_adm_form_txt_audit_content', '20%', 1, 25, '', 3),
('sys_audit_administration', 'author_id', '_adm_form_txt_audit_author_content', '10%', 1, 25, '', 4),
('sys_audit_administration', 'content_module', '_adm_form_txt_audit_module', '10%', 1, 25, '', 5),
('sys_audit_administration', 'context_profile_id', '_adm_pgt_txt_audit_context', '15%', 1, 25, '', 6),
('sys_audit_administration', 'action_lang_key', '_adm_pgt_txt_audit_action', '15%', 1, 25, '', 7);

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_reports_administration');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_reports_administration', 'object', '_adm_form_txt_reports_object', '10%', 1, 25, '', 1),
('sys_reports_administration', 'author', '_adm_form_txt_reports_author', '10%', 1, 25, '', 2),
('sys_reports_administration', 'type', '_adm_form_txt_reports_type', '10%', 1, 25, '', 3),
('sys_reports_administration', 'text', '_adm_form_txt_reports_text', '10%', 1, 25, '', 4),
('sys_reports_administration', 'date', '_adm_form_txt_reports_date', '10%', 1, 25, '', 5),
('sys_reports_administration', 'status', '_adm_form_txt_reports_status', '10%', 1, 25, '', 6),
('sys_reports_administration', 'checked_by', '_adm_form_txt_reports_checked_by', '10%', 1, 25, '', 7),
('sys_reports_administration', 'notes', '_adm_form_txt_reports_notes', '10%', 1, 25, '', 8),
('sys_reports_administration', 'comments', '_adm_form_txt_reports_comments', '10%', 1, 25, '', 8),
('sys_reports_administration', 'actions', '', '10%', 0, '', '', 9);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_groups_roles';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_groups_roles', 'independent', 'add', '_adm_rl_btn_role_add', '', 0, 1),
('sys_studio_groups_roles', 'single', 'edit', '_adm_rl_btn_role_edit', 'pencil-alt', 0, 1),
('sys_studio_groups_roles', 'single', 'delete', '_adm_rl_btn_role_delete', 'remove', 1, 3);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_reports_administration';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`, `icon_only`) VALUES
('sys_reports_administration', 'single', 'check_in', '_adm_form_btn_reports_check_in', 'lock-open', 0, 1, 1),
('sys_reports_administration', 'single', 'check_out', '_adm_form_btn_reports_check_out', 'lock', 0, 2, 1),
('sys_reports_administration', 'single', 'audit', '_adm_form_btn_reports_audit', 'history', 0, 3, 1);

UPDATE `sys_objects_grid` SET `table` = 'sys_cmts_ids' WHERE `object` = 'sys_cmts_administration';

-- Transcoder

DELETE FROM `sys_objects_transcoder` WHERE `object` = 'sys_images_editor';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_images_editor', 'sys_images_editor_resized', 'Storage', 'a:1:{s:6:"object";s:17:"sys_images_editor";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'sys_images_editor';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_images_editor', 'Resize', 'a:2:{s:1:"w";s:4:"1600";s:1:"h";s:4:"1600";}', '0');

-- Pages

DELETE FROM `sys_objects_page` WHERE `object` IN('sys_dashboard_content', 'sys_dashboard_reports', 'sys_dashboard_audit');
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `sticky_columns`) VALUES
('sys_dashboard_content', 'dashboard-content', '_sys_page_title_system_dashboard_content', '_sys_page_title_dashboard_content', 'system', 1, 12, '', 2147483646, 1, 'page.php?i=dashboard-content', '', '', '', 0, 1, 0, 'BxTemplPageDashboard', '', 0),
('sys_dashboard_reports', 'dashboard-reports', '_sys_page_title_system_dashboard_reports', '_sys_page_title_dashboard_reports', 'system', 1, 12, '', 2147483646, 1, 'page.php?i=dashboard-reports', '', '', '', 0, 1, 0, 'BxTemplPageDashboard', '', 0),
('sys_dashboard_audit', 'dashboard-audit', '_sys_page_title_system_dashboard_audit', '_sys_page_title_dashboard_audit', 'system', 1, 12, '', 2147483646, 1, 'page.php?i=dashboard-audit', '', '', '', 0, 1, 0, 'BxTemplPageDashboard', '', 0);

UPDATE `sys_objects_page` SET `layout_id` = 18 WHERE `layout_id` = 5 AND `object` IN('sys_create_account', 'sys_login', 'sys_login_step2', 'sys_login_step3');

UPDATE `sys_objects_page` SET `title_system` = '_sys_page_title_system_cmts_view' WHERE `object` = 'sys_cmts_view';

DELETE FROM `sys_pages_layouts` WHERE `name` IN('1_column_thin', '1_column_half');
INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES
(18, '1_column_thin', 'layout_1_column_thin.png', '_sys_layout_1_column_thin', 'layout_1_column_thin.html', 1),
(19, '1_column_half', 'layout_1_column_half.png', '_sys_layout_1_column_half', 'layout_1_column_half.html', 1);

-- Pages blocks

SET @iBlockOrder = IFNULL((SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1), 0);

DELETE FROM `sys_pages_blocks` WHERE `object` = '' AND `module` = 'system' AND `title_system` = '_sys_page_block_title_sys_std_site_submenu';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'system', '_sys_page_block_title_sys_std_site_submenu', '_sys_page_block_title_std_site_submenu', 3, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_site_submenu";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, @iBlockOrder + 1);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` IN('_sys_page_block_title_profile_avatar', '_sys_page_block_title_profile_menu', '_sys_page_block_title_profile_followings');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 0, 'system', '', '_sys_page_block_title_profile_avatar', 3, 0, 0, 2147483646, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"profile_avatar";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 0, 1, 1),
('sys_home', 0, 'system', '', '_sys_page_block_title_profile_menu', 3, 0, 0, 2147483644, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:12:"profile_menu";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 0, 1, 2),
('sys_home', 0, 'system', '', '_sys_page_block_title_profile_followings', 3, 0, 0, 2147483644, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"profile_followings";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 0, 1, 3);

UPDATE `sys_pages_blocks` SET `designbox_id` = 13 WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_homepage_menu' AND `designbox_id` = 3;

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard_content' AND `title` = '_sys_page_block_title_dashboard_content';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard_audit' AND `title` = '_sys_page_block_title_dashboard_audit';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard_reports' AND `title` = '_sys_page_block_title_dashboard_reports';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard_content', 1, 'system', '', '_sys_page_block_title_dashboard_content', 11, 1, 0, 192, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"manage_content";s:6:"params";a:0:{}s:5:"class";s:22:"TemplDashboardServices";}', 0, 1, 1, 1),
('sys_dashboard_audit', 1, 'system', '', '_sys_page_block_title_dashboard_audit', 11, 0, 0, 192, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:12:"manage_audit";s:6:"params";a:0:{}s:5:"class";s:22:"TemplDashboardServices";}', 0, 1, 1, 1),
('sys_dashboard_reports', 1, 'system', '', '_sys_page_block_title_dashboard_reports', 11, 1, 0, 192, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"manage_reports";s:6:"params";a:0:{}s:5:"class";s:22:"TemplDashboardServices";}', 0, 1, 1, 1);

UPDATE `sys_pages_blocks` SET `title_system` = '_sys_page_block_title_system_cmts_view' WHERE `object` = 'sys_cmts_view' AND `title` = '_cmt_page_view_title';

-- Logs

DELETE FROM `sys_objects_logs` WHERE `object` IN('sys_cron_jobs', 'sys_transcoder');
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('sys_cron_jobs', 'system', 'Auto', '_sys_log_cron_jobs', 0, '', ''),
('sys_transcoder', 'system', 'Auto', '_sys_log_transcoder', 1, '', '');

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'css_system' AND `content` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_preloader_content";s:6:"params";a:1:{i:0;s:8:"tailwind";}s:5:"class";s:12:"BaseServices";}';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'css_system', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_preloader_content";s:6:"params";a:1:{i:0;s:8:"tailwind";}s:5:"class";s:12:"BaseServices";}', 1, 4);

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` = 'BxDolConnection.js';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'BxDolConnection.js', 1, 44);

-- Switch upgrade channel

UPDATE `sys_options` SET `value` = 'beta' WHERE `name` = 'sys_upgrade_channel';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-A1' WHERE (`version` = '12.1.0.B1') AND `name` = 'system';

