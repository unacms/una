
-- Embeds

CREATE TABLE IF NOT EXISTS `sys_objects_embeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

TRUNCATE TABLE `sys_objects_embeds`;
INSERT INTO `sys_objects_embeds` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_embedly', 'Embedly', 'BxTemplEmbedEmbedly', ''),
('sys_iframely', 'Iframely', 'BxTemplEmbedIframely', '');

-- Settings

DELETE FROM `sys_options` WHERE `name` IN('sys_email_confirmation', 'sys_embed_default', 'sys_tinymce_plugins_mini', 'sys_tinymce_toolbar_mini', 'sys_tinymce_plugins_standard', 'sys_tinymce_toolbar_standard', 'sys_tinymce_plugins_full', 'sys_tinymce_toolbar_full', 'sys_embedly_api_pattern', 'sys_account_confirmation_type', 'sys_account_activation_2fa_enable');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_embed_default', '_adm_stg_cpt_option_sys_embed_default', 'sys_embedly', 'digit', '', '', '', 52),
(@iCategoryIdHidden, 'sys_tinymce_plugins_mini', '_adm_stg_cpt_option_sys_tinymce_plugins_mini', 'autolink,image,link,lists,paste,fullscreen', 'digit', '', '', '', 70),
(@iCategoryIdHidden, 'sys_tinymce_toolbar_mini', '_adm_stg_cpt_option_sys_tinymce_toolbar_mini', 'bold italic underline removeformat | bullist numlist | alignleft aligncenter alignright | blockquote | link unlink image | fullscreen', 'digit', '', '', '', 71),
(@iCategoryIdHidden, 'sys_tinymce_plugins_standard', '_adm_stg_cpt_option_sys_tinymce_plugins_standard', 'advlist,autolink,autosave,code,hr,image,link,lists,media,paste,fullscreen', 'digit', '', '', '', 73),
(@iCategoryIdHidden, 'sys_tinymce_toolbar_standard', '_adm_stg_cpt_option_sys_tinymce_toolbar_standard', 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullscreen', 'digit', '', '', '', 74),
(@iCategoryIdHidden, 'sys_tinymce_plugins_full', '_adm_stg_cpt_option_sys_tinymce_plugins_full', 'advlist,anchor,autolink,autoresize,autosave,charmap,code,emoticons,hr,image,link,lists,media,nonbreaking,pagebreak,preview,paste,save,searchreplace,table,textcolor,visualblocks,fullscreen', 'digit', '', '', '', 76),
(@iCategoryIdHidden, 'sys_tinymce_toolbar_full', '_adm_stg_cpt_option_sys_tinymce_toolbar_full', 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image, preview media | forecolor emoticons | fullscreen', 'digit', '', '', '', 77);

SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_confirmation_type', '_adm_stg_cpt_option_sys_account_confirmation_type', 'email', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:22:"get_confirmation_types";s:5:"class";s:18:"BaseServiceAccount";}', '', '', 12),
(@iCategoryIdAccount, 'sys_account_activation_2fa_enable', '_adm_stg_cpt_option_sys_account_2fa_enable', '', 'checkbox', '', '', '', 13);

UPDATE `sys_options` SET `value` = '500' WHERE `name` IN('sys_eq_send_per_start','sys_push_queue_send_per_start') AND `value` = '20';

-- Settings: Twilio

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

DELETE FROM `sys_options_categories` WHERE `name` = 'twilio_gate';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'twilio_gate', '_adm_stg_cpt_category_twilio_gate', 0, 18);
SET @iCategoryIdTwilio = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN('sys_twilio_gate_sid', 'sys_twilio_gate_token', 'sys_twilio_gate_from_number');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdTwilio, 'sys_twilio_gate_sid', '_adm_stg_cpt_option_sys_twilio_gate_sid', '', 'digit', '', '', '', 1),
(@iCategoryIdTwilio, 'sys_twilio_gate_token', '_adm_stg_cpt_option_sys_twilio_gate_token', '', 'digit', '', '', '', 2),
(@iCategoryIdTwilio, 'sys_twilio_gate_from_number', '_adm_stg_cpt_option_sys_twilio_gate_from_number', '', 'digit', '', '', '', 3);

-- ACL

UPDATE `sys_acl_actions` SET `DisabledForLevels` = 0 WHERE `Module` = 'system' AND `Name` = 'post links';

-- Score

CREATE TABLE IF NOT EXISTS `sys_objects_score` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `module` varchar(32) NOT NULL,
  `table_main` varchar(50) NOT NULL default '',
  `table_track` varchar(50) NOT NULL default '',
  `post_timeout` int(11) NOT NULL default '0',
  `is_on` tinyint(1) NOT NULL default '1',
  `trigger_table` varchar(32) NOT NULL default '',
  `trigger_field_id` varchar(32) NOT NULL default '',
  `trigger_field_author` varchar(32) NOT NULL default '',
  `trigger_field_score` varchar(32) NOT NULL default '',
  `trigger_field_cup` varchar(32) NOT NULL default '',
  `trigger_field_cdown` varchar(32) NOT NULL default '',
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY (`ID`)
);

TRUNCATE TABLE `sys_objects_embeds`;
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('sys_cmts', 'system', 'sys_cmts_scores', 'sys_cmts_scores_track', '604800', '0', 'sys_cmts_ids', 'id', '', 'score', 'sc_up', 'sc_down', '', '');

-- Report tables

CREATE TABLE IF NOT EXISTS `sys_cmts_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
);

-- Score tables

CREATE TABLE IF NOT EXISTS `sys_cmts_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- Reports

DELETE FROM `sys_objects_report` WHERE `name` = 'sys_cmts';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('sys_cmts', 'sys_cmts_reports', 'sys_cmts_reports_track', '1', '', 'sys_cmts_ids', 'id', '', 'reports',  '', '');

-- Forms: objects

UPDATE `sys_objects_form` SET `submit_name` = 'a:3:{i:0;s:4:"role";i:1;s:10:"do_sendsms";i:2;s:12:"do_checkcode";}', `params` = 'a:1:{s:14:"checker_helper";s:24:"BxFormLoginCheckerHelper";}' WHERE `object` = 'sys_login';

DELETE FROM `sys_objects_form` WHERE `object` = 'sys_confirm_phone';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_confirm_phone', 'system', '_sys_form_confirm_phone', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_sendsms";}', '', '', '', '', 'a:1:{s:14:"checker_helper";s:31:"BxFormConfirmPhoneCheckerHelper";}', 0, 1, 'BxTemplFormConfirmPhone', '');

-- Forms: displays

DELETE FROM `sys_form_displays` WHERE `display_name` IN('sys_login_step2', 'sys_login_step3', 'sys_confirm_phone_set_phone', 'sys_confirm_phone_confirmation');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_login_step2', 'system', 'sys_login', '_sys_form_display_login_step2', 0),
('sys_login_step3', 'system', 'sys_login', '_sys_form_display_login_step3', 0),
('sys_confirm_phone_set_phone', 'system', 'sys_confirm_phone', '_sys_form_display_confirm_phone_set_phone', 0),
('sys_confirm_phone_confirmation', 'system', 'sys_confirm_phone', '_sys_form_display_confirm_phone_confirmation', 0);

-- Forms: inputs

UPDATE `sys_form_inputs` SET `checker_func` = 'EmailExistOrEmpty' WHERE `object` = 'sys_forgot_password' AND `name` = 'email' AND `checker_func` = 'EmailExist';

UPDATE `sys_form_inputs` SET `editable` = 1 WHERE `object` = 'sys_forgot_password' AND `name` = 'captcha';


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_login' AND `name` IN('phone', 'code', 'back', 'do_checkcode', 'do_sendsms');
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_forgot_password' AND `name` IN('phone');
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_confirm_phone';

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES

('sys_login', 'system', 'phone', '', '', 0, 'text', '_sys_form_login_input_caption_system_phone', '_sys_form_login_input_phone', '', 1, 0, 0, '', '', '', 'PhoneExist', '', '_sys_form_login_input_phone_error_format', 'Xss', '', 1, 0),
('sys_login', 'system', 'code', '', '', 0, 'text', '_sys_form_login_input_caption_system_code', '_sys_form_login_input_code', '', 1, 0, 0, '', '', '', 'CodeExist', '', '_sys_form_login_input_code_error_empty', 'Xss', '', 0, 0),
('sys_login', 'system', 'back', '', '', '', 'value', '_sys_form_login_input_caption_system_back', '_sys_form_login_input_back', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('sys_login', 'system', 'do_checkcode', '_sys_form_login_input_checkcode', '', 0, 'submit', '_sys_form_login_input_caption_system_checkcode', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'do_sendsms', '_sys_form_login_input_sendsms', '', 0, 'submit', '_sys_form_login_input_caption_system_sendsms', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_forgot_password', 'system', 'phone', '', '', 0, 'text', '_sys_form_forgot_password_input_caption_system_phone', '_sys_form_forgot_password_input_phone', '', 1, 0, 0, '', '', '', 'PhoneExistOrEmpty', '', '_sys_form_account_input_phone_error', 'Xss', '', 0, 0),

('sys_confirm_phone', 'system', 'phone', '', '', 0, 'text', '_sys_form_confirm_phone_input_caption_system_phone', '_sys_form_confirm_phone_input_phone', '', 1, 0, 0, '', '', '', 'PhoneUniq', '', '_sys_form_confirm_phone_input_phone_error_format', 'Xss', '', 1, 0),
('sys_confirm_phone', 'system', 'code', '', '', 0, 'text', '_sys_form_confirm_phone_input_caption_system_code', '_sys_form_confirm_phone_confirmation_input_code', '', 1, 0, 0, '', '', '', 'CodeExist', '', '_sys_form_confirm_phone_input_code_error_empty', 'Xss', '', 0, 0),
('sys_confirm_phone', 'system', 'do_submit', '_sys_form_confirm_phone_input_submit', '', 0, 'submit', '_sys_form_confirm_phone_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_confirm_phone', 'system', 'do_sendsms', '_sys_form_confirm_phone_input_sendsms', '', 0, 'submit', '_sys_form_confirm_phone_input_caption_system_do_sendsms', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

-- Forms: display inputs

UPDATE `sys_form_display_inputs` SET `order` = 6 WHERE `display_name` = 'sys_account_create' AND `input_name` = 'agreement' AND `order` = 7;
UPDATE `sys_form_display_inputs` SET `order` = 3 WHERE `display_name` = 'sys_forgot_password' AND `input_name` = 'captcha' AND `order` = 2;
UPDATE `sys_form_display_inputs` SET `order` = 4 WHERE `display_name` = 'sys_forgot_password' AND `input_name` = 'do_submit' AND `order` = 3;

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_forgot_password') AND `input_name` = 'phone';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_login_step2', 'sys_login_step3', 'sys_confirm_phone_set_phone', 'sys_confirm_phone_confirmation');

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_login_step2', 'phone', 2147483647, 1, 1),
('sys_login_step2', 'do_sendsms', 2147483647, 1, 2),
('sys_login_step2', 'relocate', 2147483647, 1, 3),

('sys_login_step3', 'code', 2147483647, 1, 1),
('sys_login_step3', 'back', 2147483647, 1, 2),
('sys_login_step3', 'do_checkcode', 2147483647, 1, 3),
('sys_login_step3', 'relocate', 2147483647, 1, 4),

('sys_forgot_password', 'phone', 2147483647, 1, 2),

('sys_confirm_phone_set_phone', 'phone', 2147483647, 1, 1),
('sys_confirm_phone_set_phone', 'do_sendsms', 2147483647, 1, 2),

('sys_confirm_phone_confirmation', 'code', 2147483647, 1, 1),
('sys_confirm_phone_confirmation', 'do_submit', 2147483647, 1, 2);

-- Menu: templates

UPDATE `sys_menu_templates` SET `template` = 'menu_custom_hor.html', `title` = '_sys_menu_template_title_custom_hor' WHERE `id` = 15 AND `template` = 'menu_custom.html' AND `title` = '_sys_menu_template_title_custom';


DELETE FROM `sys_menu_templates` WHERE `template` = 'menu_custom_ver.html';

SET @iMax = (SELECT MAX(`id`)+1 FROM `sys_menu_templates`);
UPDATE `sys_menu_templates` SET `id` = @iMax WHERE `id` = 20;

INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(20, 'menu_custom_ver.html', '_sys_menu_template_title_custom_ver', 0);

-- Menu: objects

UPDATE `sys_objects_menu` SET `template_id` = 20 WHERE `object` = 'sys_cmts_item_manage' AND `template_id` = 6;

-- Menu: items

UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(\'#bx-sliding-menu-search\', this, \'site\');' WHERE `set_name` = 'sys_site' AND `name` = 'search';
UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(\'#bx-sliding-menu-sys_site\', this, \'site\');' WHERE `set_name` = 'sys_toolbar_site' AND `name` = 'main-menu';
UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(''#bx-sliding-menu-search'', this, ''site'');' WHERE `set_name` = 'sys_toolbar_site' AND `name` = 'search';
UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(\'#bx-sliding-menu-sys_add_content\', this, \'site\');' WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'add-content';
UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(''#bx-sliding-menu-account'', this, ''site'');' WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'account';
UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_slide_inline(\'#bx-sliding-menu-sys_add_content\', $(\'bx-menu-toolbar-item-add-content a\').get(0), \'site\');' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'add-content';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_manage' AND `name` = 'item-report';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_cmts_item_manage', 'system', 'item-report', '_sys_menu_item_title_system_cmts_item_report', '', 'javascript:void(0)', '', '', '', '', 2147483647, 1, 0, 0);

UPDATE `sys_menu_items` SET `editable` = 1 WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-vote';
UPDATE `sys_menu_items` SET `order` = 3 WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-reply';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-score';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_cmts_item_actions', 'system', 'item-score', '_sys_menu_item_title_system_cmts_item_score', '_sys_menu_item_title_cmts_item_score', 'javascript:void(0)', '', '', '', '', '', 2147483647, 0, 0, 1, 2);

-- Pages: pages

DELETE FROM `sys_objects_page` WHERE `object` IN('sys_login_step2', 'sys_login_step3', 'sys_confirm_phone');
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_login_step2', 'login-step2', '_sys_page_title_system_login_step2', '_sys_page_title_login_step2', 'system', 5, 2147483647, 1, 'page.php?i=login-step2', '', '', '', 0, 1, 0, '', ''),
('sys_login_step3', 'login-step3', '_sys_page_title_system_login_step3', '_sys_page_title_login_step3', 'system', 5, 2147483647, 1, 'page.php?i=login-step3', '', '', '', 0, 1, 0, '', ''),
('sys_confirm_phone', 'confirm-phone', '_sys_page_title_system_confirm_phone', '_sys_page_title_confirm_phone', 'system', 5, 2147483647, 1, 'page.php?i=confirm-phone', '', '', '', 0, 1, 0, '', '');

-- Pages: layouts

DELETE FROM `sys_pages_layouts` WHERE `name` IN('bar_content_bar', 'top_area_bar_content_bar');

SET @iMaxId = (SELECT MAX(`id`) FROM `sys_pages_layouts`);
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 1 WHERE `id` = 14;
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 2 WHERE `id` = 15;

INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES(14, 'bar_content_bar', 'layout_bar_content_bar.png', '_sys_layout_bar_content_bar', 'layout_bar_content_bar.html', 3),
(15, 'top_area_bar_content_bar', 'layout_top_area_bar_content_bar.png', '_sys_layout_top_area_bar_content_bar', 'layout_top_area_bar_content_bar.html', 4);


-- Pages: blocks

DELETE FROM `sys_pages_blocks` WHERE `object` IN('sys_login_step2', 'sys_login_step3', 'sys_confirm_phone');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES

('sys_login_step2', 1, 'system', '_sys_page_block_system_title_login_step2', '_sys_page_block_title_login_step2', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:16:\"login_form_step2\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1, 1),

('sys_login_step3', 1, 'system', '_sys_page_block_system_title_login_step3', '_sys_page_block_title_login_step3', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:16:\"login_form_step3\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1, 1),

('sys_confirm_phone', 1, 'system', '', '_sys_page_block_title_confirm_phone', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"phone_confirmation";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1);

-- Preloader 

CREATE TABLE IF NOT EXISTS `sys_preloader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `type` varchar(16) NOT NULL,
  `content` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_preloader`;
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`) VALUES
('system', 'css_system', '{dir_plugins_public}marka/|marka.min.css', 1),
('system', 'css_system', '{dir_plugins_public}at.js/css/|jquery.atwho.min.css', 1),
('system', 'css_system', '{dir_plugins_public}prism/|prism.css', 1),
('system', 'css_system', 'common.css', 1),
('system', 'css_system', 'default.less', 1),
('system', 'css_system', 'general.css', 1),
('system', 'css_system', 'icons.css', 1),
('system', 'css_system', 'colors.css', 1),
('system', 'css_system', 'forms.css', 1),
('system', 'css_system', 'media-desktop.css', 1),
('system', 'css_system', 'media-tablet.css', 1),
('system', 'css_system', 'media-phone.css', 1),
('system', 'css_system', 'media-print.css', 1),
('system', 'css_system', 'cmts.css', 1),
('system', 'css_system', 'favorite.css', 1),
('system', 'css_system', 'feature.css', 1),
('system', 'css_system', 'report.css', 1),
('system', 'css_system', 'score.css', 1),
('system', 'css_system', 'view.css', 1),
('system', 'css_system', 'vote.css', 1),

('system', 'js_system', 'jquery/jquery.min.js', 1),
('system', 'js_system', 'jquery/jquery-migrate.min.js', 1),
('system', 'js_system', 'jquery-ui/jquery.ui.position.min.js', 1),
('system', 'js_system', 'jquery.easing.js', 1),
('system', 'js_system', 'jquery.cookie.min.js', 1),
('system', 'js_system', 'jquery.form.min.js', 1),
('system', 'js_system', 'spin.min.js', 1),
('system', 'js_system', 'moment-with-locales.min.js', 1),
('system', 'js_system', 'marka/marka.min.js', 1),
('system', 'js_system', 'headroom.min.js', 1),
('system', 'js_system', 'at.js/js/jquery.atwho.min.js', 1),
('system', 'js_system', 'prism/prism.js', 1),
('system', 'js_system', 'functions.js', 1),
('system', 'js_system', 'jquery.webForms.js', 1),
('system', 'js_system', 'jquery.dolPopup.js', 1),
('system', 'js_system', 'jquery.dolConverLinks.js', 1),
('system', 'js_system', 'jquery.anim.js', 1),
('system', 'js_system', 'BxDolCmts.js', 1),
('system', 'js_system', 'BxDolFavorite.js', 1),
('system', 'js_system', 'BxDolFeature.js', 1),
('system', 'js_system', 'BxDolReport.js', 1),
('system', 'js_system', 'BxDolScore.js', 1),
('system', 'js_system', 'BxDolView.js', 1),
('system', 'js_system', 'BxDolVote.js', 1),

('system', 'js_translation', '_Are_you_sure', 1),
('system', 'js_translation', '_error occured', 1),
('system', 'js_translation', '_sys_loading', 1),
('system', 'js_translation', '_copyright', 1);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC7' WHERE (`version` = '9.0.0.RC6' OR `version` = '9.0.0-RC6') AND `name` = 'system';

