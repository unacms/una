-- TABLE: sys_accounts

ALTER TABLE `sys_accounts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_accounts` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_accounts` CHANGE `email` `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_accounts` CHANGE `password` `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_accounts` CHANGE `salt` `salt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_accounts`;
OPTIMIZE TABLE `sys_accounts`;


-- TABLE: sys_acl_actions

ALTER TABLE `sys_acl_actions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_acl_actions` CHANGE `Module` `Module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_actions` CHANGE `Name` `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_actions` CHANGE `AdditionalParamName` `AdditionalParamName` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_actions` CHANGE `Title` `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_actions` CHANGE `Desc` `Desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_acl_actions`;
OPTIMIZE TABLE `sys_acl_actions`;


-- TABLE: sys_acl_actions_track

ALTER TABLE `sys_acl_actions_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_acl_actions_track`;
OPTIMIZE TABLE `sys_acl_actions_track`;


-- TABLE: sys_acl_levels

ALTER TABLE `sys_acl_levels` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_acl_levels` CHANGE `Name` `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_levels` CHANGE `Icon` `Icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_acl_levels` CHANGE `Description` `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_acl_levels`;
OPTIMIZE TABLE `sys_acl_levels`;


-- TABLE: sys_acl_levels_members

ALTER TABLE `sys_acl_levels_members` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_acl_levels_members` CHANGE `TransactionID` `TransactionID` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_acl_levels_members`;
OPTIMIZE TABLE `sys_acl_levels_members`;


-- TABLE: sys_acl_matrix

ALTER TABLE `sys_acl_matrix` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_acl_matrix` CHANGE `AdditionalParamValue` `AdditionalParamValue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_acl_matrix`;
OPTIMIZE TABLE `sys_acl_matrix`;


-- TABLE: sys_alerts

ALTER TABLE `sys_alerts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_alerts` CHANGE `unit` `unit` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_alerts` CHANGE `action` `action` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_alerts`;
OPTIMIZE TABLE `sys_alerts`;


-- TABLE: sys_alerts_handlers

ALTER TABLE `sys_alerts_handlers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_alerts_handlers` CHANGE `name` `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_alerts_handlers` CHANGE `class` `class` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_alerts_handlers` CHANGE `file` `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_alerts_handlers` CHANGE `service_call` `service_call` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_alerts_handlers`;
OPTIMIZE TABLE `sys_alerts_handlers`;


-- TABLE: sys_cmts_ids

ALTER TABLE `sys_cmts_ids` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_cmts_ids`;
OPTIMIZE TABLE `sys_cmts_ids`;


-- TABLE: sys_cmts_images

ALTER TABLE `sys_cmts_images` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_cmts_images` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_cmts_images`;
OPTIMIZE TABLE `sys_cmts_images`;


-- TABLE: sys_cmts_images2entries

ALTER TABLE `sys_cmts_images2entries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_cmts_images2entries`;
OPTIMIZE TABLE `sys_cmts_images2entries`;


-- TABLE: sys_cmts_images_preview

ALTER TABLE `sys_cmts_images_preview` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_cmts_images_preview` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images_preview` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images_preview` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images_preview` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cmts_images_preview` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_cmts_images_preview`;
OPTIMIZE TABLE `sys_cmts_images_preview`;


-- TABLE: sys_cmts_meta_keywords

ALTER TABLE `sys_cmts_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_cmts_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_cmts_meta_keywords`;
OPTIMIZE TABLE `sys_cmts_meta_keywords`;


-- TABLE: sys_cmts_meta_mentions

ALTER TABLE `sys_cmts_meta_mentions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_cmts_meta_mentions`;
OPTIMIZE TABLE `sys_cmts_meta_mentions`;


-- TABLE: sys_cmts_votes

ALTER TABLE `sys_cmts_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_cmts_votes`;
OPTIMIZE TABLE `sys_cmts_votes`;


-- TABLE: sys_cmts_votes_track

ALTER TABLE `sys_cmts_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_cmts_votes_track`;
OPTIMIZE TABLE `sys_cmts_votes_track`;


-- TABLE: sys_content_info_grids

ALTER TABLE `sys_content_info_grids` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_content_info_grids` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_content_info_grids` CHANGE `grid_object` `grid_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_content_info_grids` CHANGE `grid_field_id` `grid_field_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_content_info_grids` CHANGE `condition` `condition` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_content_info_grids` CHANGE `selection` `selection` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_content_info_grids`;
OPTIMIZE TABLE `sys_content_info_grids`;


-- TABLE: sys_cron_jobs

ALTER TABLE `sys_cron_jobs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_cron_jobs` CHANGE `name` `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cron_jobs` CHANGE `time` `time` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cron_jobs` CHANGE `class` `class` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cron_jobs` CHANGE `file` `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_cron_jobs` CHANGE `service_call` `service_call` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_cron_jobs`;
OPTIMIZE TABLE `sys_cron_jobs`;


-- TABLE: sys_email_templates

ALTER TABLE `sys_email_templates` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_email_templates` CHANGE `Module` `Module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_email_templates` CHANGE `NameSystem` `NameSystem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_email_templates` CHANGE `Name` `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_email_templates` CHANGE `Subject` `Subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_email_templates` CHANGE `Body` `Body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_email_templates`;
OPTIMIZE TABLE `sys_email_templates`;


-- TABLE: sys_files

ALTER TABLE `sys_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_files`;
OPTIMIZE TABLE `sys_files`;


-- TABLE: sys_form_display_inputs

ALTER TABLE `sys_form_display_inputs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_form_display_inputs` CHANGE `display_name` `display_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_display_inputs` CHANGE `input_name` `input_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_form_display_inputs`;
OPTIMIZE TABLE `sys_form_display_inputs`;


-- TABLE: sys_form_displays

ALTER TABLE `sys_form_displays` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_form_displays` CHANGE `display_name` `display_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_displays` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_displays` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_displays` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_form_displays`;
OPTIMIZE TABLE `sys_form_displays`;


-- TABLE: sys_form_inputs

ALTER TABLE `sys_form_inputs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_form_inputs` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `value` `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `values` `values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `caption_system` `caption_system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `info` `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `attrs` `attrs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `attrs_tr` `attrs_tr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `attrs_wrapper` `attrs_wrapper` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `checker_func` `checker_func` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `checker_params` `checker_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `checker_error` `checker_error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `db_pass` `db_pass` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_inputs` CHANGE `db_params` `db_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_form_inputs`;
OPTIMIZE TABLE `sys_form_inputs`;


-- TABLE: sys_form_pre_lists

ALTER TABLE `sys_form_pre_lists` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_form_pre_lists` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_pre_lists` CHANGE `key` `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_pre_lists` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_form_pre_lists`;
OPTIMIZE TABLE `sys_form_pre_lists`;


-- TABLE: sys_form_pre_values

ALTER TABLE `sys_form_pre_values` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_form_pre_values` CHANGE `Key` `Key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_pre_values` CHANGE `Value` `Value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_pre_values` CHANGE `LKey` `LKey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_form_pre_values` CHANGE `LKey2` `LKey2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_form_pre_values`;
OPTIMIZE TABLE `sys_form_pre_values`;


-- TABLE: sys_grid_actions

ALTER TABLE `sys_grid_actions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_grid_actions` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_actions` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_actions` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_actions` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_grid_actions`;
OPTIMIZE TABLE `sys_grid_actions`;


-- TABLE: sys_grid_fields

ALTER TABLE `sys_grid_fields` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_grid_fields` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_fields` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_fields` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_fields` CHANGE `width` `width` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_grid_fields` CHANGE `params` `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_grid_fields`;
OPTIMIZE TABLE `sys_grid_fields`;


-- TABLE: sys_images

ALTER TABLE `sys_images` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_images` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_images`;
OPTIMIZE TABLE `sys_images`;


-- TABLE: sys_images_custom

ALTER TABLE `sys_images_custom` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_images_custom` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_custom` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_custom` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_custom` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_custom` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_images_custom`;
OPTIMIZE TABLE `sys_images_custom`;


-- TABLE: sys_images_resized

ALTER TABLE `sys_images_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_images_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_images_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_images_resized`;
OPTIMIZE TABLE `sys_images_resized`;


-- TABLE: sys_injections

ALTER TABLE `sys_injections` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_injections` CHANGE `name` `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_injections` CHANGE `key` `key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_injections` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_injections`;
OPTIMIZE TABLE `sys_injections`;


-- TABLE: sys_injections_admin

ALTER TABLE `sys_injections_admin` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_injections_admin` CHANGE `name` `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_injections_admin` CHANGE `key` `key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_injections_admin` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_injections_admin`;
OPTIMIZE TABLE `sys_injections_admin`;


-- TABLE: sys_keys

ALTER TABLE `sys_keys` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_keys` CHANGE `key` `key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_keys` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_keys`;
OPTIMIZE TABLE `sys_keys`;


-- TABLE: sys_localization_categories

ALTER TABLE `sys_localization_categories` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_localization_categories` CHANGE `Name` `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_localization_categories`;
OPTIMIZE TABLE `sys_localization_categories`;


-- TABLE: sys_localization_keys

ALTER TABLE `sys_localization_keys` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

ALTER TABLE `sys_localization_keys` CHANGE `Key` `Key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

REPAIR TABLE `sys_localization_keys`;
OPTIMIZE TABLE `sys_localization_keys`;


-- TABLE: sys_localization_languages

ALTER TABLE `sys_localization_languages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_localization_languages` CHANGE `Name` `Name` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_localization_languages` CHANGE `Flag` `Flag` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_localization_languages` CHANGE `Title` `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_localization_languages` CHANGE `LanguageCountry` `LanguageCountry` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_localization_languages`;
OPTIMIZE TABLE `sys_localization_languages`;


-- TABLE: sys_localization_strings

ALTER TABLE `sys_localization_strings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_localization_strings` CHANGE `String` `String` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_localization_strings`;
OPTIMIZE TABLE `sys_localization_strings`;


-- TABLE: sys_menu_items

ALTER TABLE `sys_menu_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_menu_items` CHANGE `set_name` `set_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `title_system` `title_system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `link` `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `onclick` `onclick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `target` `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `addon` `addon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_items` CHANGE `submenu_object` `submenu_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_menu_items`;
OPTIMIZE TABLE `sys_menu_items`;


-- TABLE: sys_menu_sets

ALTER TABLE `sys_menu_sets` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_menu_sets` CHANGE `set_name` `set_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_sets` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_sets` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_menu_sets`;
OPTIMIZE TABLE `sys_menu_sets`;


-- TABLE: sys_menu_templates

ALTER TABLE `sys_menu_templates` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_menu_templates` CHANGE `template` `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_menu_templates` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_menu_templates`;
OPTIMIZE TABLE `sys_menu_templates`;


-- TABLE: sys_modules

ALTER TABLE `sys_modules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_modules` CHANGE `type` `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `name` `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `vendor` `vendor` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `version` `version` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `help_url` `help_url` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `uri` `uri` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `class_prefix` `class_prefix` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `db_prefix` `db_prefix` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `lang_category` `lang_category` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `dependencies` `dependencies` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules` CHANGE `hash` `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_modules`;
OPTIMIZE TABLE `sys_modules`;


-- TABLE: sys_modules_file_tracks

ALTER TABLE `sys_modules_file_tracks` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_modules_file_tracks` CHANGE `file` `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules_file_tracks` CHANGE `hash` `hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_modules_file_tracks`;
OPTIMIZE TABLE `sys_modules_file_tracks`;


-- TABLE: sys_modules_relations

ALTER TABLE `sys_modules_relations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_modules_relations` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules_relations` CHANGE `on_install` `on_install` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules_relations` CHANGE `on_uninstall` `on_uninstall` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules_relations` CHANGE `on_enable` `on_enable` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_modules_relations` CHANGE `on_disable` `on_disable` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_modules_relations`;
OPTIMIZE TABLE `sys_modules_relations`;


-- TABLE: sys_objects_auths

ALTER TABLE `sys_objects_auths` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_auths` CHANGE `Name` `Name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_auths` CHANGE `Title` `Title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_auths` CHANGE `Link` `Link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_auths` CHANGE `OnClick` `OnClick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_auths` CHANGE `Icon` `Icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_auths`;
OPTIMIZE TABLE `sys_objects_auths`;


-- TABLE: sys_objects_captcha

ALTER TABLE `sys_objects_captcha` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_captcha` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_captcha` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_captcha` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_captcha` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_captcha`;
OPTIMIZE TABLE `sys_objects_captcha`;


-- TABLE: sys_objects_category

ALTER TABLE `sys_objects_category` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_category` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `search_object` `search_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `form_object` `form_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `list_name` `list_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `field` `field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `join` `join` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `where` `where` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_category` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_category`;
OPTIMIZE TABLE `sys_objects_category`;


-- TABLE: sys_objects_chart

ALTER TABLE `sys_objects_chart` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_chart` CHANGE `object` `object` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `field_date_ts` `field_date_ts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `field_date_dt` `field_date_dt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `field_status` `field_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `type` `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `options` `options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `query` `query` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_chart` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_chart`;
OPTIMIZE TABLE `sys_objects_chart`;


-- TABLE: sys_objects_cmts

ALTER TABLE `sys_objects_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_cmts` CHANGE `Name` `Name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `Module` `Module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `Table` `Table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `BrowseType` `BrowseType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `PostFormPosition` `PostFormPosition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `RootStylePrefix` `RootStylePrefix` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `BaseUrl` `BaseUrl` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `ObjectVote` `ObjectVote` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `TriggerTable` `TriggerTable` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `TriggerFieldId` `TriggerFieldId` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `TriggerFieldAuthor` `TriggerFieldAuthor` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `TriggerFieldTitle` `TriggerFieldTitle` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `TriggerFieldComments` `TriggerFieldComments` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `ClassName` `ClassName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_cmts` CHANGE `ClassFile` `ClassFile` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_cmts`;
OPTIMIZE TABLE `sys_objects_cmts`;


-- TABLE: sys_objects_connection

ALTER TABLE `sys_objects_connection` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_connection` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_connection` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_connection` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_connection` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_connection`;
OPTIMIZE TABLE `sys_objects_connection`;


-- TABLE: sys_objects_content_info

ALTER TABLE `sys_objects_content_info` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_content_info` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `title` `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `alert_unit` `alert_unit` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `alert_action_add` `alert_action_add` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `alert_action_update` `alert_action_update` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `alert_action_delete` `alert_action_delete` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_content_info` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_content_info`;
OPTIMIZE TABLE `sys_objects_content_info`;


-- TABLE: sys_objects_editor

ALTER TABLE `sys_objects_editor` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_editor` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_editor` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_editor` CHANGE `skin` `skin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_editor` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_editor` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_editor`;
OPTIMIZE TABLE `sys_objects_editor`;


-- TABLE: sys_objects_favorite

ALTER TABLE `sys_objects_favorite` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_favorite` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `table_track` `table_track` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `base_url` `base_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `trigger_table` `trigger_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `trigger_field_id` `trigger_field_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `trigger_field_author` `trigger_field_author` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `trigger_field_count` `trigger_field_count` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_favorite` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_favorite`;
OPTIMIZE TABLE `sys_objects_favorite`;


-- TABLE: sys_objects_feature

ALTER TABLE `sys_objects_feature` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_feature` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `base_url` `base_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `trigger_table` `trigger_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `trigger_field_id` `trigger_field_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `trigger_field_author` `trigger_field_author` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `trigger_field_flag` `trigger_field_flag` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_feature` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_feature`;
OPTIMIZE TABLE `sys_objects_feature`;


-- TABLE: sys_objects_file_handlers

ALTER TABLE `sys_objects_file_handlers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_file_handlers` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_file_handlers` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_file_handlers` CHANGE `preg_ext` `preg_ext` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_file_handlers` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_file_handlers` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_file_handlers`;
OPTIMIZE TABLE `sys_objects_file_handlers`;


-- TABLE: sys_objects_form

ALTER TABLE `sys_objects_form` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_form` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `action` `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `form_attrs` `form_attrs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `submit_name` `submit_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `key` `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `uri` `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `uri_title` `uri_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `params` `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_form` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_form`;
OPTIMIZE TABLE `sys_objects_form`;


-- TABLE: sys_objects_grid

ALTER TABLE `sys_objects_grid` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_grid` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `source` `source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `field_id` `field_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `field_order` `field_order` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `field_active` `field_active` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `order_get_field` `order_get_field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `order_get_dir` `order_get_dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `paginate_url` `paginate_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `paginate_simple` `paginate_simple` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `paginate_get_start` `paginate_get_start` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `paginate_get_per_page` `paginate_get_per_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `filter_fields` `filter_fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `filter_fields_translatable` `filter_fields_translatable` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `filter_get` `filter_get` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `sorting_fields` `sorting_fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `sorting_fields_translatable` `sorting_fields_translatable` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_grid` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_grid`;
OPTIMIZE TABLE `sys_objects_grid`;


-- TABLE: sys_objects_live_updates

ALTER TABLE `sys_objects_live_updates` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_live_updates` CHANGE `name` `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_live_updates` CHANGE `service_call` `service_call` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_live_updates`;
OPTIMIZE TABLE `sys_objects_live_updates`;


-- TABLE: sys_objects_menu

ALTER TABLE `sys_objects_menu` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_menu` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_menu` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_menu` CHANGE `set_name` `set_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_menu` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_menu` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_menu` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_menu`;
OPTIMIZE TABLE `sys_objects_menu`;


-- TABLE: sys_objects_metatags

ALTER TABLE `sys_objects_metatags` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_metatags` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_metatags` CHANGE `table_keywords` `table_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_metatags` CHANGE `table_locations` `table_locations` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_metatags` CHANGE `table_mentions` `table_mentions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_metatags` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_metatags` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_metatags`;
OPTIMIZE TABLE `sys_objects_metatags`;


-- TABLE: sys_objects_page

ALTER TABLE `sys_objects_page` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_page` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `uri` `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `title_system` `title_system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `url` `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `meta_description` `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `meta_keywords` `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `meta_robots` `meta_robots` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_page` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_page`;
OPTIMIZE TABLE `sys_objects_page`;


-- TABLE: sys_objects_payments

ALTER TABLE `sys_objects_payments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_payments` CHANGE `object` `object` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_payments` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_payments` CHANGE `uri` `uri` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_payments`;
OPTIMIZE TABLE `sys_objects_payments`;


-- TABLE: sys_objects_privacy

ALTER TABLE `sys_objects_privacy` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_privacy` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `module` `module` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `action` `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `default_group` `default_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `table` `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `table_field_id` `table_field_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `table_field_author` `table_field_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_privacy` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_privacy`;
OPTIMIZE TABLE `sys_objects_privacy`;


-- TABLE: sys_objects_report

ALTER TABLE `sys_objects_report` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_report` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `table_main` `table_main` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `table_track` `table_track` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `base_url` `base_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `trigger_table` `trigger_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `trigger_field_id` `trigger_field_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `trigger_field_author` `trigger_field_author` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `trigger_field_count` `trigger_field_count` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_report` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_report`;
OPTIMIZE TABLE `sys_objects_report`;


-- TABLE: sys_objects_rss

ALTER TABLE `sys_objects_rss` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_rss` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_rss` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_rss` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_rss`;
OPTIMIZE TABLE `sys_objects_rss`;


-- TABLE: sys_objects_search

ALTER TABLE `sys_objects_search` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_search` CHANGE `ObjectName` `ObjectName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search` CHANGE `Title` `Title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search` CHANGE `ClassName` `ClassName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search` CHANGE `ClassPath` `ClassPath` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_search`;
OPTIMIZE TABLE `sys_objects_search`;


-- TABLE: sys_objects_search_extended

ALTER TABLE `sys_objects_search_extended` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_search_extended` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search_extended` CHANGE `object_content_info` `object_content_info` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search_extended` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search_extended` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search_extended` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_search_extended` CHANGE `class_file` `class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_search_extended`;
OPTIMIZE TABLE `sys_objects_search_extended`;


-- TABLE: sys_objects_storage

ALTER TABLE `sys_objects_storage` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_storage` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_storage` CHANGE `engine` `engine` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_storage` CHANGE `params` `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_storage` CHANGE `table_files` `table_files` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_storage` CHANGE `ext_allow` `ext_allow` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_storage` CHANGE `ext_deny` `ext_deny` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_storage`;
OPTIMIZE TABLE `sys_objects_storage`;


-- TABLE: sys_objects_transcoder

ALTER TABLE `sys_objects_transcoder` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_transcoder` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_transcoder` CHANGE `storage_object` `storage_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_transcoder` CHANGE `source_params` `source_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_transcoder` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_transcoder` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_transcoder`;
OPTIMIZE TABLE `sys_objects_transcoder`;


-- TABLE: sys_objects_uploader

ALTER TABLE `sys_objects_uploader` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_uploader` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_uploader` CHANGE `override_class_name` `override_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_uploader` CHANGE `override_class_file` `override_class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_uploader`;
OPTIMIZE TABLE `sys_objects_uploader`;


-- TABLE: sys_objects_view

ALTER TABLE `sys_objects_view` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_view` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `table_track` `table_track` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `trigger_table` `trigger_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `trigger_field_id` `trigger_field_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `trigger_field_author` `trigger_field_author` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `trigger_field_count` `trigger_field_count` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `class_name` `class_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_view` CHANGE `class_file` `class_file` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_view`;
OPTIMIZE TABLE `sys_objects_view`;


-- TABLE: sys_objects_vote

ALTER TABLE `sys_objects_vote` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_objects_vote` CHANGE `Name` `Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TableMain` `TableMain` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TableTrack` `TableTrack` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TriggerTable` `TriggerTable` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TriggerFieldId` `TriggerFieldId` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TriggerFieldAuthor` `TriggerFieldAuthor` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TriggerFieldRate` `TriggerFieldRate` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `TriggerFieldRateCount` `TriggerFieldRateCount` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `ClassName` `ClassName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_objects_vote` CHANGE `ClassFile` `ClassFile` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_objects_vote`;
OPTIMIZE TABLE `sys_objects_vote`;


-- TABLE: sys_options

ALTER TABLE `sys_options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_options` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `value` `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `extra` `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `check` `check` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `check_params` `check_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options` CHANGE `check_error` `check_error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_options`;
OPTIMIZE TABLE `sys_options`;


-- TABLE: sys_options_categories

ALTER TABLE `sys_options_categories` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_options_categories` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_categories` CHANGE `caption` `caption` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_options_categories`;
OPTIMIZE TABLE `sys_options_categories`;


-- TABLE: sys_options_mixes

ALTER TABLE `sys_options_mixes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_options_mixes` CHANGE `type` `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_mixes` CHANGE `category` `category` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_mixes` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_mixes` CHANGE `title` `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_options_mixes`;
OPTIMIZE TABLE `sys_options_mixes`;


-- TABLE: sys_options_mixes2options

ALTER TABLE `sys_options_mixes2options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_options_mixes2options` CHANGE `option` `option` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_mixes2options` CHANGE `value` `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_options_mixes2options`;
OPTIMIZE TABLE `sys_options_mixes2options`;


-- TABLE: sys_options_types

ALTER TABLE `sys_options_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_options_types` CHANGE `group` `group` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_types` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_types` CHANGE `caption` `caption` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_options_types` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_options_types`;
OPTIMIZE TABLE `sys_options_types`;


-- TABLE: sys_pages_blocks

ALTER TABLE `sys_pages_blocks` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_pages_blocks` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_blocks` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_blocks` CHANGE `title_system` `title_system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_blocks` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_blocks` CHANGE `hidden_on` `hidden_on` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_blocks` CHANGE `content` `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_pages_blocks`;
OPTIMIZE TABLE `sys_pages_blocks`;


-- TABLE: sys_pages_design_boxes

ALTER TABLE `sys_pages_design_boxes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_pages_design_boxes` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_design_boxes` CHANGE `template` `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_pages_design_boxes`;
OPTIMIZE TABLE `sys_pages_design_boxes`;


-- TABLE: sys_pages_layouts

ALTER TABLE `sys_pages_layouts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_pages_layouts` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_layouts` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_layouts` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_pages_layouts` CHANGE `template` `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_pages_layouts`;
OPTIMIZE TABLE `sys_pages_layouts`;


-- TABLE: sys_permalinks

ALTER TABLE `sys_permalinks` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_permalinks` CHANGE `standard` `standard` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_permalinks` CHANGE `permalink` `permalink` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_permalinks` CHANGE `check` `check` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_permalinks`;
OPTIMIZE TABLE `sys_permalinks`;


-- TABLE: sys_privacy_defaults

ALTER TABLE `sys_privacy_defaults` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_privacy_defaults`;
OPTIMIZE TABLE `sys_privacy_defaults`;


-- TABLE: sys_privacy_groups

ALTER TABLE `sys_privacy_groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_privacy_groups` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_privacy_groups` CHANGE `check` `check` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_privacy_groups`;
OPTIMIZE TABLE `sys_privacy_groups`;


-- TABLE: sys_profiles

ALTER TABLE `sys_profiles` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_profiles` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_profiles`;
OPTIMIZE TABLE `sys_profiles`;


-- TABLE: sys_profiles_conn_friends

ALTER TABLE `sys_profiles_conn_friends` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_profiles_conn_friends`;
OPTIMIZE TABLE `sys_profiles_conn_friends`;


-- TABLE: sys_profiles_conn_subscriptions

ALTER TABLE `sys_profiles_conn_subscriptions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_profiles_conn_subscriptions`;
OPTIMIZE TABLE `sys_profiles_conn_subscriptions`;


-- TABLE: sys_queue_email

ALTER TABLE `sys_queue_email` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_queue_email` CHANGE `email` `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_queue_email` CHANGE `subject` `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_queue_email` CHANGE `body` `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_queue_email` CHANGE `headers` `headers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_queue_email` CHANGE `params` `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_queue_email`;
OPTIMIZE TABLE `sys_queue_email`;


-- TABLE: sys_queue_push

ALTER TABLE `sys_queue_push` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_queue_push` CHANGE `message` `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_queue_push`;
OPTIMIZE TABLE `sys_queue_push`;


-- TABLE: sys_search_extended_fields

ALTER TABLE `sys_search_extended_fields` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_search_extended_fields` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `values` `values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `pass` `pass` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `search_type` `search_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `search_value` `search_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_search_extended_fields` CHANGE `search_operator` `search_operator` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_search_extended_fields`;
OPTIMIZE TABLE `sys_search_extended_fields`;


-- TABLE: sys_sessions

ALTER TABLE `sys_sessions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_sessions` CHANGE `id` `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_sessions` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_sessions`;
OPTIMIZE TABLE `sys_sessions`;


-- TABLE: sys_statistics

ALTER TABLE `sys_statistics` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_statistics` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_statistics` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_statistics` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_statistics` CHANGE `link` `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_statistics` CHANGE `icon` `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_statistics` CHANGE `query` `query` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_statistics`;
OPTIMIZE TABLE `sys_statistics`;


-- TABLE: sys_std_pages

ALTER TABLE `sys_std_pages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_std_pages` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_pages` CHANGE `header` `header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_pages` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_pages` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_std_pages`;
OPTIMIZE TABLE `sys_std_pages`;


-- TABLE: sys_std_pages_widgets

ALTER TABLE `sys_std_pages_widgets` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_std_pages_widgets`;
OPTIMIZE TABLE `sys_std_pages_widgets`;


-- TABLE: sys_std_widgets

ALTER TABLE `sys_std_widgets` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_std_widgets` CHANGE `page_id` `page_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `module` `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `url` `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `click` `click` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `cnt_notices` `cnt_notices` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_std_widgets` CHANGE `cnt_actions` `cnt_actions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_std_widgets`;
OPTIMIZE TABLE `sys_std_widgets`;


-- TABLE: sys_storage_deletions

ALTER TABLE `sys_storage_deletions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_storage_deletions` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_storage_deletions`;
OPTIMIZE TABLE `sys_storage_deletions`;


-- TABLE: sys_storage_ghosts

ALTER TABLE `sys_storage_ghosts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_storage_ghosts` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_storage_ghosts`;
OPTIMIZE TABLE `sys_storage_ghosts`;


-- TABLE: sys_storage_mime_types

ALTER TABLE `sys_storage_mime_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_storage_mime_types` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_storage_mime_types` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_storage_mime_types` CHANGE `icon` `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_storage_mime_types` CHANGE `icon_font` `icon_font` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_storage_mime_types`;
OPTIMIZE TABLE `sys_storage_mime_types`;


-- TABLE: sys_storage_tokens

ALTER TABLE `sys_storage_tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_storage_tokens` CHANGE `object` `object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_storage_tokens` CHANGE `hash` `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_storage_tokens`;
OPTIMIZE TABLE `sys_storage_tokens`;


-- TABLE: sys_storage_user_quotas

ALTER TABLE `sys_storage_user_quotas` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `sys_storage_user_quotas`;
OPTIMIZE TABLE `sys_storage_user_quotas`;


-- TABLE: sys_transcoder_filters

ALTER TABLE `sys_transcoder_filters` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_transcoder_filters` CHANGE `transcoder_object` `transcoder_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_filters` CHANGE `filter` `filter` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_filters` CHANGE `filter_params` `filter_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_transcoder_filters`;
OPTIMIZE TABLE `sys_transcoder_filters`;


-- TABLE: sys_transcoder_images_files

ALTER TABLE `sys_transcoder_images_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_transcoder_images_files` CHANGE `transcoder_object` `transcoder_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_images_files` CHANGE `handler` `handler` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_images_files` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_transcoder_images_files`;
OPTIMIZE TABLE `sys_transcoder_images_files`;


-- TABLE: sys_transcoder_queue

ALTER TABLE `sys_transcoder_queue` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_transcoder_queue` CHANGE `transcoder_object` `transcoder_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `file_url_source` `file_url_source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `file_id_source` `file_id_source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `file_url_result` `file_url_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `file_ext_result` `file_ext_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `server` `server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue` CHANGE `log` `log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_transcoder_queue`;
OPTIMIZE TABLE `sys_transcoder_queue`;


-- TABLE: sys_transcoder_queue_files

ALTER TABLE `sys_transcoder_queue_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_transcoder_queue_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_queue_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_transcoder_queue_files`;
OPTIMIZE TABLE `sys_transcoder_queue_files`;


-- TABLE: sys_transcoder_videos_files

ALTER TABLE `sys_transcoder_videos_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sys_transcoder_videos_files` CHANGE `transcoder_object` `transcoder_object` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `sys_transcoder_videos_files` CHANGE `handler` `handler` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `sys_transcoder_videos_files`;
OPTIMIZE TABLE `sys_transcoder_videos_files`;


