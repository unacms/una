
-- ==============================================================================
-- ========================= B1 =================================================
-- ==============================================================================

-- Email templates

DELETE FROM `sys_email_templates` WHERE `Name` = 't_PasswordReset';

-- Settings: hidden

UPDATE `sys_options` SET `check` = 'Segment', `check_params` = 'a:2:{s:3:"min";i:1;s:3:"max";i:10;}', `check_error` = '_adm_stg_cpt_option_sys_template_cache_image_max_size_err' WHERE `name` = 'sys_template_cache_image_max_size';


DELETE FROM `sys_options` WHERE `name` IN('sys_logs_storage_default', 'sys_default_curl_timeout');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_logs_storage_default', '_adm_stg_cpt_option_sys_logs_storage_default', 'Folder', 'select', 'Folder,PHPLog,STDErr', '', '', '', 130),
(@iCategoryIdHidden, 'sys_default_curl_timeout', '_adm_stg_cpt_option_sys_default_curl_timeout', '10', 'digit', '', '', '', '', 140);

-- Settings: security

DELETE FROM `sys_options` WHERE `name` IN('sys_security_block_content_after_n_reports');

SET @iCategoryIdSecurity = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSecurity, 'sys_security_block_content_after_n_reports', '_adm_stg_cpt_option_sys_security_block_content_after_n_reports', '0', 'digit', '', '', '', 35);

-- Settings: storage

UPDATE `sys_options` SET `extra` = 'Local,S3,S3v4,S3v4alt' WHERE `name` = 'sys_storage_default';

DELETE FROM `sys_options` WHERE `name` IN('sys_storage_s3_endpoint', 'sys_storage_s3_sig_ver', 'sys_storage_s3_region');

SET @iCategoryIdStorage = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'storage');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdStorage, 'sys_storage_s3_endpoint', '_adm_stg_cpt_option_sys_storage_s3_endpoint', '', 'digit', '', '', '', 6),
(@iCategoryIdStorage, 'sys_storage_s3_sig_ver', '_adm_stg_cpt_option_sys_storage_s3_sig_ver', 'v2', 'select', 'v2,v4', '', '', 7),
(@iCategoryIdStorage, 'sys_storage_s3_region', '_adm_stg_cpt_option_sys_storage_s3_region', '', 'digit', '', '', '', 8);


-- Settings: account

UPDATE `sys_options` SET `extra` = 'Local,S3,S3v4,S3v4alt' WHERE `name` = 'sys_storage_default';

DELETE FROM `sys_options` WHERE `name` IN('sys_account_activation_2fa_lifetime', 'sys_account_reset_password_key_lifetime', 'sys_account_reset_password_redirect', 'sys_account_reset_password_redirect_custom');

SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_activation_2fa_lifetime', '_adm_stg_cpt_option_sys_account_2fa_lifetime', '0', 'digit', '', '', '', 14),
(@iCategoryIdAccount, 'sys_account_reset_password_key_lifetime', '_adm_stg_cpt_option_sys_account_reset_password_key_lifetime', '259200', 'digit', '', '', '', 30),
(@iCategoryIdAccount, 'sys_account_reset_password_redirect', '_adm_stg_cpt_option_sys_account_reset_password_redirect', 'home', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:35:"get_options_reset_password_redirect";s:5:"class";s:18:"BaseServiceAccount";}', '', '', 31),
(@iCategoryIdAccount, 'sys_account_reset_password_redirect_custom', '_adm_stg_cpt_option_sys_account_reset_password_redirect_custom', '', 'digit', '', '', '', 32);


-- Settings: location

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name` = 'location';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'location', '_adm_stg_cpt_category_location', 0, 20);
SET @iCategoryIdLocation = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id` = @iCategoryIdLocation WHERE `name` = 'sys_maps_api_key';

DELETE FROM `sys_options` WHERE `name` IN('sys_location_field_default', 'sys_location_map_default', 'sys_location_map_zoom_default', 'sys_nominatim_server', 'sys_nominatim_email', 'sys_location_leaflet_provider');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdLocation, 'sys_location_field_default', '_adm_stg_cpt_option_sys_location_field_default', 'sys_plain', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:34:"get_options_location_field_default";s:5:"class";s:13:"TemplServices";}', '', '', 10),
(@iCategoryIdLocation, 'sys_location_map_default', '_adm_stg_cpt_option_sys_location_map_default', 'sys_leaflet', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:32:"get_options_location_map_default";s:5:"class";s:13:"TemplServices";}', '', '', 12),

(@iCategoryIdLocation, 'sys_location_map_zoom_default', '_adm_stg_cpt_option_sys_location_map_zoom_default', '7', 'digit', '', '', '', 20),

(@iCategoryIdLocation, 'sys_nominatim_server', '_adm_stg_cpt_option_sys_nominatim_server', 'https://nominatim.openstreetmap.org', 'digit', '', '', '', 40),
(@iCategoryIdLocation, 'sys_nominatim_email', '_adm_stg_cpt_option_sys_nominatim_email', '', 'digit', '', '', '', 42),

(@iCategoryIdLocation, 'sys_location_leaflet_provider', '_adm_stg_cpt_option_sys_location_leaflet_provider', 'OpenStreetMap.Mapnik', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:42:"get_options_location_leaflet_get_providers";s:5:"class";s:13:"TemplServices";}', '', '', 50);

-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `Name` IN('comments pin', 'set form fields privacy');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` IN('comments pin', 'set form fields privacy');

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments pin', NULL, '_sys_acl_action_comments_pin', '', 1, 3);
SET @iIdActionCmtPin = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'set form fields privacy', NULL, '_sys_acl_action_set_form_fields_privacy', '_sys_acl_action_set_form_fields_privacy_desc', 0, 3);
SET @iIdActionSetFormFieldsPrivacy = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- comments pin
(@iModerator, @iIdActionCmtPin),
(@iAdministrator, @iIdActionCmtPin),

-- set form fields privacy
(@iAccount, @iIdActionSetFormFieldsPrivacy),
(@iStandard, @iIdActionSetFormFieldsPrivacy),
(@iUnconfirmed, @iIdActionSetFormFieldsPrivacy),
(@iPending, @iIdActionSetFormFieldsPrivacy),
(@iModerator, @iIdActionSetFormFieldsPrivacy),
(@iAdministrator, @iIdActionSetFormFieldsPrivacy),
(@iPremium, @iIdActionSetFormFieldsPrivacy);


-- Vote objects

DELETE FROM `sys_objects_vote` WHERE `Name` IN('sys_form_fields_votes', 'sys_form_fields_reaction');
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('sys_form_fields_votes', 'sys_form_fields_votes', 'sys_form_fields_votes_track', '604800', '1', '1', '0', '1', 'sys_form_fields_ids', 'id', 'author_id', 'rate', 'votes', '', ''),
('sys_form_fields_reaction', 'sys_form_fields_reaction', 'sys_form_fields_reaction_track', '604800', '1', '1', '1', '1', 'sys_form_fields_ids', 'id', 'author_id', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- Votes and reactions for form fields

CREATE TABLE IF NOT EXISTS `sys_form_fields_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `sys_form_fields_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `sys_form_fields_reaction` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `sys_form_fields_reaction_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- Privacy

DELETE FROM `sys_objects_privacy` WHERE `object` = 'sys_form_inputs_allow_view_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('sys_form_inputs_allow_view_to', 'system', 'view', '_sys_privacy_forms_input_allow_view_to', '3', 'sys_form_inputs_privacy', 'id', 'author_id', '', '');

-- Uploader

DELETE FROM `sys_objects_uploader` WHERE `object` = 'sys_video_recording';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_video_recording', 1, 'BxTemplUploaderVideoRecording', '');

-- Forms

DELETE FROM `sys_objects_form` WHERE `object` IN('sys_favorite', 'sys_labels');
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_favorite', 'system', '_sys_form_favorite', 'favorite.php', 'a:3:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:5:"class";s:19:"bx-favorite-do-form";}', 'submit', '', 'id', '', '', '', 0, 1, '', ''),
('sys_labels', 'system', '_sys_form_labels', 'label.php', '', 'do_submit', '', '', '', '', '', 0, 1, 'BxTemplLabelForm', '');

DELETE FROM `sys_form_displays` WHERE `display_name` IN('sys_forgot_password_reset', 'sys_favorite_add', 'sys_favorite_list_edit', 'sys_labels_select');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_forgot_password_reset', 'system', 'sys_forgot_password', '_sys_form_display_forgot_password_reset', 0),
('sys_favorite_add', 'system', 'sys_favorite', '_sys_form_display_favorite_add', 0),
('sys_favorite_list_edit', 'system', 'sys_favorite', '_sys_form_display_favorite_list_edit', 0),
('sys_labels_select', 'system', 'sys_labels', '_sys_form_labels_display_select', 0);

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_account' AND `name` = 'phone';
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_forgot_password' AND `name` IN ('key', 'password');
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_comment' AND `name` IN ('cmt_controls', 'cmt_cancel');
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_favorite' AND `name` IN ('sys', 'list_id', 'object_id', 'action', 'id', 'list', 'new_list', 'title', 'allow_view_favorite_list_to', 'submit');
DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_labels' AND `name` IN ('name', 'action', 'search', 'list', 'controls', 'do_submit', 'do_cancel');

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_account', 'system', 'phone', '', '', 0, 'text', '_sys_form_login_input_caption_system_phone', '_sys_form_login_input_phone', '_sys_form_login_input_phone_info', 1, 0, 0, '', '', '', 'PhoneExist', '', '_sys_form_login_input_phone_error_format', 'Xss', '', 1, 0),

('sys_forgot_password', 'system', 'key', '', '', 0, 'hidden', '_sys_form_forgot_password_input_caption_system_key', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_forgot_password', 'system', 'password', '', '', 0, 'password', '_sys_form_forgot_password_input_caption_system_password', '_sys_form_forgot_password_input_caption_password', '', 1, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:38:"~^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}~";}', '_sys_form_account_input_password_error', 'Xss', '', 1, 0),

('sys_comment', 'system', 'cmt_controls', '', 'cmt_submit,cmt_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_comment', 'system', 'cmt_cancel', '_sys_form_comment_input_cancel', '', 0, 'button', '_sys_form_comment_input_caption_system_cmt_cancel', '', '', 0, 0, 0, 'a:1:{s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),

('sys_favorite', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_favorite_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_favorite', 'system', 'list_id', '', '', 0, 'hidden', '_sys_form_favorite_input_caption_system_list_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_favorite', 'system', 'object_id', '', '', 0, 'hidden', '_sys_form_favorite_input_caption_system_object_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_favorite', 'system', 'action', '', '', 0, 'hidden', '_sys_form_favorite_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_favorite', 'system', 'id', '', '', 0, 'hidden', '_sys_form_favorite_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_favorite', 'system', 'list', '', '#!sys_report_types', 0, 'checkbox_set', '_sys_form_favorite_input_caption_system_list', '_sys_form_favorite_input_caption_list', '', 1, 0, 0, '', '', '', 'Avail', '', '_Please select value', 'Xss', '', 1, 0),
('sys_favorite', 'system', 'new_list', '_sys_form_favorite_input_caption_button_new_list', '', 0, 'button', '', '', '', 0, 0, 0, 'a:1:{s:7:"onclick";s:25:"{js_object}.showNewList()";}', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_favorite', 'system', 'title', '', '', 0, 'text', '_sys_form_favorite_input_caption_system_title', '_sys_form_favorite_input_caption_title', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_favorite', 'system', 'allow_view_favorite_list_to', '', '', 0, 'custom', '_sys_form_favorite_input_caption_system_allow_view_to', '_sys_form_favorite_input_caption_allow_view_to', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_favorite', 'system', 'submit', '_sys_form_favorite_input_caption_submit', '', 0, 'submit', '_sys_form_favorite_input_caption_system_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_labels', 'system', 'name', '', '', 0, 'hidden', '_sys_form_labels_input_caption_system_name', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'action', '', '', 0, 'hidden', '_sys_form_labels_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'search', '', '', 0, 'custom', '_sys_form_labels_input_caption_system_search', '_sys_form_labels_input_caption_search', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'list', '', '', 0, 'custom', '_sys_form_labels_input_caption_system_list', '_sys_form_labels_input_caption_list', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'do_submit', '_sys_form_labels_input_caption_do_submit', '', 0, 'submit', '_sys_form_labels_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_labels', 'system', 'do_cancel', '_sys_form_labels_input_caption_do_cancel', '', 0, 'button', '_sys_form_labels_input_caption_system_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_inputs` SET `checker_func` = 'ProfileName' WHERE `object` = 'sys_account' AND `name` = 'name';

CREATE TABLE IF NOT EXISTS `sys_form_inputs_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `input_id` int(11) unsigned NOT NULL default '0',
  `author_id` int(11) unsigned NOT NULL default '0',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  UNIQUE KEY `input` (`input_id`,`author_id`)
);


UPDATE `sys_form_display_inputs` SET `order` = 2 WHERE `display_name` = 'sys_login_step3' AND `input_name` = 'do_checkcode' AND `order` = 3;
UPDATE `sys_form_display_inputs` SET `order` = 3 WHERE `display_name` = 'sys_login_step3' AND `input_name` = 'back' AND `order` = 2;

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_account_create' AND `input_name` = 'phone';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_account_create', 'phone', 2147483647, 0, 3);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_forgot_password_reset';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_forgot_password_reset', 'key', 2147483647, 1, 1),
('sys_forgot_password_reset', 'password', 2147483647, 1, 2),
('sys_forgot_password_reset', 'captcha', 2147483647, 1, 3),
('sys_forgot_password_reset', 'do_submit', 2147483647, 1, 4);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_comment_edit' AND `input_name` IN('cmt_controls', 'cmt_cancel');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_comment_edit', 'cmt_controls', 2147483647, 1, 8),
('sys_comment_edit', 'cmt_cancel', 2147483647, 1, 10);
UPDATE `sys_form_display_inputs` SET `order` = 9 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_submit';


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_favorite_add', 'sys_favorite_list_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_favorite_add', 'sys', 2147483647, 1, 1),
('sys_favorite_add', 'object_id', 2147483647, 1, 2),
('sys_favorite_add', 'action', 2147483647, 1, 3),
('sys_favorite_add', 'id', 2147483647, 0, 4),
('sys_favorite_add', 'list', 2147483647, 1, 5),
('sys_favorite_add', 'new_list', 2147483647, 1, 6),
('sys_favorite_add', 'title', 2147483647, 1, 7),
('sys_favorite_add', 'allow_view_favorite_list_to', 2147483647, 1, 8),
('sys_favorite_add', 'submit', 2147483647, 1, 9),

('sys_favorite_list_edit', 'sys', 2147483647, 1, 1),
('sys_favorite_list_edit', 'list_id', 2147483647, 1, 2),
('sys_favorite_list_edit', 'object_id', 2147483647, 1, 3),
('sys_favorite_list_edit', 'action', 2147483647, 1, 4),
('sys_favorite_list_edit', 'title', 2147483647, 1, 5),
('sys_favorite_list_edit', 'allow_view_favorite_list_to', 2147483647, 1, 6),
('sys_favorite_list_edit', 'submit', 2147483647, 1, 7);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_labels_select');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_labels_select', 'name', 2147483647, 1, 1),
('sys_labels_select', 'action', 2147483647, 1, 2),
('sys_labels_select', 'search', 2147483647, 1, 3),
('sys_labels_select', 'list', 2147483647, 1, 4),
('sys_labels_select', 'controls', 2147483647, 1, 5),
('sys_labels_select', 'do_submit', 2147483647, 1, 6),
('sys_labels_select', 'do_cancel', 2147483647, 1, 7);

CREATE TABLE IF NOT EXISTS `sys_form_fields_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_form` varchar(64) NOT NULL default '',
  `module` varchar(32) NOT NULL,
  `field_name` varchar(255) NOT NULL default '',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `author_id` int(10) NOT NULL DEFAULT '0',
  `nested_content_id` int(10) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rrate` float NOT NULL DEFAULT '0',
  `rvotes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `system_form_fields_id` (`object_form`, `content_id`, `nested_content_id`)
);

-- Predefined lists

DELETE FROM `sys_form_pre_lists` WHERE `key` = 'sys_studio_widget_types';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('sys_studio_widget_types', '_sys_pre_lists_studio_widget_types', 'system', '0', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_studio_widget_types';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_studio_widget_types', '', 1, '_sys_pre_lists_studio_widget_types_library', '', 'a:1:{s:4:"icon";s:15:"lmi-library.svg";}'),
('sys_studio_widget_types', 'appearance', 2, '_sys_pre_lists_studio_widget_types_appearance', '', 'a:1:{s:4:"icon";s:18:"lmi-appearance.svg";}'),
('sys_studio_widget_types', 'structure', 3, '_sys_pre_lists_studio_widget_types_structure', '', 'a:1:{s:4:"icon";s:17:"lmi-structure.svg";}'),
('sys_studio_widget_types', 'content', 4, '_sys_pre_lists_studio_widget_types_content', '', 'a:1:{s:4:"icon";s:15:"lmi-content.svg";}'),
('sys_studio_widget_types', 'users', 5, '_sys_pre_lists_studio_widget_types_users', '', 'a:1:{s:4:"icon";s:13:"lmi-users.svg";}'),
('sys_studio_widget_types', 'configuration', 6, '_sys_pre_lists_studio_widget_types_configuration', '', 'a:1:{s:4:"icon";s:21:"lmi-configuration.svg";}'),
('sys_studio_widget_types', 'extensions', 7, '_sys_pre_lists_studio_widget_types_extensions', '', 'a:1:{s:4:"icon";s:18:"lmi-extensions.svg";}'),
('sys_studio_widget_types', 'integrations', 8, '_sys_pre_lists_studio_widget_types_integrations', '', 'a:1:{s:4:"icon";s:20:"lmi-integrations.svg";}'),
('sys_studio_widget_types', 'favorites', 9, '_sys_pre_lists_studio_widget_types_favorites', '', 'a:1:{s:4:"icon";s:17:"lmi-favorites.svg";}');

-- Menus

SET @iMaxIdTemplate = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_menu_templates`);

UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 1 WHERE `id` = 18 AND `template` != 'menu_main_submenu_more_auto.html';
UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 2 WHERE `id` = 25 AND `template` != 'menu_block_submenu_hor.html';
UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 3 WHERE `id` = 26 AND `template` != 'menu_block_submenu_ver.html';

DELETE FROM `sys_menu_templates` WHERE `id` IN(18, 25, 26);
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(18, 'menu_main_submenu_more_auto.html', '_sys_menu_template_title_main_submenu_more_auto', 8),
(25, 'menu_block_submenu_hor.html', '_sys_menu_template_title_block_submenu_hor', 1),
(26, 'menu_block_submenu_ver.html', '_sys_menu_template_title_block_submenu_ver', 1);

DELETE FROM `sys_objects_menu` WHERE `object` IN ('sys_favorite_list', 'sys_studio_account_popup');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_favorite_list', '_sys_menu_title_favorite_list', 'sys_favorite_list', 'system', 9, 0, 1, '', ''),
('sys_studio_account_popup', '_sys_menu_title_studio_account_popup', 'sys_studio_account_popup', 'system', 4, 0, 1, 'BxTemplStudioMenuAccountPopup', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` IN('sys_favorite_list', 'sys_studio_account_popup');
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_favorite_list', 'system', '_sys_menu_set_title_sys_favorite_list', 0),
('sys_studio_account_popup', 'system', '_sys_menu_set_title_studio_account_popup', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `name` = 'more-auto';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_site', 'system', 'more-auto', '_sys_menu_item_title_system_more_auto', '_sys_menu_item_title_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', 2147483647, 1, 0, 9999);

UPDATE `sys_menu_items` SET `visible_for_levels` = 2147483646 WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'add-content' AND `visible_for_levels` = 510;
UPDATE `sys_menu_items` SET `visible_for_levels` = 2147483646 WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'account' AND `visible_for_levels` = 510;

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_manage' AND `name` IN('item-pin', 'item-unpin');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_cmts_item_manage', 'system', 'item-pin', '_sys_menu_item_title_system_cmts_item_pin', '_sys_menu_item_title_cmts_item_pin', 'javascript:void(0)', 'javascript:{js_object}.cmtPin(this, {content_id}, 1)', '_self', 'thumbtack', '', 2147483647, 1, 0, 0),
('sys_cmts_item_manage', 'system', 'item-unpin', '_sys_menu_item_title_system_cmts_item_unpin', '_sys_menu_item_title_cmts_item_unpin', 'javascript:void(0)', 'javascript:{js_object}.cmtPin(this, {content_id}, 0)', '_self', 'thumbtack', '', 2147483647, 1, 0, 0);

UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_popup(''sys_cmts_item_manage'', this, {''id'':''sys_cmts_item_manage_{cmt_system}_{cmt_id}'', ''removeOnClose'':1}, {cmt_system:''{cmt_system}'', cmt_object_id:{cmt_object_id}, cmt_id:{cmt_id}});' WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-more';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_favorite_list';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_favorite_list', 'system', 'edit', '', '_sys_menu_item_title_favorite_list_edit', 'javascript:void(0)', 'javascript:{js_object}.cmtEdit(this, {list_id})', '', 'edit', '', '', 0, 2147483646, '', 1, 0, 1, 1),
('sys_favorite_list', 'system', 'delete', '', '_sys_menu_item_title_wiki_favorite_list_delete', 'javascript:void(0)', 'javascript:{js_object}.cmtDelete(this, {list_id})', '', 'times', '', '', 0, 2147483646, '', 1, 0, 1, 2);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_studio_account_popup';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_studio_account_popup', 'system', 'account', '_sys_menu_item_title_system_sa_account', '_sys_menu_item_title_sa_account', '{member_url}', '', '', 'ami-account.svg', '', 2147483647, 1, 0, 0, 1),
('sys_studio_account_popup', 'system', 'edit', '_sys_menu_item_title_system_sa_edit', '_sys_menu_item_title_sa_edit', 'javascript:void(0)', '{js_object}.clickEdit(this);', '', 'ami-edit.svg', '', 2147483647, 1, 0, 0, 2),
('sys_studio_account_popup', 'system', 'language', '_sys_menu_item_title_system_sa_language', '_sys_menu_item_title_sa_language', 'javascript:void(0)', 'bx_menu_popup(''sys_switch_language_popup'', this);', '', 'ami-language.svg', '', 2147483647, 1, 0, 0, 3),
('sys_studio_account_popup', 'system', 'logout', '_sys_menu_item_title_system_sa_logout', '_sys_menu_item_title_sa_logout', '{url_root}logout.php', '{js_object}.clickLogout(this);', '', 'ami-logout.svg', '', 2147483647, 1, 0, 0, 4);

-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_studio_roles', 'sys_studio_roles_actions');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_roles', 'Sql', 'SELECT * FROM `sys_std_roles` WHERE 1 ', 'sys_std_roles', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'name', 'title,description', 'auto', '', '', 'BxTemplStudioRolesLevels', ''),
('sys_studio_roles_actions', 'Sql', 'SELECT *, ''0'' AS `active` FROM `sys_std_roles_actions` WHERE 1 ', 'sys_std_roles_actions', 'id', '', 'active', '', 20, NULL, 'start', '', 'name', 'title,description', 'auto', '', '', 'BxTemplStudioRolesActions', '');

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_studio_roles', 'sys_studio_roles_actions');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_roles', 'order', '', '1%', 0, 0, '', 1),
('sys_studio_roles', 'switcher', '', '5%', 0, 0, '', 2),
('sys_studio_roles', 'title', '_adm_rl_txt_title', '24%', 1, 0, '', 3),
('sys_studio_roles', 'description', '_adm_rl_txt_description', '35%', 1, 32, '', 4),
('sys_studio_roles', 'actions_list', '_adm_rl_txt_actions', '15%', 0, 0, '', 5),
('sys_studio_roles', 'actions', '', '20%', 0, 0, '', 6),

('sys_studio_roles_actions', 'switcher', '', '10%', 0, 0, '', 1),
('sys_studio_roles_actions', 'title', '_adm_rl_txt_title', '40%', 1, 32, '', 2),
('sys_studio_roles_actions', 'description', '_adm_rl_txt_description', '50%', 1, 48, '', 3);

DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_studio_roles');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_roles', 'independent', 'add', '_adm_rl_btn_role_add', '', 0, 0, 1),
('sys_studio_roles', 'single', 'edit', '_adm_rl_btn_role_edit', 'pencil-alt', 1, 0, 1),
('sys_studio_roles', 'single', 'delete', '_adm_rl_btn_role_delete', 'remove', 1, 1, 2);

-- Pages

DELETE FROM `sys_objects_page` WHERE `object` = 'sys_search_keyword';
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_search_keyword', 'search-keyword', '_sys_page_title_system_search_keyword', '_sys_page_title_search_keyword', 'system', 1, 5, 2147483647, 1, 'searchKeyword.php', '', '', '', 0, 1, 0, '', '');

CREATE TABLE IF NOT EXISTS `sys_pages_content_placeholders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_pages_content_placeholders`;

INSERT INTO `sys_pages_content_placeholders` (`id`, `module`, `title`, `template`, `order`) VALUES
(1, 'system', '_sys_page_content_ph_loading_indicator', 'block_async_loading_indicator.html', 1),
(2, 'system', '_sys_page_content_ph_text', 'block_async_text.html', 2),
(3, 'system', '_sys_page_content_ph_image', 'block_async_image.html', 3),
(4, 'system', '_sys_page_content_ph_create_post', 'block_async_create_post.html', 4),
(100, 'system', '_sys_page_content_ph_profile_units', 'block_async_profile_units.html', 100),
(110, 'system', '_sys_page_content_ph_text_units_list', 'block_async_text_units_list.html', 110),
(120, 'system', '_sys_page_content_ph_text_units_gallery', 'block_async_text_units_gallery.html', 120);

UPDATE `sys_pages_blocks` SET `designbox_id` = 11 WHERE `module` = 'system' AND `title_system` IN('_sys_page_block_title_sys_create_post', '_sys_page_block_title_sys_create_post_context', '_sys_page_block_title_sys_create_post_public');

UPDATE `sys_pages_blocks` SET `designbox_id` = 13 WHERE `object` = 'sys_forgot_password' AND `title` IN('_sys_page_block_title_forgot_password');

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_search_keyword' AND `title` IN('_sys_page_block_title_search_keyword_form', '_sys_page_block_title_search_keyword_result');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_search_keyword', 1, 'system', '', '_sys_page_block_title_search_keyword_form', 13, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:19:"search_keyword_form";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, 1),
('sys_search_keyword', 1, 'system', '', '_sys_page_block_title_search_keyword_result', 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"search_keyword_result";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, 1);

-- Logs objects

CREATE TABLE IF NOT EXISTS `sys_objects_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `logs_storage` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `class_name` varchar(255) NOT NULL,
  `class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_objects_logs`;

INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('sys_debug', 'system', 'Auto', '_sys_log_debug', 1, '', ''),
('sys_twilio', 'system', 'Auto', '_sys_log_twilio', 1, '', ''),
('sys_push', 'system', 'Auto', '_sys_log_push', 1, '', ''),
('sys_payments', 'system', 'Auto', '_sys_log_payments', 1, '', '');

-- Location field objects

CREATE TABLE IF NOT EXISTS `sys_objects_location_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_objects_location_field`;

INSERT INTO `sys_objects_location_field` (`object`, `module`, `title`, `class_name`, `class_file`) VALUES
('sys_google', 'system', '_sys_location_field_google', 'BxDolLocationFieldGoogle', ''),
('sys_plain', 'system', '_sys_location_field_plain', 'BxDolLocationFieldNominatim', '');

-- Location map objects

CREATE TABLE IF NOT EXISTS `sys_objects_location_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_objects_location_map`;

INSERT INTO `sys_objects_location_map` (`object`, `module`, `title`, `class_name`, `class_file`) VALUES
('sys_google_static', 'system', '_sys_location_map_google_static', 'BxDolLocationMapGoogleStatic', ''),
('sys_leaflet', 'system', '_sys_location_map_leaflet', 'BxDolLocationMapLeaflet', '');

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` IN('BxDolForm.js', 'BxDolNestedForm.js');
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'BxDolForm.js', 1, 42),
('system', 'js_system', 'BxDolNestedForm.js', 1, 43);

-- Studio roles

CREATE TABLE IF NOT EXISTS `sys_std_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  FULLTEXT KEY `searchable` (`title`, `description`)
);

TRUNCATE TABLE `sys_std_roles`;

INSERT INTO `sys_std_roles` (`id`, `name`, `title`, `description`, `active`, `order`) VALUES
(1, 'master', '_adm_rl_txt_role_master', '_adm_rl_txt_role_master_dsc', 1, 1),
(2, 'operator', '_adm_rl_txt_role_operator', '_adm_rl_txt_role_operator_dsc', 1, 2);

CREATE TABLE IF NOT EXISTS `sys_std_roles_actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `searchable` (`title`, `description`)
);

TRUNCATE TABLE `sys_std_roles_actions`;

CREATE TABLE IF NOT EXISTS `sys_std_roles_actions2roles` (  
  `role_id` int(11) unsigned NOT NULL default '0',
  `action_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`role_id`, `action_id`)
);

TRUNCATE TABLE `sys_std_roles_actions2roles`;

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('manage roles', '_adm_rl_txt_action_manage_roles', '_adm_rl_txt_action_manage_roles_dsc');
SET @iIdActionManageRoles = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('manage apps', '_adm_rl_txt_action_manage_apps', '_adm_rl_txt_action_manage_apps_dsc');
SET @iIdActionManageApps = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use appearance', '_adm_rl_txt_action_use_appearance', '_adm_rl_txt_action_use_appearance_dsc');
SET @iIdActionUseAppearance = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use structure', '_adm_rl_txt_action_use_structure', '_adm_rl_txt_action_use_structure_dsc');
SET @iIdActionUseStructure = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use content', '_adm_rl_txt_action_use_content', '_adm_rl_txt_action_use_content_dsc');
SET @iIdActionUseContent = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use users', '_adm_rl_txt_action_use_users', '_adm_rl_txt_action_use_users_dsc');
SET @iIdActionUseUsers = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use configuration', '_adm_rl_txt_action_use_configuration', '_adm_rl_txt_action_use_configuration_dsc');
SET @iIdActionUseConfiguration = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use extensions', '_adm_rl_txt_action_use_extensions', '_adm_rl_txt_action_use_extensions_dsc');
SET @iIdActionUseExtensions = LAST_INSERT_ID();

INSERT INTO `sys_std_roles_actions` (`name`, `title`, `description`) VALUES
('use integrations', '_adm_rl_txt_action_use_integrations', '_adm_rl_txt_action_use_integrations_dsc');
SET @iIdActionUseIntegrations = LAST_INSERT_ID();

SET @iMaster = 1;
SET @iOperator = 2;

INSERT INTO `sys_std_roles_actions2roles` (`role_id`, `action_id`) VALUES

-- manage roles
(@iMaster, @iIdActionManageRoles),
(@iMaster, @iIdActionManageApps),

-- use appearance
(@iMaster, @iIdActionUseAppearance),
(@iOperator, @iIdActionUseAppearance),

-- use structure
(@iMaster, @iIdActionUseStructure),
(@iOperator, @iIdActionUseStructure),

-- use content
(@iMaster, @iIdActionUseContent),
(@iOperator, @iIdActionUseContent),

-- use users
(@iMaster, @iIdActionUseUsers),
(@iOperator, @iIdActionUseUsers),

-- use configuration
(@iMaster, @iIdActionUseConfiguration),
(@iOperator, @iIdActionUseConfiguration),

-- use extensions
(@iMaster, @iIdActionUseExtensions),
(@iOperator, @iIdActionUseExtensions),

-- use integrations
(@iMaster, @iIdActionUseIntegrations),
(@iOperator, @iIdActionUseIntegrations);

CREATE TABLE IF NOT EXISTS `sys_std_roles_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) unsigned NOT NULL default '0',
  `role` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `account` (`account_id`)
);

-- Studio widgets

CREATE TABLE IF NOT EXISTS `sys_std_widgets_bookmarks` (
  `widget_id` int(11) unsigned NOT NULL default '0',
  `profile_id` int(11) unsigned NOT NULL default '0',
  `bookmark` tinyint(4) unsigned NOT NULL default '0',
  UNIQUE KEY `bookmark` (`widget_id`, `profile_id`)
);

UPDATE `sys_std_pages` SET `index` = 3 WHERE `name` = 'home';

-- Roles

TRUNCATE TABLE `sys_std_roles_members`;
INSERT INTO `sys_std_roles_members` (`account_id`, `role`) SELECT `id`, 1 FROM `sys_accounts` WHERE `role` = 3 AND `password` != '' AND `locked` = 0;


-- ==============================================================================
-- ========================= B2 =================================================
-- ==============================================================================

-- Forms

UPDATE `sys_form_inputs` SET `checker_func` = '', `checker_params` = '', `checker_error` = ''  WHERE `object` = 'sys_comment' AND `name` = 'cmt_text';

-- Menu 

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `name` = 'invoices';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'invoices', '_sys_menu_item_title_system_invoices', '_sys_menu_item_title_invoices', 'invoices.php', '', '', 'file-invoice col-green3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"get_invoices_count";s:6:"params";a:1:{i:0;s:6:"unpaid";}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 0, 1, 8);

UPDATE `sys_menu_items` SET `icon` = 'credit-card col-blue3' WHERE `set_name` = 'sys_account_dashboard' AND `name` = 'dashboard-subscriptions';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard' AND `name` = 'dashboard-invoices';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_dashboard', 'system', 'dashboard-invoices', '_sys_menu_item_title_system_invoices', '_sys_menu_item_title_invoices', 'invoices.php', '', '', 'file-invoice col-green3', '', '', 2147483646, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_meta' AND `name` = 'in-reply-to';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_meta', 'system', 'in-reply-to', '_sys_menu_item_title_system_sm_in_reply_to', '_sys_menu_item_title_sm_in_reply_to', '', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 1);

UPDATE `sys_menu_items` SET `order` = 0 WHERE `set_name` = 'sys_cmts_item_meta' AND `name` = 'author';

-- Live updates

DELETE FROM `sys_objects_live_updates` WHERE `name` = 'sys_payments_invoices';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('sys_payments_invoices', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_live_updates_invoices";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:8:"invoices";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` = 'jquery.ba-resize.min.js';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'jquery.ba-resize.min.js', 1, 25);

-- ==============================================================================
-- ========================= FINAL ==============================================
-- ==============================================================================


UPDATE `sys_options` SET `value` = 'stable' WHERE `name` = 'sys_upgrade_channel';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.0.0' WHERE (`version` = '11.0.4') AND `name` = 'system';
