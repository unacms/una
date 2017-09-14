--
-- Database: v 9.0
--

-- --------------------------------------------------------

SET NAMES 'utf8';
DROP TABLE IF EXISTS `sys_keys`, `sys_objects_editor`, `sys_objects_file_handlers`, `sys_objects_captcha`, `sys_objects_cmts`, `sys_cmts_images`, `sys_cmts_images_preview`, `sys_cmts_images2entries`, `sys_cmts_ids`, `sys_cmts_meta_keywords`, `sys_cmts_votes`, `sys_cmts_votes_track`, `sys_email_templates`, `sys_options`, sys_options_types, `sys_options_categories`, `sys_options_mixes`, `sys_options_mixes2options`,  `sys_localization_categories`, `sys_localization_keys`, `sys_localization_languages`, `sys_localization_strings`, `sys_acl_actions`, `sys_acl_actions_track`, `sys_acl_matrix`, `sys_acl_levels`, `sys_sessions`, `sys_acl_levels_members`, `sys_objects_rss`, `sys_objects_search`, `sys_objects_search_extended`, `sys_search_extended_fields`, `sys_statistics`, `sys_alerts`, `sys_alerts_handlers`, `sys_injections`, `sys_injections_admin`, `sys_modules`, `sys_modules_file_tracks`, `sys_modules_relations`, `sys_permalinks`, `sys_objects_privacy`, `sys_privacy_defaults`, `sys_privacy_groups`, `sys_objects_auths`, `sys_objects_vote`, `sys_objects_report`, `sys_objects_view`, `sys_objects_favorite`, `sys_objects_feature`, `sys_objects_chart`, `sys_objects_content_info`, `sys_content_info_grids`, `sys_cron_jobs`, `sys_objects_storage`, `sys_objects_uploader`, `sys_storage_user_quotas`, `sys_storage_tokens`, `sys_storage_ghosts`, `sys_storage_deletions`, `sys_storage_mime_types`, `sys_objects_transcoder`, `sys_transcoder_images_files`, `sys_transcoder_videos_files`, `sys_transcoder_filters`, `sys_transcoder_queue`, `sys_transcoder_queue_files`, `sys_accounts`, `sys_profiles`, `sys_objects_form`, `sys_form_displays`, `sys_form_inputs`, `sys_form_display_inputs`, `sys_form_pre_lists`, `sys_form_pre_values`, `sys_menu_templates`, `sys_objects_menu`, `sys_menu_sets`, `sys_menu_items`, `sys_objects_grid`, `sys_grid_fields`, `sys_grid_actions`, `sys_objects_connection`, `sys_profiles_conn_subscriptions`, `sys_profiles_conn_friends`, `sys_objects_page`, `sys_pages_layouts`, `sys_pages_design_boxes`, `sys_pages_blocks`, `sys_objects_metatags`, `sys_objects_category`, `sys_objects_live_updates`, `sys_objects_payments`, `sys_files`, `sys_images`, `sys_images_custom`, `sys_images_resized`, `sys_std_pages`, `sys_std_widgets`, `sys_std_pages_widgets`;

ALTER DATABASE DEFAULT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';


-- --------------------------------------------------------


CREATE TABLE `sys_keys` (
  `key` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `expire` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_objects_editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `skin` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `sys_objects_editor` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('sys_tinymce', 'TinyMCE', 'lightgray', 'BxTemplEditorTinyMCE', '');


-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_file_handlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `preg_ext` text NOT NULL,
  `active` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_file_handlers` (`object`, `title`, `preg_ext`, `active`, `order`, `override_class_name`, `override_class_file`) VALUES
('sys_ms_viewer', '_sys_file_handlers_ms_viewer', '/\\.(doc|docx|xls|xlsx|ppt|pptx)$/i', 1, 1, 'BxTemplFileHandlerMsViewer', ''),
('sys_google_viewer', '_sys_file_handlers_google_viewer', '/\\.(pdf|doc|docx|xls|xlsx|ppt|pptx|ai|svg|ps|tif|tiff)$/i', 1, 2, 'BxTemplFileHandlerGoogleViewer', ''),
('sys_images_viewer', '_sys_file_handlers_images_viewer', '/\\.(jpg|jpeg|png|gif|webp)$/i', 1, 3, 'BxTemplFileHandlerImagesViewer', ''),
('sys_code_viewer', '_sys_file_handlers_code_viewer', '/\\.(1st|aspx|asp|json|js|jsp|java|php|xml|html|htm|rdf|xsd|xsl|xslt|sax|rss|cfm|js|asm|pl|prl|bas|b|vbs|fs|src|cs|ws|cgi|bat|py|c|cpp|cc|cp|h|hh|cxx|hxx|c++|m|lua|swift|sh|as|cob|tpl|lsp|x|cmd|rb|cbl|pas|pp|vb|f|perl|jl|lol|bal|pli|css|less|sass|saas|bcc|coffee|jade|j|tea|c#|sas|diff|pro|for|sh|bsh|bash|twig|csh|lisp|lsp|cobol|pl|d|git|rb|hrl|cr|inp|a|go|as3|m|sql|md|txt|csv)$/i', 1, 4, 'BxTemplFileHandlerCodeViewer', '');


-- --------------------------------------------------------


CREATE TABLE `sys_objects_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_captcha` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_recaptcha', 'reCAPTCHA', 'BxTemplCaptchaReCAPTCHA', ''),
('sys_recaptcha_new', 'reCAPTCHA New', 'BxTemplCaptchaReCAPTCHANew', '');


-- --------------------------------------------------------


CREATE TABLE `sys_objects_auths` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(64) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Link` varchar(255) NOT NULL,
  `OnClick` varchar(255) NOT NULL,
  `Icon` varchar(64) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `sys_objects_cmts`
--

CREATE TABLE `sys_objects_cmts` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(64) NOT NULL,
  `Module` varchar(32) NOT NULL,
  `Table` varchar(50) NOT NULL,
  `CharsPostMin` int(10) NOT NULL,
  `CharsPostMax` int(10) NOT NULL,
  `CharsDisplayMax` int(10) NOT NULL,
  `Nl2br` smallint(1) NOT NULL,
  `PerView` smallint(6) NOT NULL,
  `PerViewReplies` smallint(6) NOT NULL,
  `BrowseType` varchar(50) NOT NULL,
  `IsBrowseSwitch` smallint(1) NOT NULL,
  `PostFormPosition` varchar(50) NOT NULL,
  `NumberOfLevels` smallint(6) NOT NULL,
  `IsDisplaySwitch` smallint(1) NOT NULL,
  `IsRatable` smallint(1) NOT NULL,
  `ViewingThreshold` smallint(6) NOT NULL,
  `IsOn` smallint(1) NOT NULL,
  `RootStylePrefix` varchar(16) NOT NULL default 'cmt',
  `BaseUrl` varchar(256) NOT NULL,
  `ObjectVote` varchar(64) NOT NULL,
  `TriggerTable` varchar(32) NOT NULL,
  `TriggerFieldId` varchar(32) NOT NULL,
  `TriggerFieldAuthor` varchar(32) NOT NULL,
  `TriggerFieldTitle` varchar(32) NOT NULL,
  `TriggerFieldComments` varchar(32) NOT NULL,
  `ClassName` varchar(32) NOT NULL,
  `ClassFile` varchar(256) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sys_email_templates` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `Module` varchar(32) NOT NULL,
  `NameSystem` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Body` varchar(255) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_admin_email', 't_AdminEmail', '_sys_et_txt_subject_admin_email', '_sys_et_txt_body_admin_email'),
('system', '_sys_et_txt_name_system_confirmation', 't_Confirmation', '_sys_et_txt_subject_confirmation', '_sys_et_txt_body_confirmation'),
('system', '_sys_et_txt_name_system_forgot', 't_Forgot', '_sys_et_txt_subject_forgot', '_sys_et_txt_body_forgot'),
('system', '_sys_et_txt_name_system_password_reset', 't_PasswordReset', '_sys_et_txt_subject_password_reset', '_sys_et_txt_body_password_reset'),
('system', '_sys_et_txt_name_system_mem_expiration', 't_MemExpiration', '_sys_et_txt_subject_mem_expiration', '_sys_et_txt_body_mem_expiration'),
('system', '_sys_et_txt_name_system_mem_changed', 't_MemChanged', '_sys_et_txt_subject_mem_changed', '_sys_et_txt_body_mem_changed'),
('system', '_sys_et_txt_name_system_comment_replied', 't_CommentReplied', '_sys_et_txt_subject_comment_replied', '_sys_et_txt_body_comment_replied'),
('system', '_sys_et_txt_name_system_reported', 't_Reported', '_sys_et_txt_subject_system_reported', '_sys_et_txt_body_system_reported'),
('system', '_sys_et_txt_name_system_delayed_module_uninstall', 't_DelayedModuleUninstall', '_sys_et_txt_subject_delayed_module_uninstall', '_sys_et_txt_body_delayed_module_uninstall'),
('system', '_sys_et_txt_name_system_account', 't_Account', '_sys_et_txt_subject_account', '_sys_et_txt_body_account'),
('system', '_sys_et_txt_name_system_pruning', 't_Pruning', '_sys_et_txt_subject_pruning', '_sys_et_txt_body_pruning'),
('system', '_sys_et_txt_name_profile_change_status_active', 't_ChangeStatusActive', '_sys_et_txt_subject_profile_change_status_active', '_sys_et_txt_body_profile_change_status_active'),
('system', '_sys_et_txt_name_profile_change_status_suspended', 't_ChangeStatusSuspended', '_sys_et_txt_subject_profile_change_status_suspended', '_sys_et_txt_body_profile_change_status_suspended'),
('system', '_sys_et_txt_name_profile_change_status_pending', 't_ChangeStatusPending', '_sys_et_txt_subject_profile_change_status_pending', '_sys_et_txt_body_profile_change_status_pending'),
('system', '_sys_et_txt_name_upgrade_failed', 't_UpgradeFailed', '_sys_et_txt_subject_upgrade_failed', '_sys_et_txt_body_upgrade_failed'),
('system', '_sys_et_txt_name_upgrade_modules_failed', 't_UpgradeModulesFailed', '_sys_et_txt_subject_upgrade_modules_failed', '_sys_et_txt_body_upgrade_modules_failed'),
('system', '_sys_et_txt_name_upgrade_success', 't_UpgradeSuccess', '_sys_et_txt_subject_upgrade_success', '_sys_et_txt_body_upgrade_success'),
('system', '_sys_et_txt_name_upgrade_modules_success', 't_UpgradeModulesSuccess', '_sys_et_txt_subject_upgrade_modules_success', '_sys_et_txt_body_upgrade_modules_success'),
('system', '_sys_et_txt_name_bg_operation_failed', 't_BgOperationFailed', '_sys_et_txt_subject_bg_operation_failed', '_sys_et_txt_body_bg_operation_failed');

-- --------------------------------------------------------

--
-- Table structure for table `sys_options`
--
CREATE TABLE `sys_options` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `value` mediumtext NOT NULL,
  `type` enum('value','digit','text','checkbox','select','combobox','file','image','list','rlist','rgb','rgba') NOT NULL default 'digit',
  `extra` text NOT NULL default '',
  `check` varchar(32) NOT NULL,
  `check_params` text NOT NULL,
  `check_error` varchar(255) NOT NULL default '',
  `order` int(11) default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `sys_options_categories`
--
CREATE TABLE `sys_options_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(64) NOT NULL default '',
  `hidden` tinyint(1) NOT NULL default '0',
  `order` int(11) default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `sys_options_types`
--
CREATE TABLE `sys_options_types` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(64) NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  `order` int(11) default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for tables `sys_options_types`
--
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES ('system', 'system', '_adm_stg_cpt_type_system', 'cogs', 1);
SET @iTypeId = LAST_INSERT_ID();

--
-- CATEGORY (HIDDEN): Hidden
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'hidden', '_adm_stg_cpt_category_hidden', 1, 0);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_install_time', '_adm_stg_cpt_option_sys_install_time', '0', 'digit', '', '', '', 3),

(@iCategoryId, 'sys_ftp_login', '_adm_stg_cpt_option_sys_ftp_login', '', 'digit', '', '', '', 10),
(@iCategoryId, 'sys_ftp_password', '_adm_stg_cpt_option_sys_ftp_password', '', 'digit', '', '', '', 11),
(@iCategoryId, 'sys_ftp_dir', '_adm_stg_cpt_option_sys_ftp_dir', '', 'digit', '', '', '', 12),

(@iCategoryId, 'sys_template_cache_image_enable', '_adm_stg_cpt_option_sys_template_cache_image_enable', '', 'checkbox', '', '', '', 20),
(@iCategoryId, 'sys_template_cache_image_max_size', '_adm_stg_cpt_option_sys_template_cache_image_max_size', '5', 'digit', '', '', '', 21),

(@iCategoryId, 'sys_email_confirmation', '_adm_stg_cpt_option_sys_email_confirmation', 'on', 'checkbox', '', '', '', 30),
(@iCategoryId, 'sys_email_attachable_email_templates', '_adm_stg_cpt_option_sys_email_attachable_email_templates', '', 'digit', '', '', '', 31),

(@iCategoryId, 'sys_redirect_after_account_added', '_adm_stg_cpt_option_sys_redirect_after_account_added', 'page.php?i=account-profile-switcher', 'digit', '', '', '', 40),

(@iCategoryId, 'sys_editor_default', '_adm_stg_cpt_option_sys_editor_default', 'sys_tinymce', 'digit', '', '', '', 50),
(@iCategoryId, 'sys_captcha_default', '_adm_stg_cpt_option_sys_captcha_default', 'sys_recaptcha', 'digit', '', '', '', 51),

(@iCategoryId, 'sys_live_updates_interval', '_adm_stg_cpt_option_sys_live_updates_interval', '10000', 'digit', '', '', '', 60);


--
-- CATEGORY (HIDDEN): System
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'system', '_adm_stg_cpt_category_system', 1, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'site_email_html_template_header', '_adm_stg_cpt_option_site_email_html_template_header', '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #999; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>', 'text', '', '', '', 1),
(@iCategoryId, 'site_email_html_template_footer', '_adm_stg_cpt_option_site_email_html_template_footer', '\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="margin-top:5px; text-align:center; color:#999; font:11px Helvetica, Arial, sans-serif;">{about_us}&nbsp;&nbsp;&nbsp;{unsubscribe}</div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>', 'text', '', '', '', 2),

(@iCategoryId, 'sys_site_logo', '', '0', 'digit', '', '', '', 20),
(@iCategoryId, 'sys_site_logo_alt', '_adm_dsg_txt_alt_text', '0', 'text', '', '', '', 21),
(@iCategoryId, 'sys_site_icon', '', '0', 'digit', '', '', '', 22),
(@iCategoryId, 'sys_site_logo_width', '_adm_stg_cpt_option_sys_site_logo_width', '240', 'digit', '', '', '', 23),
(@iCategoryId, 'sys_site_logo_height', '_adm_stg_cpt_option_sys_site_logo_height', '48', 'digit', '', '', '', 24),

(@iCategoryId, 'sys_site_splash_code', '', '<style>\r\n    /*--- General ---*/\r\n  \r\n    @media (max-width:1024px) {\r\n        #bx-footer-wrapper,\r\n        #bx-menu-main-bar-wrapper,\r\n        #bx-toolbar {\r\n            display:none;\r\n        }\r\n        html #bx-content-wrapper {\r\n            border:none;\r\n            padding-bottom:0;\r\n        }\r\n    }\r\n\r\n    #bx-content-wrapper {\r\n        padding-top: 0px;\r\n        padding-bottom: 4rem;\r\n    }\r\n\r\n    .bx-page-wrapper,\r\n    #bx-content-container,\r\n    #bx-content-main {\r\n        width: 100%;\r\n        margin: 0px;\r\n        padding: 0px;\r\n    }\r\n\r\n    /*--- Splash ---*/\r\n\r\n    .bx-splash {\r\n        position: relative;\r\n    }\r\n\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n\r\n    .bx-spl-slide {\r\n        position: relative;\r\n        display: block;\r\n\r\n        overflow: hidden;\r\n    }\r\n\r\n    .bx-spl-bg {\r\n        position: relative;\r\n\r\n        width: 100%;\r\n        height: 100%;\r\n    }\r\n\r\n    .bx-spl-container {\r\n        position: relative;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 100%;\r\n        height: 100%;\r\n    }\r\n\r\n    .bx-spl-content {\r\n        position: relative;\r\n        width:100%;\r\n    }\r\n\r\n    .bx-spl-slide .bx-spl-content h1 {\r\n        font-size: 2.25rem;\r\n    }\r\n\r\n    .bx-spl-slide .bx-spl-content h3 {\r\n        font-size: 1.5rem;\r\n    }\r\n\r\n    .bx-spl-white-text-all * {\r\n        color: #fff;\r\n    }\r\n\r\n    /*--- Join button ---*/\r\n\r\n    .bx-spl-join,\r\n    .bx-spl-join {\r\n        display: inline-block;\r\n        border-color: #fff;\r\n    }\r\n    .bx-spl-join a,\r\n    .bx-spl-join a {\r\n        display: block;\r\n        padding-left: 3.75rem;\r\n        padding-right: 3.75rem;\r\n        text-decoration: none;\r\n    }\r\n\r\n    /*--- Slides ---*/\r\n\r\n    #bx-spl-slide01 .bx-spl-container,\r\n    #bx-spl-slide03 .bx-spl-container {\r\n        position: absolute;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-content,\r\n    #bx-spl-slide03 .bx-spl-content {\r\n        text-align: center;\r\n        text-shadow: 0px 0px 0.25rem #000;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-content h3,\r\n    #bx-spl-slide04 .bx-spl-content h3 {\r\n        text-align: center;\r\n    }\r\n\r\n    /*--- Slide 1 ---*/\r\n\r\n    #bx-spl-slide01 {\r\n        width:100%;\r\n        height:100vh;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-bg {\r\n        background-position: center center;\r\n        background-repeat: no-repeat;\r\n        background-size: cover;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-slide-video {\r\n        object-fit: cover;\r\n\r\n        width: 100%;\r\n        height: 100vh;\r\n    }\r\n\r\n    /*--- Slide 2 ---*/\r\n\r\n    #bx-spl-slide02 .bx-spl-bg {\r\n        background-color: #fff;\r\n        height: 35vw;\r\n        max-height:400px;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-image {\r\n        position: absolute;\r\n        width: 60%;\r\n        height: 100%;\r\n        background-position: center bottom;\r\n        background-repeat: no-repeat;\r\n        background-size: contain;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-content {\r\n        position: absolute;\r\n        display: -webkit-flex;\r\n        -webkit-align-items: center;\r\n        -webkit-justify-content: center;\r\n        display: flex;\r\n        align-items: center;\r\n        justify-content: center;\r\n\r\n        top: 0px;\r\n        right: 0px;\r\n        width: 40%;\r\n        height: 100%;\r\n    }\r\n    @media (max-width:720px) {\r\n        #bx-spl-slide02.bx-spl-slide .bx-spl-content h3 {\r\n            font-size: 0.8rem;\r\n        }\r\n    }\r\n\r\n    /*--- Slide 3 ---*/\r\n\r\n    #bx-spl-slide03 {\r\n        width:100%;\r\n        height:150vh;\r\n    }\r\n    #bx-spl-slide03 .bx-spl-bg {\r\n        background-position: center center;\r\n        background-repeat: repeat;\r\n        background-attachment: fixed;\r\n        background-size:100%;\r\n    }\r\n\r\n    /*--- Slide 4 ---*/\r\n\r\n    #bx-spl-slide04 {\r\n        width:100%;\r\n        height:100vh;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-bg {\r\n        background-color:#fff;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-container {\r\n        display: -webkit-flex;\r\n        -webkit-align-items: center;\r\n        -webkit-justify-content: center;\r\n        display: flex;\r\n        align-items: center;\r\n        justify-content: center;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-content {\r\n        width: 50%;\r\n    }\r\n    @media (max-width:720px) {\r\n        #bx-spl-slide04 .bx-spl-content {\r\n            width: 90%;\r\n        }\r\n    }\r\n    #bx-spl-slide04 .bx-spl-content h3 {\r\n        margin-top: 0px;\r\n        margin-bottom: 2rem;\r\n    }\r\n</style>\r\n<div id="skrollr-body" class="bx-splash bx-def-color-bg-page">\r\n	<div id="bx-spl-preload" class="bx-spl-preload">\r\n		<img src="<bx_image_url:cover01.jpg />">\r\n		<img src="<bx_image_url:cover02.jpg />">\r\n		<img src="<bx_image_url:cover03.jpg />">\r\n	</div>\r\n\r\n	<div id="bx-spl-slide01" class="bx-spl-slide">\r\n		<div class="bx-spl-bg" style="background-image:url(<bx_image_url:cover01.jpg />);">\r\n			<video class="bx-spl-slide-video bx-def-media-phone-hide bx-def-media-tablet-hide" poster="<bx_image_url:cover01.jpg />" loop autoplay muted>\r\n				<source src="<bx_image_url:cover01.mp4 />" type="video/mp4">\r\n				<source src="<bx_image_url:cover01.webm />" type="video/webm">\r\n			</video>\r\n		</div>\r\n		<div class="bx-spl-container">\r\n			<div class="bx-spl-content bx-spl-white-text-all" data-anchor-target="#bx-spl-slide01 .bx-spl-bg" data-top="opacity:1" data-top-center="opacity:0">\r\n				<h1 class="bx-def-font-semibold"><bx_text:_sys_txt_splash_slide01_title /></h1>\r\n				<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide01_desc /></h3>\r\n				<div class="bx-spl-join bx-def-margin-topbottom bx-def-border bx-def-round-corners">\r\n					<a class="bx-def-padding-sec-topbottom bx-def-font-h3 bx-def-font-normal" href="__join_link__"><bx_text:_sys_txt_splash_btn_join /></a>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n\r\n	<div id="bx-spl-slide02" class="bx-spl-slide">\r\n		<div class="bx-spl-bg">\r\n			<div class="bx-spl-container">\r\n				<div class="bx-spl-image" style="background-image:url(<bx_image_url:cover02.jpg />);" data-bottom-top="bottom:-100%;" data-100-center-center="bottom:0%;"></div>\r\n				<div class="bx-spl-content" data-bottom-top="right:-50%;" data-100-center-center="right:0%;">\r\n					<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide02_txt /></h3>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n\r\n	<div id="bx-spl-slide03" class="bx-spl-slide">\r\n		<div class="bx-spl-bg" style="background-image:url(<bx_image_url:cover03.jpg />)"></div>\r\n		<div class="bx-spl-container">\r\n			<div class="bx-spl-content bx-spl-white-text-all">\r\n				<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide03_txt /></h3>\r\n				<div class="bx-spl-join bx-def-margin-topbottom bx-def-border bx-def-round-corners">\r\n					<a class="bx-def-padding-sec-topbottom bx-def-font-h3 bx-def-font-normal" href="__join_link__"><bx_text:_sys_txt_splash_btn_join /></a>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n	<div id="bx-spl-slide04" class="bx-spl-slide">\r\n		<div class="bx-spl-bg">\r\n            <div class="bx-spl-container" data-anchor-target="#bx-spl-slide03 .bx-spl-bg" data-100-center-bottom="opacity:0; transform:scale(0.7,0.7);" data-200-end="opacity:1; transform:scale(1,1);">\r\n                <div class="bx-spl-content">\r\n                    <h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide04_txt /></h3>\r\n                    <div class="bx-spl-login">__login_form__</div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n	</div>\r\n	<script type="text/javascript">\r\n		$(document).ready(function () {\r\n\r\n            // workaround for iOS 7 \r\n            if (!!navigator.userAgent.match(/i(Pad|Phone|Pod).+(Version\\/7\\.\\d+ Mobile)/i)) {\r\n                var aSelVh = {\r\n                    ''1'': ''#bx-spl-slide01, #bx-spl-slide01 .bx-spl-slide-video, #bx-spl-slide04'',\r\n                    ''1.5'': ''#bx-spl-slide03''\r\n                };\r\n                var aSelVw = {\r\n                    ''0.35'': ''#bx-spl-slide02 .bx-spl-bg''\r\n                };\r\n                function fixMobileSafariViewport() {\r\n                    $.each(aSelVh, function (sVal, sSel) {\r\n                        $(sSel).css(''height'', window.innerHeight * parseFloat(sVal));\r\n                    });\r\n                    $.each(aSelVw, function (sVal, sSel) {\r\n                        $(sSel).css(''height'', window.innerWidth * parseFloat(sVal));\r\n                    });                \r\n                }\r\n                // listen to portrait/landscape changes\r\n                window.addEventListener(''orientationchange'', fixMobileSafariViewport, true);\r\n                fixMobileSafariViewport();\r\n            }\r\n\r\n			skrollr.init();\r\n		});\r\n	</script>\r\n</div>', 'text', '', '', '', 25),
(@iCategoryId, 'sys_site_splash_enabled', '', '', 'checkbox', '', '', '', 26),

(@iCategoryId, 'sys_site_cover_common', '', '0', 'digit', '', '', '', 27),
(@iCategoryId, 'sys_unit_cover_profile', '', '0', 'digit', '', '', '', 28);


--
-- CATEGORY (HIDDEN): Languages
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'languages', '_adm_stg_cpt_category_languages', 1, 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'lang_default', '_adm_stg_cpt_option_lang_default', '', 'select', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"get_languages";s:6:"params";a:2:{i:0;b:0;i:1;b:1;}s:5:"class";s:22:"TemplLanguagesServices";}', '', '', 1),
(@iCategoryId, 'lang_subst_from_en', '_adm_stg_cpt_option_lang_subst_from_en', 'on', 'checkbox', '', '', '', 2),

(@iCategoryId, 'sys_format_date', '_adm_stg_cpt_option_sys_format_date', 'D MMM YYYY', 'digit', '', '', '', 3),
(@iCategoryId, 'sys_format_time', '_adm_stg_cpt_option_sys_format_time', 'HH:mm', 'digit', '', '', '', 4),
(@iCategoryId, 'sys_format_datetime', '_adm_stg_cpt_option_sys_format_datetime', 'D MMM YYYY h:mm:ss a', 'digit', '', '', '', 5),
(@iCategoryId, 'sys_format_timeago', '_adm_stg_cpt_option_sys_format_timeago', 432000, 'digit', '', '', '', 6);


--
-- CATEGORY (HIDDEN): Templates
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'templates', '_adm_stg_cpt_category_templates', 1, 3);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'template', '_adm_stg_cpt_option_template', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:13:"get_templates";s:5:"class";s:21:"TemplTemplateServices";}', 'Template', '_adm_stg_err_option_template', 1);


--
-- CATEGORY: Cache
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'cache', '_adm_stg_cpt_category_cache', 0, 4);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_db_cache_enable', '_adm_stg_cpt_option_sys_db_cache_enable', 'on', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_db_cache_engine', '_adm_stg_cpt_option_sys_db_cache_engine', 'File', 'select', 'File,Memcache,APC,XCache', '', '', 11),

(@iCategoryId, 'sys_cache_memcache_host', '_adm_stg_cpt_option_sys_cache_memcache_host', '', 'digit', '', '', '', 20),
(@iCategoryId, 'sys_cache_memcache_port', '_adm_stg_cpt_option_sys_cache_memcache_port', '11211', 'digit', '', '', '', 21),

(@iCategoryId, 'sys_page_cache_enable', '_adm_stg_cpt_option_sys_page_cache_enable', 'on', 'checkbox', '', '', '', 30),
(@iCategoryId, 'sys_page_cache_engine', '_adm_stg_cpt_option_sys_page_cache_engine', 'File', 'select', 'File,Memcache,APC,XCache', '', '', 31),

(@iCategoryId, 'sys_pb_cache_enable', '_adm_stg_cpt_option_sys_pb_cache_enable', 'on', 'checkbox', '', '', '', 40),
(@iCategoryId, 'sys_pb_cache_engine', '_adm_stg_cpt_option_sys_pb_cache_engine', 'File', 'select', 'File,Memcache,APC,XCache', '', '', 41),

(@iCategoryId, 'sys_template_cache_enable', '_adm_stg_cpt_option_sys_template_cache_enable', 'on', 'checkbox', '', '', '', 50),
(@iCategoryId, 'sys_template_cache_engine', '_adm_stg_cpt_option_sys_template_cache_engine', 'FileHtml', 'select', 'FileHtml,Memcache,APC,XCache', '', '', 51),
(@iCategoryId, 'sys_template_cache_css_enable', '_adm_stg_cpt_option_sys_template_cache_css_enable', 'on', 'checkbox', '', '', '', 55),
(@iCategoryId, 'sys_template_cache_js_enable', '_adm_stg_cpt_option_sys_template_cache_js_enable', 'on', 'checkbox', '', '', '', 56),
(@iCategoryId, 'sys_template_cache_minify_css_enable', '_adm_stg_cpt_option_sys_template_cache_minify_css_enable', 'on', 'checkbox', '', '', '', 57),
(@iCategoryId, 'sys_template_cache_minify_js_enable', '_adm_stg_cpt_option_sys_template_cache_minify_js_enable', 'on', 'checkbox', '', '', '', 58),
(@iCategoryId, 'sys_template_cache_compress_enable', '_adm_stg_cpt_option_sys_template_cache_compress_enable', 'on', 'checkbox', '', '', '', 59);


--
-- CATEGORY: General
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'general', '_adm_stg_cpt_category_general', 0, 6);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_oauth_key', '_adm_stg_cpt_option_sys_oauth_key', '', 'digit', '', '', '', 20),
(@iCategoryId, 'sys_oauth_secret', '_adm_stg_cpt_option_sys_oauth_secret', '', 'digit', '', '', '', 21),

(@iCategoryId, 'currency_code', '_adm_stg_cpt_option_currency_code', 'USD', 'select', 'USD,EURO', 'Avail', '_adm_stg_err_option_currency_code', 30),
(@iCategoryId, 'currency_sign', '_adm_stg_cpt_option_currency_sign', '&#36;', 'digit', '', 'Avail', '_adm_stg_err_option_currency_sign', 31),

(@iCategoryId, 'enable_gd', '_adm_stg_cpt_option_enable_gd', 'on', 'checkbox', '', '', '', 40),
(@iCategoryId, 'useLikeOperator', '_adm_stg_cpt_option_use_like_operator', 'on', 'checkbox', '', '', '', 45),

(@iCategoryId, 'sys_transcoder_queue_storage', '_adm_stg_cpt_option_sys_transcoder_queue_storage', '', 'checkbox', '', '', '', 50),

(@iCategoryId, 'sys_default_payment', '_adm_stg_cpt_option_sys_default_payment', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:12:"get_payments";s:5:"class";s:21:"TemplPaymentsServices";}', '', '', 60),

(@iCategoryId, 'sys_maps_api_key', '_adm_stg_cpt_option_sys_maps_api_key', '', 'digit', '', '', '', 70),

(@iCategoryId, 'sys_embedly_api_key', '_adm_stg_cpt_option_sys_embedly_api_key', '', 'digit', '', '', '', 80),
(@iCategoryId, 'sys_embedly_api_pattern', '_adm_stg_cpt_option_sys_embedly_api_pattern', '((https?:\\/\\/(.*yfrog\\..*\\/.*|www\\.flickr\\.com\\/photos\\/.*|flic\\.kr\\/.*|twitpic\\.com\\/.*|www\\.twitpic\\.com\\/.*|twitpic\\.com\\/photos\\/.*|www\\.twitpic\\.com\\/photos\\/.*|.*imgur\\.com\\/.*|twitgoo\\.com\\/.*|i.*\\.photobucket\\.com\\/albums\\/.*|s.*\\.photobucket\\.com\\/albums\\/.*|media\\.photobucket\\.com\\/image\\/.*|www\\.mobypicture\\.com\\/user\\/.*\\/view\\/.*|moby\\.to\\/.*|xkcd\\.com\\/.*|www\\.xkcd\\.com\\/.*|imgs\\.xkcd\\.com\\/.*|www\\.asofterworld\\.com\\/index\\.php\\?id=.*|www\\.asofterworld\\.com\\/.*\\.jpg|asofterworld\\.com\\/.*\\.jpg|www\\.qwantz\\.com\\/index\\.php\\?comic=.*|23hq\\.com\\/.*\\/photo\\/.*|www\\.23hq\\.com\\/.*\\/photo\\/.*|.*dribbble\\.com\\/shots\\/.*|drbl\\.in\\/.*|.*\\.smugmug\\.com\\/.*|.*\\.smugmug\\.com\\/.*#.*|picasaweb\\.google\\.com.*\\/.*\\/.*#.*|picasaweb\\.google\\.com.*\\/lh\\/photo\\/.*|picasaweb\\.google\\.com.*\\/.*\\/.*|img\\.ly\\/.*|www\\.tinypic\\.com\\/view\\.php.*|tinypic\\.com\\/view\\.php.*|www\\.tinypic\\.com\\/player\\.php.*|tinypic\\.com\\/player\\.php.*|www\\.tinypic\\.com\\/r\\/.*\\/.*|tinypic\\.com\\/r\\/.*\\/.*|.*\\.tinypic\\.com\\/.*\\.jpg|.*\\.tinypic\\.com\\/.*\\.png|meadd\\.com\\/.*\\/.*|meadd\\.com\\/.*|.*\\.deviantart\\.com\\/art\\/.*|.*\\.deviantart\\.com\\/gallery\\/.*|.*\\.deviantart\\.com\\/#\\/.*|fav\\.me\\/.*|.*\\.deviantart\\.com|.*\\.deviantart\\.com\\/gallery|.*\\.deviantart\\.com\\/.*\\/.*\\.jpg|.*\\.deviantart\\.com\\/.*\\/.*\\.gif|.*\\.deviantart\\.net\\/.*\\/.*\\.jpg|.*\\.deviantart\\.net\\/.*\\/.*\\.gif|www\\.fotopedia\\.com\\/.*\\/.*|fotopedia\\.com\\/.*\\/.*|photozou\\.jp\\/photo\\/show\\/.*\\/.*|photozou\\.jp\\/photo\\/photo_only\\/.*\\/.*|instagr\\.am\\/p\\/.*|instagram\\.com\\/p\\/.*|skitch\\.com\\/.*\\/.*\\/.*|img\\.skitch\\.com\\/.*|www\\.questionablecontent\\.net\\/|questionablecontent\\.net\\/|www\\.questionablecontent\\.net\\/view\\.php.*|questionablecontent\\.net\\/view\\.php.*|questionablecontent\\.net\\/comics\\/.*\\.png|www\\.questionablecontent\\.net\\/comics\\/.*\\.png|twitrpix\\.com\\/.*|.*\\.twitrpix\\.com\\/.*|www\\.someecards\\.com\\/.*\\/.*|someecards\\.com\\/.*\\/.*|some\\.ly\\/.*|www\\.some\\.ly\\/.*|pikchur\\.com\\/.*|achewood\\.com\\/.*|www\\.achewood\\.com\\/.*|achewood\\.com\\/index\\.php.*|www\\.achewood\\.com\\/index\\.php.*|www\\.whosay\\.com\\/.*\\/content\\/.*|www\\.whosay\\.com\\/.*\\/photos\\/.*|www\\.whosay\\.com\\/.*\\/videos\\/.*|say\\.ly\\/.*|ow\\.ly\\/i\\/.*|mlkshk\\.com\\/p\\/.*|lockerz\\.com\\/s\\/.*|pics\\.lockerz\\.com\\/s\\/.*|d\\.pr\\/i\\/.*|www\\.eyeem\\.com\\/p\\/.*|www\\.eyeem\\.com\\/a\\/.*|www\\.eyeem\\.com\\/u\\/.*|giphy\\.com\\/gifs\\/.*|gph\\.is\\/.*|frontback\\.me\\/p\\/.*|www\\.fotokritik\\.com\\/.*\\/.*|fotokritik\\.com\\/.*\\/.*|vid\\.me\\/.*|galeri\\.uludagsozluk\\.com\\/.*|gfycat\\.com\\/.*|tochka\\.net\\/.*|.*\\.tochka\\.net\\/.*|4cook\\.net\\/recipe\\/.*|.*youtube\\.com\\/watch.*|.*\\.youtube\\.com\\/v\\/.*|youtu\\.be\\/.*|.*\\.youtube\\.com\\/user\\/.*|.*\\.youtube\\.com\\/.*#.*\\/.*|m\\.youtube\\.com\\/watch.*|m\\.youtube\\.com\\/index.*|.*\\.youtube\\.com\\/profile.*|.*\\.youtube\\.com\\/view_play_list.*|.*\\.youtube\\.com\\/playlist.*|www\\.youtube\\.com\\/embed\\/.*|youtube\\.com\\/gif.*|www\\.youtube\\.com\\/gif.*|.*twitch\\.tv\\/.*|.*twitch\\.tv\\/.*\\/b\\/.*|www\\.ustream\\.tv\\/recorded\\/.*|www\\.ustream\\.tv\\/channel\\/.*|www\\.ustream\\.tv\\/.*|ustre\\.am\\/.*|qik\\.com\\/video\\/.*|qik\\.com\\/.*|qik\\.ly\\/.*|.*revision3\\.com\\/.*|.*\\.dailymotion\\.com\\/video\\/.*|.*\\.dailymotion\\.com\\/.*\\/video\\/.*|collegehumor\\.com\\/video:.*|collegehumor\\.com\\/video\\/.*|www\\.collegehumor\\.com\\/video:.*|www\\.collegehumor\\.com\\/video\\/.*|telly\\.com\\/.*|www\\.telly\\.com\\/.*|break\\.com\\/.*\\/.*|www\\.break\\.com\\/.*\\/.*|vids\\.myspace\\.com\\/index\\.cfm\\?fuseaction=vids\\.individual&videoid.*|www\\.myspace\\.com\\/index\\.cfm\\?fuseaction=.*&videoid.*|www\\.metacafe\\.com\\/watch\\/.*|www\\.metacafe\\.com\\/w\\/.*|blip\\.tv\\/.*\\/.*|.*\\.blip\\.tv\\/.*\\/.*|video\\.google\\.com\\/videoplay\\?.*|.*viddler\\.com\\/v\\/.*|liveleak\\.com\\/view\\?.*|www\\.liveleak\\.com\\/view\\?.*|animoto\\.com\\/play\\/.*|video214\\.com\\/play\\/.*|dotsub\\.com\\/view\\/.*|www\\.overstream\\.net\\/view\\.php\\?oid=.*|www\\.livestream\\.com\\/.*|new\\.livestream\\.com\\/.*|www\\.worldstarhiphop\\.com\\/videos\\/video.*\\.php\\?v=.*|worldstarhiphop\\.com\\/videos\\/video.*\\.php\\?v=.*|bambuser\\.com\\/v\\/.*|bambuser\\.com\\/channel\\/.*|bambuser\\.com\\/channel\\/.*\\/broadcast\\/.*|www\\.schooltube\\.com\\/video\\/.*\\/.*|bigthink\\.com\\/ideas\\/.*|bigthink\\.com\\/series\\/.*|sendables\\.jibjab\\.com\\/view\\/.*|sendables\\.jibjab\\.com\\/originals\\/.*|jibjab\\.com\\/view\\/.*|www\\.xtranormal\\.com\\/watch\\/.*|socialcam\\.com\\/v\\/.*|www\\.socialcam\\.com\\/v\\/.*|v\\.youku\\.com\\/v_show\\/.*|v\\.youku\\.com\\/v_playlist\\/.*|www\\.snotr\\.com\\/video\\/.*|snotr\\.com\\/video\\/.*|www\\.clipfish\\.de\\/.*\\/.*\\/video\\/.*|www\\.myvideo\\.de\\/watch\\/.*|www\\.vzaar\\.com\\/videos\\/.*|vzaar\\.com\\/videos\\/.*|www\\.vzaar\\.tv\\/.*|vzaar\\.tv\\/.*|vzaar\\.me\\/.*|.*\\.vzaar\\.me\\/.*|coub\\.com\\/view\\/.*|coub\\.com\\/embed\\/.*|www\\.streamio\\.com\\/api\\/v1\\/.*|streamio\\.com\\/api\\/v1\\/.*|vine\\.co\\/v\\/.*|www\\.vine\\.co\\/v\\/.*|www\\.viddy\\.com\\/video\\/.*|www\\.viddy\\.com\\/.*\\/v\\/.*|www\\.tudou\\.com\\/programs\\/view\\/.*|tudou\\.com\\/programs\\/view\\/.*|sproutvideo\\.com\\/videos\\/.*|embed\\.minoto-video\\.com\\/.*|iframe\\.minoto-video\\.com\\/.*|play\\.minoto-video\\.com\\/.*|dashboard\\.minoto-video\\.com\\/main\\/video\\/details\\/.*|api\\.minoto-video\\.com\\/publishers\\/.*\\/videos\\/.*|www\\.brainshark\\.com\\/.*\\/.*|brainshark\\.com\\/.*\\/.*|23video\\.com\\/.*|.*\\.23video\\.com\\/.*|goanimate\\.com\\/videos\\/.*|brainsonic\\.com\\/.*|.*\\.brainsonic\\.com\\/.*|lustich\\.de\\/videos\\/.*|web\\.tv\\/.*|.*\\.web\\.tv\\/.*|mynet\\.com\\/video\\/.*|www\\.mynet\\.com\\/video\\/|izlesene\\.com\\/video\\/.*|www\\.izlesene\\.com\\/video\\/|alkislarlayasiyorum\\.com\\/.*|www\\.alkislarlayasiyorum\\.com\\/.*|59saniye\\.com\\/.*|www\\.59saniye\\.com\\/.*|zie\\.nl\\/video\\/.*|www\\.zie\\.nl\\/video\\/.*|app\\.ustudio\\.com\\/embed\\/.*\\/.*|bale\\.io\\/.*|www\\.allego\\.com\\/.*|clipter\\.com\\/c\\/.*|sendvid\\.com\\/.*|www\\.snappytv\\.com\\/.*|snappytv\\.com\\/.*|frankly\\.me\\/.*|streamable\\.com\\/.*|ticker\\.tv\\/v\\/.*|videobio\\.com\\/playerjs\\/.*|clippituser\\.tv\\/.*|www\\.clippituser\\.tv\\/.*|www\\.whitehouse\\.gov\\/photos-and-video\\/video\\/.*|www\\.whitehouse\\.gov\\/video\\/.*|wh\\.gov\\/photos-and-video\\/video\\/.*|wh\\.gov\\/video\\/.*|www\\.hulu\\.com\\/watch.*|www\\.hulu\\.com\\/w\\/.*|www\\.hulu\\.com\\/embed\\/.*|hulu\\.com\\/watch.*|hulu\\.com\\/w\\/.*|.*crackle\\.com\\/c\\/.*|www\\.funnyordie\\.com\\/videos\\/.*|www\\.funnyordie\\.com\\/m\\/.*|funnyordie\\.com\\/videos\\/.*|funnyordie\\.com\\/m\\/.*|www\\.vimeo\\.com\\/groups\\/.*\\/videos\\/.*|www\\.vimeo\\.com\\/.*|vimeo\\.com\\/groups\\/.*\\/videos\\/.*|vimeo\\.com\\/.*|vimeo\\.com\\/m\\/#\\/.*|player\\.vimeo\\.com\\/.*|www\\.ted\\.com\\/talks\\/.*\\.html.*|www\\.ted\\.com\\/talks\\/lang\\/.*\\/.*\\.html.*|www\\.ted\\.com\\/index\\.php\\/talks\\/.*\\.html.*|www\\.ted\\.com\\/index\\.php\\/talks\\/lang\\/.*\\/.*\\.html.*|www\\.ted\\.com\\/talks\\/|.*nfb\\.ca\\/film\\/.*|thedailyshow\\.cc\\.com\\/videos\\/.*|www\\.thedailyshow\\.com\\/watch\\/.*|www\\.thedailyshow\\.com\\/full-episodes\\/.*|www\\.thedailyshow\\.com\\/collection\\/.*\\/.*\\/.*|yahoo\\.com\\/movies\\/.*|.*\\.yahoo\\.com\\/movies\\/.*|thecolbertreport\\.cc\\.com\\/videos\\/.*|www\\.colbertnation\\.com\\/the-colbert-report-collections\\/.*|www\\.colbertnation\\.com\\/full-episodes\\/.*|www\\.colbertnation\\.com\\/the-colbert-report-videos\\/.*|www\\.comedycentral\\.com\\/videos\\/index\\.jhtml\\?.*|www\\.theonion\\.com\\/video\\/.*|theonion\\.com\\/video\\/.*|wordpress\\.tv\\/.*\\/.*\\/.*\\/.*\\/|www\\.traileraddict\\.com\\/trailer\\/.*|www\\.traileraddict\\.com\\/clip\\/.*|www\\.traileraddict\\.com\\/poster\\/.*|www\\.trailerspy\\.com\\/trailer\\/.*\\/.*|www\\.trailerspy\\.com\\/trailer\\/.*|www\\.trailerspy\\.com\\/view_video\\.php.*|fora\\.tv\\/.*\\/.*\\/.*\\/.*|www\\.spike\\.com\\/video\\/.*|www\\.gametrailers\\.com\\/video.*|gametrailers\\.com\\/video.*|www\\.koldcast\\.tv\\/video\\/.*|www\\.koldcast\\.tv\\/#video:.*|mixergy\\.com\\/.*|video\\.pbs\\.org\\/video\\/.*|www\\.zapiks\\.com\\/.*|www\\.trutv\\.com\\/video\\/.*|www\\.nzonscreen\\.com\\/title\\/.*|nzonscreen\\.com\\/title\\/.*|app\\.wistia\\.com\\/embed\\/medias\\/.*|wistia\\.com\\/.*|.*\\.wistia\\.com\\/.*|.*\\.wi\\.st\\/.*|wi\\.st\\/.*|confreaks\\.net\\/videos\\/.*|www\\.confreaks\\.net\\/videos\\/.*|confreaks\\.com\\/videos\\/.*|www\\.confreaks\\.com\\/videos\\/.*|video\\.allthingsd\\.com\\/video\\/.*|videos\\.nymag\\.com\\/.*|aniboom\\.com\\/animation-video\\/.*|www\\.aniboom\\.com\\/animation-video\\/.*|grindtv\\.com\\/.*\\/video\\/.*|www\\.grindtv\\.com\\/.*\\/video\\/.*|ifood\\.tv\\/recipe\\/.*|ifood\\.tv\\/video\\/.*|ifood\\.tv\\/channel\\/user\\/.*|www\\.ifood\\.tv\\/recipe\\/.*|www\\.ifood\\.tv\\/video\\/.*|www\\.ifood\\.tv\\/channel\\/user\\/.*|logotv\\.com\\/video\\/.*|www\\.logotv\\.com\\/video\\/.*|lonelyplanet\\.com\\/Clip\\.aspx\\?.*|www\\.lonelyplanet\\.com\\/Clip\\.aspx\\?.*|streetfire\\.net\\/video\\/.*\\.htm.*|www\\.streetfire\\.net\\/video\\/.*\\.htm.*|sciencestage\\.com\\/v\\/.*\\.html|sciencestage\\.com\\/a\\/.*\\.html|www\\.sciencestage\\.com\\/v\\/.*\\.html|www\\.sciencestage\\.com\\/a\\/.*\\.html|link\\.brightcove\\.com\\/services\\/player\\/bcpid.*|bcove\\.me\\/.*|wirewax\\.com\\/.*|www\\.wirewax\\.com\\/.*|canalplus\\.fr\\/.*|www\\.canalplus\\.fr\\/.*|www\\.vevo\\.com\\/watch\\/.*|www\\.vevo\\.com\\/video\\/.*|pixorial\\.com\\/watch\\/.*|www\\.pixorial\\.com\\/watch\\/.*|spreecast\\.com\\/events\\/.*|www\\.spreecast\\.com\\/events\\/.*|showme\\.com\\/sh\\/.*|www\\.showme\\.com\\/sh\\/.*|.*\\.looplogic\\.com\\/.*|on\\.aol\\.com\\/video\\/.*|on\\.aol\\.com\\/playlist\\/.*|videodetective\\.com\\/.*\\/.*|www\\.videodetective\\.com\\/.*\\/.*|khanacademy\\.org\\/.*|www\\.khanacademy\\.org\\/.*|.*vidyard\\.com\\/.*|www\\.veoh\\.com\\/watch\\/.*|veoh\\.com\\/watch\\/.*|.*\\.univision\\.com\\/.*\\/video\\/.*|.*\\.vidcaster\\.com\\/.*|muzu\\.tv\\/.*|www\\.muzu\\.tv\\/.*|vube\\.com\\/.*\\/.*|www\\.vube\\.com\\/.*\\/.*|.*boxofficebuz\\.com\\/video\\/.*|www\\.godtube\\.com\\/featured\\/video\\/.*|godtube\\.com\\/featured\\/video\\/.*|www\\.godtube\\.com\\/watch\\/.*|godtube\\.com\\/watch\\/.*|mediamatters\\.org\\/mmtv\\/.*|www\\.clikthrough\\.com\\/theater\\/video\\/.*|www\\.clipsyndicate\\.com\\/video\\/playlist\\/.*\\/.*|www\\.srf\\.ch\\/play\\/.*\\/.*\\/.*\\/.*\\?id=.*|www\\.mpora\\.com\\/videos\\/.*|mpora\\.com\\/videos\\/.*|vice\\.com\\/.*|www\\.vice\\.com\\/.*|videodonor\\.com\\/video\\/.*|api\\.lovelive\\.tv\\/v1\\/.*|www\\.hurriyettv\\.com\\/.*|www\\.hurriyettv\\.com\\/.*|video\\.uludagsozluk\\.com\\/.*|www\\.ign\\.com\\/videos\\/.*|ign\\.com\\/videos\\/.*|www\\.askmen\\.com\\/video\\/.*|askmen\\.com\\/video\\/.*|video\\.esri\\.com\\/.*|www\\.zapkolik\\.com\\/video\\/.*|.*\\.iplayerhd\\.com\\/playerframe\\/.*|.*\\.iplayerhd\\.com\\/player\\/video\\/.*|plays\\.tv\\/video\\/.*|espn\\.go\\.com\\/video\\/clip.*|espn\\.go\\.com\\/.*\\/story.*|abcnews\\.com\\/.*\\/video\\/.*|abcnews\\.com\\/video\\/playerIndex.*|abcnews\\.go\\.com\\/.*\\/video\\/.*|abcnews\\.go\\.com\\/video\\/playerIndex.*|washingtonpost\\.com\\/wp-dyn\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.washingtonpost\\.com\\/wp-dyn\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.boston\\.com\\/video.*|boston\\.com\\/video.*|www\\.boston\\.com\\/.*video.*|boston\\.com\\/.*video.*|www\\.facebook\\.com\\/photo\\.php.*|www\\.facebook\\.com\\/video\\.php.*|www\\.facebook\\.com\\/.*\\/posts\\/.*|fb\\.me\\/.*|www\\.facebook\\.com\\/.*\\/photos\\/.*|www\\.facebook\\.com\\/.*\\/videos\\/.*|cnbc\\.com\\/id\\/.*\\?.*video.*|www\\.cnbc\\.com\\/id\\/.*\\?.*video.*|cnbc\\.com\\/id\\/.*\\/play\\/1\\/video\\/.*|www\\.cnbc\\.com\\/id\\/.*\\/play\\/1\\/video\\/.*|cbsnews\\.com\\/video\\/watch\\/.*|plus\\.google\\.com\\/.*|www\\.google\\.com\\/profiles\\/.*|google\\.com\\/profiles\\/.*|www\\.cnn\\.com\\/video\\/.*|edition\\.cnn\\.com\\/video\\/.*|money\\.cnn\\.com\\/video\\/.*|today\\.msnbc\\.msn\\.com\\/id\\/.*\\/vp\\/.*|www\\.msnbc\\.msn\\.com\\/id\\/.*\\/vp\\/.*|www\\.msnbc\\.msn\\.com\\/id\\/.*\\/ns\\/.*|today\\.msnbc\\.msn\\.com\\/id\\/.*\\/ns\\/.*|msnbc\\.msn\\.com\\/.*\\/watch\\/.*|www\\.msnbc\\.msn\\.com\\/.*\\/watch\\/.*|nbcnews\\.com\\/.*|www\\.nbcnews\\.com\\/.*|multimedia\\.foxsports\\.com\\/m\\/video\\/.*\\/.*|msn\\.foxsports\\.com\\/video.*|www\\.globalpost\\.com\\/video\\/.*|www\\.globalpost\\.com\\/dispatch\\/.*|theguardian\\.com\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.theguardian\\.com\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|guardian\\.co\\.uk\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.guardian\\.co\\.uk\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|bravotv\\.com\\/.*\\/.*\\/videos\\/.*|www\\.bravotv\\.com\\/.*\\/.*\\/videos\\/.*|dsc\\.discovery\\.com\\/videos\\/.*|animal\\.discovery\\.com\\/videos\\/.*|health\\.discovery\\.com\\/videos\\/.*|investigation\\.discovery\\.com\\/videos\\/.*|military\\.discovery\\.com\\/videos\\/.*|planetgreen\\.discovery\\.com\\/videos\\/.*|science\\.discovery\\.com\\/videos\\/.*|tlc\\.discovery\\.com\\/videos\\/.*|video\\.forbes\\.com\\/fvn\\/.*|distrify\\.com\\/film\\/.*|muvi\\.es\\/.*|video\\.foxnews\\.com\\/v\\/.*|video\\.foxbusiness\\.com\\/v\\/.*|www\\.reuters\\.com\\/video\\/.*|reuters\\.com\\/video\\/.*|live\\.huffingtonpost\\.com\\/r\\/segment\\/.*\\/.*|video\\.nytimes\\.com\\/video\\/.*|www\\.nytimes\\.com\\/video\\/.*\\/.*|nyti\\.ms\\/.*|www\\.vol\\.at\\/video\\/.*|vol\\.at\\/video\\/.*|www\\.spiegel\\.de\\/video\\/.*|spiegel\\.de\\/video\\/.*|www\\.zeit\\.de\\/video\\/.*|zeit\\.de\\/video\\/.*|www\\.rts\\.ch\\/play\\/tv\\/.*|soundcloud\\.com\\/.*|soundcloud\\.com\\/.*\\/.*|soundcloud\\.com\\/.*\\/sets\\/.*|soundcloud\\.com\\/groups\\/.*|snd\\.sc\\/.*|open\\.spotify\\.com\\/.*|spoti\\.fi\\/.*|play\\.spotify\\.com\\/.*|www\\.last\\.fm\\/music\\/.*|www\\.last\\.fm\\/music\\/+videos\\/.*|www\\.last\\.fm\\/music\\/+images\\/.*|www\\.last\\.fm\\/music\\/.*\\/_\\/.*|www\\.last\\.fm\\/music\\/.*\\/.*|www\\.mixcloud\\.com\\/.*\\/.*\\/|www\\.radionomy\\.com\\/.*\\/radio\\/.*|radionomy\\.com\\/.*\\/radio\\/.*|www\\.hark\\.com\\/clips\\/.*|www\\.rdio\\.com\\/#\\/artist\\/.*\\/album\\/.*|www\\.rdio\\.com\\/artist\\/.*\\/album\\/.*|www\\.zero-inch\\.com\\/.*|.*\\.bandcamp\\.com\\/|.*\\.bandcamp\\.com\\/track\\/.*|.*\\.bandcamp\\.com\\/album\\/.*|freemusicarchive\\.org\\/music\\/.*|www\\.freemusicarchive\\.org\\/music\\/.*|freemusicarchive\\.org\\/curator\\/.*|www\\.freemusicarchive\\.org\\/curator\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/templates\\/story\\/story\\.php.*|huffduffer\\.com\\/.*\\/.*|www\\.audioboom\\.com\\/boos\\/.*|audioboom\\.com\\/boos\\/.*|boo\\.fm\\/b.*|www\\.xiami\\.com\\/song\\/.*|xiami\\.com\\/song\\/.*|www\\.saynow\\.com\\/playMsg\\.html.*|www\\.saynow\\.com\\/playMsg\\.html.*|grooveshark\\.com\\/.*|radioreddit\\.com\\/songs.*|www\\.radioreddit\\.com\\/songs.*|radioreddit\\.com\\/\\?q=songs.*|www\\.radioreddit\\.com\\/\\?q=songs.*|www\\.gogoyoko\\.com\\/song\\/.*|hypem\\.com\\/premiere\\/.*|bop\\.fm\\/s\\/.*\\/.*|clyp\\.it\\/.*|www\\.dnbradio\\.com\\/.*|dnbradio\\.com\\/.*))|(https:\\/\\/(picasaweb\\.google\\.com.*\\/.*\\/.*#.*|picasaweb\\.google\\.com.*\\/lh\\/photo\\/.*|picasaweb\\.google\\.com.*\\/.*\\/.*|skitch\\.com\\/.*\\/.*\\/.*|img\\.skitch\\.com\\/.*|vidd\\.me\\/.*|vid\\.me\\/.*|gfycat\\.com\\/.*|.*youtube\\.com\\/watch.*|.*\\.youtube\\.com\\/v\\/.*|youtu\\.be\\/.*|.*\\.youtube\\.com\\/playlist.*|www\\.youtube\\.com\\/embed\\/.*|youtube\\.com\\/gif.*|www\\.youtube\\.com\\/gif.*|screen\\.yahoo\\.com\\/.*\\/.*|animoto\\.com\\/play\\/.*|video214\\.com\\/play\\/.*|www\\.streamio\\.com\\/api\\/v1\\/.*|streamio\\.com\\/api\\/v1\\/.*|vine\\.co\\/v\\/.*|www\\.vine\\.co\\/v\\/.*|mixbit\\.com\\/v\\/.*|www\\.brainshark\\.com\\/.*\\/.*|brainshark\\.com\\/.*\\/.*|23video\\.com\\/.*|.*\\.23video\\.com\\/.*|brainsonic\\.com\\/.*|.*\\.brainsonic\\.com\\/.*|www\\.reelhouse\\.org\\/.*|reelhouse\\.org\\/.*|www\\.allego\\.com\\/.*|clipter\\.com\\/c\\/.*|app\\.devhv\\.com\\/oembed\\/.*|sendvid\\.com\\/.*|clipmine\\.com\\/video\\/.*|clipmine\\.com\\/embed\\/.*|clippituser\\.tv\\/.*|www\\.clippituser\\.tv\\/.*|www\\.vimeo\\.com\\/.*|vimeo\\.com\\/.*|player\\.vimeo\\.com\\/.*|yahoo\\.com\\/movies\\/.*|.*\\.yahoo\\.com\\/movies\\/.*|app\\.wistia\\.com\\/embed\\/medias\\/.*|wistia\\.com\\/.*|.*\\.wistia\\.com\\/.*|.*\\.wi\\.st\\/.*|wi\\.st\\/.*|.*\\.looplogic\\.com\\/.*|khanacademy\\.org\\/.*|www\\.khanacademy\\.org\\/.*|.*vidyard\\.com\\/.*|.*\\.stream\\.co\\.jp\\/apiservice\\/.*|.*\\.stream\\.ne\\.jp\\/apiservice\\/.*|api\\.lovelive\\.tv\\/v1\\/.*|video\\.esri\\.com\\/.*|mix\\.office\\.com\\/watch\\/.*|mix\\.office\\.com\\/mix\\/.*|mix\\.office\\.com\\/embed\\/.*|mix\\.office\\.com\\/MyMixes\\/Details\\/.*|.*\\.iplayerhd\\.com\\/playerframe\\/.*|.*\\.iplayerhd\\.com\\/player\\/video\\/.*|plays\\.tv\\/video\\/.*|www\\.facebook\\.com\\/photo\\.php.*|www\\.facebook\\.com\\/video\\.php.*|www\\.facebook\\.com\\/.*\\/posts\\/.*|fb\\.me\\/.*|www\\.facebook\\.com\\/.*\\/photos\\/.*|www\\.facebook\\.com\\/.*\\/videos\\/.*|plus\\.google\\.com\\/.*|soundcloud\\.com\\/.*|soundcloud\\.com\\/.*\\/.*|soundcloud\\.com\\/.*\\/sets\\/.*|soundcloud\\.com\\/groups\\/.*|open\\.spotify\\.com\\/.*|play\\.spotify\\.com\\/.*|bop\\.fm\\/s\\/.*\\/.*|bop\\.fm\\/p\\/.*|bop\\.fm\\/a\\/.*|clyp\\.it\\/.*|sfx\\.io\\/.*)))', 'text', '', '', '', 81),

(@iCategoryId, 'sys_iframely_api_key', '_adm_stg_cpt_option_sys_iframely_api_key', '', 'digit', '', '', '', 90);


--
-- CATEGORY: Permalinks
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'permalinks', '_adm_stg_cpt_category_permalinks', 0, 9);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'permalinks_pages', '_adm_stg_cpt_option_permalinks_pages', 'on', 'checkbox', '', '', '', 1),
(@iCategoryId, 'permalinks_modules', '_adm_stg_cpt_option_permalinks_modules', 'on', 'checkbox', '', '', '', 2),
(@iCategoryId, 'permalinks_storage', '_adm_stg_cpt_option_permalinks_storage', 'on', 'checkbox', '', '', '', 3);


--
-- CATEGORY: Security
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'security', '_adm_stg_cpt_category_security', 0, 11);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_security_form_token_enable', '_adm_stg_cpt_option_sys_security_form_token_enable', 'on', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_security_form_token_lifetime', '_adm_stg_cpt_option_sys_security_form_token_lifetime', '86400', 'digit', '', '', '', 11),
(@iCategoryId, 'sys_recaptcha_key_public', '_adm_stg_cpt_option_sys_recaptcha_key_public', '', 'digit', '', '', '', 20),
(@iCategoryId, 'sys_recaptcha_key_private', '_adm_stg_cpt_option_sys_recaptcha_key_private', '', 'digit', '', '', '', 21),
(@iCategoryId, 'sys_add_nofollow', '_adm_stg_cpt_option_sys_add_nofollow', 'on', 'checkbox', '', '', '', 30);

--
-- CATEGORY: Site Settings
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'site_settings','_adm_stg_cpt_category_site_settings', 0, 12);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'site_title', '_adm_stg_cpt_option_site_title', 'Community', 'digit', '', '', '', 1),
(@iCategoryId, 'site_email', '_adm_stg_cpt_option_site_email', 'admin@example.com', 'digit', '', '', '', 2),
(@iCategoryId, 'site_email_notify', '_adm_stg_cpt_option_site_email_notify', 'admin@example.com', 'digit', '', '', '', 3),
(@iCategoryId, 'site_tour_home', '_adm_stg_cpt_option_site_tour_home', 'on', 'checkbox', '', '', '', 6),
(@iCategoryId, 'site_tour_studio', '_adm_stg_cpt_option_site_tour_studio', 'on', 'checkbox', '', '', '', 7),
(@iCategoryId, 'add_to_mobile_homepage', '_adm_stg_cpt_option_add_to_mobile_homepage', 'on', 'checkbox', '', '', '', 8),
(@iCategoryId, 'site_login_social_compact', '_adm_stg_cpt_option_site_login_social_compact', '', 'checkbox', '', '', '', 9),

(@iCategoryId, 'sys_autoupdate_system', '_adm_stg_cpt_option_sys_autoupdate_system', 'on', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_autoupdate_modules', '_adm_stg_cpt_option_sys_autoupdate_modules', 'on', 'checkbox', '', '', '', 11),
(@iCategoryId, 'sys_autoupdate_force_modified_files', '_adm_stg_cpt_option_sys_autoupdate_force_modified_files', '', 'checkbox', '', '', '', 12),

(@iCategoryId, 'sys_per_page_search_keyword_single', '_adm_stg_cpt_option_sys_per_page_search_keyword_single', '24', 'digit', '', '', '', 20),
(@iCategoryId, 'sys_per_page_search_keyword_plural', '_adm_stg_cpt_option_sys_per_page_search_keyword_plural', '3', 'digit', '', '', '', 21);

--
-- CATEGORY: Storage
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'storage', '_adm_stg_cpt_category_storage', 0, 13);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_storage_default', '_adm_stg_cpt_option_sys_storage_default', 'Local', 'select', 'Local,S3', '', '', 1),
(@iCategoryId, 'sys_storage_s3_access_key', '_adm_stg_cpt_option_sys_storage_s3_access_key', '', 'digit', '', '', '', 2),
(@iCategoryId, 'sys_storage_s3_secret_key', '_adm_stg_cpt_option_sys_storage_s3_secret_key', '', 'digit', '', '', '', 3),
(@iCategoryId, 'sys_storage_s3_bucket', '_adm_stg_cpt_option_sys_storage_s3_bucket', '', 'digit', '', '', '', 4),
(@iCategoryId, 'sys_storage_s3_domain', '_adm_stg_cpt_option_sys_storage_s3_domain', '', 'digit', '', '', '', 5);

--
-- CATEGORY: Account
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'account', '_adm_stg_cpt_category_account', 0, 14);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_account_online_time', '_adm_stg_cpt_option_sys_account_online_time', '5', 'digit', '', 'Avail', '_adm_stg_err_option_sys_account_online_time', 1),
(@iCategoryId, 'sys_account_autoapproval', '_adm_stg_cpt_option_sys_account_autoapproval', 'on', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_account_activation_letter', '_adm_stg_cpt_option_sys_account_activation_letter', '', 'checkbox', '', '', '', 11),
(@iCategoryId, 'sys_account_default_profile_type', '_adm_stg_cpt_option_sys_account_default_profile_type', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_profile_types";s:5:"class";s:20:"TemplServiceProfiles";}', '', '', 20),
(@iCategoryId, 'sys_account_limit_profiles_number', '_adm_stg_cpt_option_sys_account_limit_profiles_number', '0', 'digit', '', '', '', 21);

--
-- CATEGORY: ACL
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'acl', '_adm_stg_cpt_category_acl', 0, 15);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_acl_expire_notification_days', '_adm_stg_cpt_option_sys_acl_expire_notification_days', '1', 'digit', '', '', '', 1),
(@iCategoryId, 'sys_acl_expire_notify_once', '_adm_stg_cpt_option_sys_acl_expire_notify_once', 'on', 'checkbox', '', '', '', 2);

--
-- CATEGORY: Notifications
--
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'notifications', '_adm_stg_cpt_category_notifications', 0, 16);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'enable_notification_pruning', '_adm_stg_cpt_option_enable_notification_pruning', '', 'checkbox', '', '', '', 1),
(@iCategoryId, 'enable_notification_account', '_adm_stg_cpt_option_enable_notification_account', 'on', 'checkbox', '', '', '', 2);


--
-- Table structure for table `sys_options_mixes`
--
CREATE TABLE `sys_options_mixes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(64) NOT NULL default '',
  `category` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `title` varchar(64) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  `editable` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `sys_options_mixes2options`
--
CREATE TABLE `sys_options_mixes2options` (
  `option` varchar(64) NOT NULL default '',
  `mix_id` int(11) unsigned NOT NULL default '0',
  `value` mediumtext NOT NULL,
  UNIQUE KEY `value`(`option`, `mix_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_localization_categories` (
  `ID` int(6) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_localization_categories` VALUES
(1, 'System'),
(2, 'Custom');

CREATE TABLE `sys_localization_keys` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `IDCategory` int(6) unsigned NOT NULL default '0',
  `Key` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Key` (`Key`),
  FULLTEXT KEY `KeyFilter` (`Key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_localization_languages` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(5) NOT NULL default '',
  `Flag` varchar(2) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Enabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_localization_strings` (
  `IDKey` int(10) unsigned NOT NULL default '0',
  `IDLanguage` int(10) unsigned NOT NULL default '0',
  `String` mediumtext NOT NULL,
  PRIMARY KEY  (`IDKey`,`IDLanguage`),
  FULLTEXT KEY `String` (`String`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_acl_actions` (  
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Module` varchar(32) NOT NULL,
  `Name` varchar(255) NOT NULL default '',
  `AdditionalParamName` varchar(80) default NULL,
  `Title` varchar(255) NOT NULL,
  `Desc` varchar(255) NOT NULL,
  `Countable` tinyint(4) NOT NULL DEFAULT '0',
  `DisabledForLevels` int(10) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY  (`ID`),
  FULLTEXT KEY `ModuleAndName` (`Module`, `Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'connect', NULL, '_sys_acl_action_connect', '', 0, 3);
SET @iIdActionConnect = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'vote', NULL, '_sys_acl_action_vote', '', 0, 0);
SET @iIdActionVote = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'report', NULL, '_sys_acl_action_report', '', 0, 0);
SET @iIdActionReport = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'report_view', NULL, '_sys_acl_action_report_view', '', 0, 0);
SET @iIdActionReportView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'favorite', NULL, '_sys_acl_action_favorite', '', 0, 0);
SET @iIdActionFavorite = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'favorite_view', NULL, '_sys_acl_action_favorite_view', '', 0, 0);
SET @iIdActionFavoriteView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'feature', NULL, '_sys_acl_action_feature', '', 0, 0);
SET @iIdActionFeature = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'view_view', NULL, '_sys_acl_action_view_view', '', 0, 0);
SET @iIdActionViewView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'view_view_viewers', NULL, '_sys_acl_action_view_view_viewers', '', 0, 0);
SET @iIdActionViewViewViewers = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments post', NULL, '_sys_acl_action_comments_post', '', 0, 3);
SET @iIdActionCmtPost = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments edit own', NULL, '_sys_acl_action_comments_edit_own', '', 0, 3);
SET @iIdActionCmtEditOwn = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments remove own', NULL, '_sys_acl_action_comments_remove_own', '', 0, 3);
SET @iIdActionCmtRemoveOwn = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments edit all', NULL, '_sys_acl_action_comments_edit_all', '', 0, 3);
SET @iIdActionCmtEditAll = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments remove all', NULL, '_sys_acl_action_comments_remove_all', '', 0, 3);
SET @iIdActionCmtRemoveAll = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'create account', NULL, '_sys_acl_action_create_account', '_sys_acl_action_create_account_desc', 0, 2147483646);
SET @iIdActionAccountCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'delete account', NULL, '_sys_acl_action_delete_account', '_sys_acl_action_delete_account_desc', 0, 1);
SET @iIdActionAccountDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'set acl level', NULL, '_sys_acl_action_set_acl_level', '_sys_acl_action_set_acl_level_desc', 0, 3);
SET @iIdActionSetAclLevel = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'chart view', NULL, '_sys_acl_action_chart_view', '_sys_acl_action_chart_view_desc', 0, 3);
SET @iIdActionChartView = LAST_INSERT_ID();

CREATE TABLE `sys_acl_actions_track` (
  `IDAction` int(10) unsigned NOT NULL DEFAULT '0',
  `IDMember` int(10) unsigned NOT NULL default '0',
  `ActionsLeft` int(10) unsigned NOT NULL DEFAULT '0',
  `ValidSince` datetime default NULL,
  PRIMARY KEY  (`IDAction`,`IDMember`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `sys_acl_matrix` (
  `IDLevel` int(10) unsigned NOT NULL DEFAULT '0',
  `IDAction` int(10) unsigned NOT NULL DEFAULT '0',
  `AllowedCount` int(10) unsigned DEFAULT NULL,
  `AllowedPeriodLen` int(10) unsigned DEFAULT NULL,
  `AllowedPeriodStart` datetime default NULL,
  `AllowedPeriodEnd` datetime default NULL,
  `AdditionalParamValue` varchar(255) default NULL,
  PRIMARY KEY  (`IDLevel`,`IDAction`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

-- connect
(@iStandard, @iIdActionConnect),
(@iModerator, @iIdActionConnect),
(@iAdministrator, @iIdActionConnect),
(@iPremium, @iIdActionConnect),

-- vote 
(@iStandard, @iIdActionVote),
(@iModerator, @iIdActionVote),
(@iAdministrator, @iIdActionVote),
(@iPremium, @iIdActionVote),

-- report 
(@iStandard, @iIdActionReport),
(@iModerator, @iIdActionReport),
(@iAdministrator, @iIdActionReport),
(@iPremium, @iIdActionReport),

-- report view 
(@iModerator, @iIdActionReportView),
(@iAdministrator, @iIdActionReportView),

-- favorite 
(@iStandard, @iIdActionFavorite),
(@iModerator, @iIdActionFavorite),
(@iAdministrator, @iIdActionFavorite),
(@iPremium, @iIdActionFavorite),

-- favorite view
(@iUnauthenticated, @iIdActionFavoriteView),
(@iAccount, @iIdActionFavoriteView),
(@iStandard, @iIdActionFavoriteView),
(@iUnconfirmed, @iIdActionFavoriteView),
(@iPending, @iIdActionFavoriteView),
(@iModerator, @iIdActionFavoriteView),
(@iAdministrator, @iIdActionFavoriteView),
(@iPremium, @iIdActionFavoriteView),

-- feature 
(@iModerator, @iIdActionFeature),
(@iAdministrator, @iIdActionFeature),

-- view view
(@iUnauthenticated, @iIdActionViewView),
(@iAccount, @iIdActionViewView),
(@iStandard, @iIdActionViewView),
(@iUnconfirmed, @iIdActionViewView),
(@iPending, @iIdActionViewView),
(@iModerator, @iIdActionViewView),
(@iAdministrator, @iIdActionViewView),
(@iPremium, @iIdActionViewView),

-- view view viewers
(@iModerator, @iIdActionViewViewViewers),
(@iAdministrator, @iIdActionViewViewViewers),

-- comments post
(@iStandard, @iIdActionCmtPost),
(@iModerator, @iIdActionCmtPost),
(@iAdministrator, @iIdActionCmtPost),
(@iPremium, @iIdActionCmtPost),

-- comments edit own
(@iStandard, @iIdActionCmtEditOwn),
(@iModerator, @iIdActionCmtEditOwn),
(@iAdministrator, @iIdActionCmtEditOwn),
(@iPremium, @iIdActionCmtEditOwn),

-- comments remove own
(@iStandard, @iIdActionCmtRemoveOwn),
(@iModerator, @iIdActionCmtRemoveOwn),
(@iAdministrator, @iIdActionCmtRemoveOwn),
(@iPremium, @iIdActionCmtRemoveOwn),

-- comments edit all
(@iModerator, @iIdActionCmtEditAll),
(@iAdministrator, @iIdActionCmtEditAll),

-- comments remove all
(@iModerator, @iIdActionCmtRemoveAll),
(@iAdministrator, @iIdActionCmtRemoveAll),

-- account create
(@iUnauthenticated, @iIdActionAccountCreate),

-- account delete
(@iAccount, @iIdActionAccountDelete),
(@iStandard, @iIdActionAccountDelete),
(@iUnconfirmed, @iIdActionAccountDelete),
(@iPending, @iIdActionAccountDelete),
(@iModerator, @iIdActionAccountDelete),
(@iAdministrator, @iIdActionAccountDelete),
(@iPremium, @iIdActionAccountDelete),

-- set acl level
(@iAdministrator, @iIdActionSetAclLevel),

-- view charts
(@iAdministrator, @iIdActionChartView);


CREATE TABLE `sys_acl_levels` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  `Active` enum('yes','no') NOT NULL default 'no',
  `Purchasable` enum('yes','no') NOT NULL default 'yes',
  `Removable` enum('yes','no') NOT NULL default 'yes',
  `QuotaSize` int(11) NOT NULL,
  `QuotaNumber` int(11) NOT NULL,
  `QuotaMaxFileSize` int(11) NOT NULL,
  `Order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`),
  FULLTEXT KEY `Description` (`Description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_acl_levels` (`ID`, `Name`, `Icon`, `Description`, `Active`, `Purchasable`, `Removable`, `QuotaSize`, `QuotaNumber`, `QuotaMaxFileSize`, `Order`) VALUES
(1, '_adm_prm_txt_level_unauthenticated', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 1),
(2, '_adm_prm_txt_level_account', 'user col-green1', '', 'yes', 'no', 'no', 0, 0, 0, 2),
(3, '_adm_prm_txt_level_standard', 'user col-red1', '', 'yes', 'no', 'no', 0, 0, 0, 3),
(4, '_adm_prm_txt_level_unconfirmed', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 4),
(5, '_adm_prm_txt_level_pending', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 5),
(6, '_adm_prm_txt_level_suspended', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 6),
(7, '_adm_prm_txt_level_moderator', 'user-secret bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 7),
(8, '_adm_prm_txt_level_administrator', 'user-secret bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 8),
(9, '_adm_prm_txt_level_premium', 'user col-red3', '', 'yes', 'yes', 'no', 0, 0, 0, 9);


-- --------------------------------------------------------

--
-- Table structure for table `boon_sys_sessions`
--

CREATE TABLE IF NOT EXISTS `sys_sessions` (
  `id` varchar(32) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `data` text,
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_acl_levels_members`
--

CREATE TABLE `sys_acl_levels_members` (
  `IDMember` int(10) unsigned NOT NULL default '0',
  `IDLevel` int(10) unsigned NOT NULL DEFAULT '0',
  `DateStarts` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateExpires` datetime default NULL,
  `TransactionID` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`IDMember`,`IDLevel`,`DateStarts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `receive_updates` tinyint(4) NOT NULL DEFAULT '1',
  `receive_news` tinyint(4) NOT NULL DEFAULT '1',
  `password` varchar(40) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '1',
  `lang_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `changed` int(11) NOT NULL DEFAULT '0',
  `logged` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `added` (`added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `sys_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `status` enum('active','pending','suspended') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_type_content` (`account_id`,`type`,`content_id`),
  KEY `content_type` (`content_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


--
-- Table structure for table 'sys_statistics'
--

CREATE TABLE `sys_statistics` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `module` varchar(32) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `icon` varchar(32) NOT NULL default '',
  `query` text NOT NULL default '',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES
('system', 'sys_accounts', '_sys_accounts', '', 'user', 'SELECT COUNT(*) FROM `sys_accounts` WHERE 1', 1);


-- --------------------------------------------------------

--
-- Table structure for table 'sys_objects_rss'
--

CREATE TABLE `sys_objects_rss` (
  `id` int(11) NOT NULL auto_increment,
  `object` varchar(64) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_rss` (`object`, `class_name`, `class_file`) VALUES
('sys_boonex', 'BxDolRssBoonEx', ''),
('sys_page_block', 'BxDolRssPageBlock', ''),
('sys_studio_page_help', 'BxDolStudioRssPageHelp', ''),
('sys_studio_module_help', 'BxDolStudioRssModuleHelp', '');


-- --------------------------------------------------------

--
-- Table structure for table 'sys_objects_search'
--

CREATE TABLE `sys_objects_search` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `ObjectName` varchar(64) NOT NULL  default '',
  `Title` varchar(50) NOT NULL default '',
  `Order` int(11) NOT NULL,
  `GlobalSearch` tinyint(4) NOT NULL DEFAULT '1',
  `ClassName` varchar(50) NOT NULL  default '',
  `ClassPath` varchar(100) NOT NULL  default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table 'sys_objects_search_extended'
--

CREATE TABLE `sys_objects_search_extended` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `object` varchar(64) NOT NULL  default '',
  `object_content_info` varchar(64) NOT NULL  default '',
  `module` varchar(32) NOT NULL  default '',
  `title` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `class_name` varchar(32) NOT NULL  default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'sys_search_extended_fields'
--

CREATE TABLE `sys_search_extended_fields` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `object` varchar(64) NOT NULL  default '',
  `name` varchar(255) NOT NULL  default '',
  `type` varchar(32) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `values` text NOT NULL default '',
  `search_type` varchar(32) NOT NULL  default '',
  `search_value` varchar(255) NOT NULL default '',
  `search_operator` varchar(32) NOT NULL  default '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `field` (`object`, `name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_objects_vote`
--

CREATE TABLE `sys_objects_vote` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `Name` varchar(50) NOT NULL default '',
  `TableMain` varchar(50) NOT NULL default '',
  `TableTrack` varchar(50) NOT NULL default '',
  `PostTimeout` int(11) NOT NULL default '0',
  `MinValue` tinyint(4) NOT NULL default '1',
  `MaxValue` tinyint(4) NOT NULL default '5',
  `IsUndo` tinyint(1) NOT NULL default '0',
  `IsOn` tinyint(1) NOT NULL default '1',
  `TriggerTable` varchar(32) NOT NULL default '',
  `TriggerFieldId` varchar(32) NOT NULL default '',
  `TriggerFieldAuthor` varchar(32) NOT NULL default '',
  `TriggerFieldRate` varchar(32) NOT NULL default '',
  `TriggerFieldRateCount` varchar(32) NOT NULL default '',
  `ClassName` varchar(32) NOT NULL default '',
  `ClassFile` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('sys_cmts', 'sys_cmts_votes', 'sys_cmts_votes_track', '604800', '1', '1', '0', '1', 'sys_cmts_ids', 'id', '', 'rate', 'votes', '', '');


-- -------------------------------------------------------


CREATE TABLE `sys_modules` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(16) NOT NULL default 'module',
  `name` varchar(32) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `vendor` varchar(64) NOT NULL default '',
  `version` varchar(32) NOT NULL default '',
  `help_url` varchar(128) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',  
  `uri` varchar(32) NOT NULL default '',
  `class_prefix` varchar(32) NOT NULL default '',
  `db_prefix` varchar(32) NOT NULL default '',
  `lang_category` varchar(64) NOT NULL default '',
  `dependencies` varchar(255) NOT NULL default '',
  `date` int(11) unsigned NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  `pending_uninstall` tinyint(4) NOT NULL,
  `hash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `path` (`path`),
  UNIQUE KEY `uri` (`uri`),
  UNIQUE KEY `class_prefix` (`class_prefix`),
  UNIQUE KEY `db_prefix` (`db_prefix`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_modules` (`type`, `name`, `title`, `vendor`, `version`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `dependencies`, `date`, `enabled`) VALUES
('module', 'system', 'System', 'UNA, Inc', '9', '', 'system', 'Bx', 'sys_', 'System', '', 0, 1);


CREATE TABLE `sys_modules_file_tracks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `module_id` int(11) unsigned NOT NULL default '0',
  `file` varchar(255) NOT NULL default '',
  `hash` varchar(64) NOT NULL default '',  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `sys_modules_relations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `module` varchar(32) NOT NULL default '',
  `on_install` varchar(255) NOT NULL default '',
  `on_uninstall` varchar(255) NOT NULL default '',
  `on_enable` varchar(255) NOT NULL default '',
  `on_disable` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sys_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE `sys_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE `sys_images_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE `sys_images_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_cmts_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_images_preview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_images2entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  `image_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `image` (`system_id`,`cmt_id`,`image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_cmt_id` (`system_id`,`cmt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE `sys_injections` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `page_index` int(11) NOT NULL default '0',
  `key` varchar(128) NOT NULL default '',
  `type` enum('text', 'service') NOT NULL default 'text',
  `data` text NOT NULL default '',
  `replace` TINYINT NOT NULL DEFAULT '0',
  `active` TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('update_cache', 150, 'injection_head', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_cache_updater";s:6:"params";a:0:{}s:5:"class";s:19:"TemplStudioLauncher";}', 0, 1),
('live_updates', 0, 'injection_head', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:4:"init";s:6:"params";a:0:{}s:5:"class";s:24:"TemplLiveUpdatesServices";}', 0, 1),
('sys_head', 0, 'injection_head', 'text', '', 0, 1),
('sys_body', 0, 'injection_footer', 'text', '', 0, 1);

-- --------------------------------------------------------

CREATE TABLE `sys_injections_admin` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `page_index` int(11) NOT NULL default '0',
  `key` varchar(128) NOT NULL default '',
  `type` enum('text','service') NOT NULL default 'text',
  `data` text NOT NULL,
  `replace` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE `sys_permalinks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `standard` varchar(128) NOT NULL DEFAULT '',
  `permalink` varchar(128) NOT NULL DEFAULT '',
  `check` varchar(64) NOT NULL DEFAULT '',
  `compare_by_prefix` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `check` (`standard`,`permalink`,`check`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_permalinks` (`standard`, `permalink`, `check`, `compare_by_prefix`) VALUES
('page.php?i=', 'page/', 'permalinks_pages', 1),
('modules/?r=', 'm/', 'permalinks_modules', 1),
('storage.php?o=', 's/', 'permalinks_storage', 1);


-- --------------------------------------------------------


CREATE TABLE `sys_alerts_handlers` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `class` varchar(128) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `service_call` text NOT NULL default '', 
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_alerts` (
  `id` int(11) unsigned NOT NULL auto_increment,  
  `unit` varchar(128) NOT NULL default '',
  `action` varchar(32) NOT NULL default 'none',
  `handler_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alert_handler` (`unit`, `action`, `handler_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_studio_settings_save_design', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:28:"alert_response_settings_save";s:6:"params";a:0:{}s:5:"class";s:25:"TemplStudioDesignServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iIdHandler);

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_settings_sys_images_custom_file_deleted', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:45:"alert_response_sys_images_custom_file_deleted";s:6:"params";a:0:{}s:5:"class";s:27:"TemplStudioSettingsServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_images_custom', 'file_deleted', @iIdHandler);

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_settings_storage_change', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:37:"alert_response_process_storage_change";s:5:"class";s:13:"TemplServices";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_installed', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:32:"alert_response_process_installed";s:5:"class";s:13:"TemplServices";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'installed', @iHandler);

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_connections', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:26:"alert_response_connections";s:5:"class";s:23:"TemplServiceConnections";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_profiles_friends', 'connection_added', @iHandler),
('sys_profiles_friends', 'connection_removed', @iHandler);

-- --------------------------------------------------------


CREATE TABLE `sys_objects_report` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `table_main` varchar(32) NOT NULL,
  `table_track` varchar(32) NOT NULL,
  `is_on` tinyint(4) NOT NULL default '1',
  `base_url` varchar(256) NOT NULL default '',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_count` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_objects_view` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `table_track` varchar(32) NOT NULL,
  `period` int(11) NOT NULL default '86400',
  `is_on` tinyint(4) NOT NULL default '1',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_count` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_objects_favorite` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `table_track` varchar(32) NOT NULL,
  `is_on` tinyint(4) NOT NULL default '1',
  `is_undo` tinyint(4) NOT NULL default '1',
  `is_public` tinyint(4) NOT NULL default '1',
  `base_url` varchar(256) NOT NULL default '',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_count` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE `sys_objects_feature` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `is_on` tinyint(4) NOT NULL default '1',
  `is_undo` tinyint(4) NOT NULL default '1',
  `base_url` varchar(256) NOT NULL default '',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_flag` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `sys_objects_chart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `table` varchar(255) NOT NULL,
  `field_date_ts` varchar(255) NOT NULL,
  `field_date_dt` varchar(255) NOT NULL,
  `field_status` varchar(255) NOT NULL,
  `column_date` int(11) NOT NULL DEFAULT '0',
  `column_count` int(11) NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `options` text NOT NULL,
  `query` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('sys_accounts_growth', '_sys_chart_accounts_growth', 'sys_accounts', 'added', '', '', '', 1, 1, 'BxDolChartGrowth', ''),
('sys_accounts_growth_speed', '_sys_chart_accounts_growth_speed', 'sys_accounts', 'added', '', '', '', 1, 1, 'BxDolChartGrowthSpeed', '');

-- --------------------------------------------------------

CREATE TABLE `sys_objects_content_info` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `title` varchar(128) NOT NULL,
  `alert_unit` varchar(32) NOT NULL,
  `alert_action_add` varchar(32) NOT NULL,
  `alert_action_update` varchar(32) NOT NULL,
  `alert_action_delete` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `alert_add` (`alert_unit` ,`alert_action_add`),
  UNIQUE KEY `alert_update` (`alert_unit` ,`alert_action_update`),
  UNIQUE KEY `alert_delete` (`alert_unit` ,`alert_action_delete`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

CREATE TABLE `sys_content_info_grids` (
  `id` int(11) NOT NULL auto_increment,
  `object` varchar(64) NOT NULL,
  `grid_object` varchar(64) NOT NULL,
  `grid_field_id` varchar(64) NOT NULL,
  `condition` text NOT NULL default '',
  `selection` varchar(256) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `grid_object` (`grid_object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

CREATE TABLE `sys_objects_privacy` (
  `id` int(11) NOT NULL auto_increment,
  `object` varchar(64) NOT NULL default '',
  `module` varchar(64) NOT NULL default '',
  `action` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `default_group` varchar(255) NOT NULL default '1',
  `table` varchar(255) NOT NULL default '',
  `table_field_id` varchar(255) NOT NULL default '',
  `table_field_author` varchar(255) NOT NULL default '',
  `override_class_name` varchar(255) NOT NULL default '',
  `override_class_file` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `action` (`module`, `action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_privacy_defaults` (  
  `owner_id` int(11) NOT NULL default '0',
  `action_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`owner_id`, `action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_privacy_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `check` text NOT NULL default '',
  `active` tinyint(4) NOT NULL default '1',
  `visible` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_privacy_groups`(`id`, `title`, `check`, `active`, `visible`) VALUES
('1', '', '', 1, 0),
('2', '_sys_ps_group_title_me_only', '@me_only', 1, 1),
('3', '_sys_ps_group_title_public', '@public', 1, 1),
('4', '_sys_ps_group_title_members', '@members', 0, 0),
('5', '_sys_ps_group_title_friends', '@friends', 1, 1);

-- --------------------------------------------------------

CREATE TABLE `sys_cron_jobs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `time` varchar(128) NOT NULL default '*',
  `class` varchar(128) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `service_call` text NOT NULL default '', 
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('pruning', '0 0 * * *', 'BxDolCronPruning', 'inc/classes/BxDolCronPruning.php', ''),
('sys_acl', '0 0 * * *', 'BxDolCronAcl', 'inc/classes/BxDolCronAcl.php', ''),
('sys_account', '0 0 * * *', 'BxDolCronAccount', 'inc/classes/BxDolCronAccount.php', ''),
('sys_upgrade', '0 3 * * *', 'BxDolCronUpgradeCheck', 'inc/classes/BxDolCronUpgradeCheck.php', ''),
('sys_upgrade_modules', '30 2 * * *', 'BxDolCronUpgradeModulesCheck', 'inc/classes/BxDolCronUpgradeModulesCheck.php', ''),
('sys_storage', '* * * * *', 'BxDolCronStorage', 'inc/classes/BxDolCronStorage.php', ''),
('sys_transcoder', '* * * * *', 'BxDolCronTranscoder', 'inc/classes/BxDolCronTranscoder.php', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sys_objects_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `engine` varchar(32) NOT NULL,
  `params` text NOT NULL,
  `token_life` int(11) NOT NULL,
  `cache_control` int(11) NOT NULL,
  `levels` tinyint(4) NOT NULL,
  `table_files` varchar(64) NOT NULL,
  `ext_mode` enum('allow-deny','deny-allow') NOT NULL,
  `ext_allow` text NOT NULL,
  `ext_deny` text NOT NULL,
  `quota_size` int(11) NOT NULL,
  `current_size` int(11) NOT NULL,
  `quota_number` int(11) NOT NULL,
  `current_number` int(11) NOT NULL,
  `max_file_size` int(11) NOT NULL,
  `ts` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_images', 'Local', '', 360, 2592000, 0, 'sys_images', 'allow-deny', 'jpg,jpeg,jpe,gif,png,svg', '', 0, 0, 0, 0, 0, 0),
('sys_images_custom', 'Local', '', 360, 2592000, 0, 'sys_images_custom', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('sys_images_resized', 'Local', '', 360, 2592000, 0, 'sys_images_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,svg', '', 0, 0, 0, 0, 0, 0),
('sys_cmts_images', 'Local', '', 360, 2592000, 3, 'sys_cmts_images', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('sys_cmts_images_preview', 'Local', '', 360, 2592000, 3, 'sys_cmts_images_preview', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('sys_transcoder_queue_files', 'Local', '', 3600, 2592000, 0, 'sys_transcoder_queue_files', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,divx,xvid,3gp,webm,jpg', '', 0, 0, 0, 0, 0, 0),
('sys_files', 'Local', '', 360, 2592000, 3, 'sys_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `sys_storage_user_quotas` (
  `profile_id` int(11) NOT NULL,
  `current_size` int(11) NOT NULL,
  `current_number` int(11) NOT NULL,
  `ts` int(11) NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_storage_tokens` (
  `id` int(11) NOT NULL,
  `object` varchar(64) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`,`object`,`hash`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_storage_ghosts` (
  `id` int(11) NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `object` varchar(64) NOT NULL,
  `content_id` int(11) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`,`object`),
  KEY `created` (`created`),
  KEY `profile_object_content` (`profile_id`,`object`,`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_storage_deletions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `file_id` int(11) NOT NULL,
  `requested` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_file_id` (`object`,`file_id`),
  KEY `requested` (`requested`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_storage_mime_types` (
  `ext` varchar(32) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `icon_font` varchar(255) NOT NULL,
  PRIMARY KEY (`ext`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('ez', 'application/andrew-inset', '', ''),
('aw', 'application/applixware', '', ''),
('atom', 'application/atom+xml', '', ''),
('atomcat', 'application/atomcat+xml', '', ''),
('atomsvc', 'application/atomsvc+xml', '', ''),
('ccxml', 'application/ccxml+xml', '', ''),
('cdmia', 'application/cdmi-capability', '', ''),
('cdmic', 'application/cdmi-container', '', ''),
('cdmid', 'application/cdmi-domain', '', ''),
('cdmio', 'application/cdmi-object', '', ''),
('cdmiq', 'application/cdmi-queue', '', ''),
('cu', 'application/cu-seeme', '', ''),
('davmount', 'application/davmount+xml', '', ''),
('dbk', 'application/docbook+xml', '', ''),
('dssc', 'application/dssc+der', '', ''),
('xdssc', 'application/dssc+xml', '', ''),
('ecma', 'application/ecmascript', '', ''),
('emma', 'application/emma+xml', '', ''),
('epub', 'application/epub+zip', '', ''),
('exi', 'application/exi', '', ''),
('pfr', 'application/font-tdpfr', '', ''),
('gml', 'application/gml+xml', '', ''),
('gpx', 'application/gpx+xml', '', ''),
('gxf', 'application/gxf', '', ''),
('stk', 'application/hyperstudio', '', ''),
('ink', 'application/inkml+xml', '', ''),
('inkml', 'application/inkml+xml', '', ''),
('ipfix', 'application/ipfix', '', ''),
('jar', 'application/java-archive', '', ''),
('ser', 'application/java-serialized-object', '', ''),
('class', 'application/java-vm', '', ''),
('js', 'application/javascript', '', 'file-code-o'),
('json', 'application/json', '', 'file-code-o'),
('jsonml', 'application/jsonml+json', '', ''),
('lostxml', 'application/lost+xml', '', ''),
('hqx', 'application/mac-binhex40', '', ''),
('cpt', 'application/mac-compactpro', '', ''),
('mads', 'application/mads+xml', '', ''),
('mrc', 'application/marc', '', ''),
('mrcx', 'application/marcxml+xml', '', ''),
('ma', 'application/mathematica', '', ''),
('nb', 'application/mathematica', '', ''),
('mb', 'application/mathematica', '', ''),
('mathml', 'application/mathml+xml', '', ''),
('mbox', 'application/mbox', '', 'file-code-o'),
('mscml', 'application/mediaservercontrol+xml', '', ''),
('metalink', 'application/metalink+xml', '', ''),
('meta4', 'application/metalink4+xml', '', ''),
('mets', 'application/mets+xml', '', ''),
('mods', 'application/mods+xml', '', ''),
('m21', 'application/mp21', '', ''),
('mp21', 'application/mp21', '', ''),
('mp4s', 'application/mp4', '', ''),
('doc', 'application/msword', 'mime-type-document.png', 'file-word-o'),
('dot', 'application/msword', '', ''),
('mxf', 'application/mxf', '', ''),
('bin', 'application/octet-stream', '', ''),
('dms', 'application/octet-stream', '', ''),
('lrf', 'application/octet-stream', '', ''),
('mar', 'application/octet-stream', '', ''),
('so', 'application/octet-stream', '', ''),
('dist', 'application/octet-stream', '', ''),
('distz', 'application/octet-stream', '', ''),
('pkg', 'application/octet-stream', '', ''),
('bpk', 'application/octet-stream', '', ''),
('dump', 'application/octet-stream', '', ''),
('elc', 'application/octet-stream', '', ''),
('deploy', 'application/octet-stream', '', ''),
('mobipocket-ebook', 'application/octet-stream', '', ''),
('oda', 'application/oda', '', ''),
('opf', 'application/oebps-package+xml', '', ''),
('ogx', 'application/ogg', '', ''),
('omdoc', 'application/omdoc+xml', '', ''),
('onetoc', 'application/onenote', '', ''),
('onetoc2', 'application/onenote', '', ''),
('onetmp', 'application/onenote', '', ''),
('onepkg', 'application/onenote', '', ''),
('oxps', 'application/oxps', '', ''),
('xer', 'application/patch-ops-error+xml', '', ''),
('pdf', 'application/pdf', 'mime-type-document.png', 'file-pdf-o'),
('pgp', 'application/pgp-encrypted', '', 'file-code-o'),
('asc', 'application/pgp-signature', '', 'file-code-o'),
('sig', 'application/pgp-signature', '', 'file-code-o'),
('prf', 'application/pics-rules', '', ''),
('p10', 'application/pkcs10', 'mime-type-vector.png', ''),
('p7m', 'application/pkcs7-mime', '', ''),
('p7c', 'application/pkcs7-mime', '', ''),
('p7s', 'application/pkcs7-signature', '', ''),
('p8', 'application/pkcs8', '', ''),
('ac', 'application/pkix-attr-cert', '', ''),
('cer', 'application/pkix-cert', '', ''),
('crl', 'application/pkix-crl', '', ''),
('pkipath', 'application/pkix-pkipath', '', ''),
('pki', 'application/pkixcmp', '', ''),
('pls', 'application/pls+xml', '', ''),
('ai', 'application/postscript', 'mime-type-vector.png', ''),
('eps', 'application/postscript', '', ''),
('ps', 'application/postscript', 'mime-type-vector.png', ''),
('cww', 'application/prs.cww', '', ''),
('pskcxml', 'application/pskc+xml', '', ''),
('rdf', 'application/rdf+xml', '', 'file-code-o'),
('rif', 'application/reginfo+xml', '', ''),
('rnc', 'application/relax-ng-compact-syntax', '', ''),
('rl', 'application/resource-lists+xml', '', ''),
('rld', 'application/resource-lists-diff+xml', '', ''),
('rs', 'application/rls-services+xml', '', ''),
('gbr', 'application/rpki-ghostbusters', '', ''),
('mft', 'application/rpki-manifest', '', ''),
('roa', 'application/rpki-roa', '', ''),
('rsd', 'application/rsd+xml', '', ''),
('rss', 'application/rss+xml', '', 'file-code-o'),
('rtf', 'application/rtf', 'mime-type-document.png', ''),
('sbml', 'application/sbml+xml', '', ''),
('scq', 'application/scvp-cv-request', '', ''),
('scs', 'application/scvp-cv-response', '', ''),
('spq', 'application/scvp-vp-request', '', ''),
('spp', 'application/scvp-vp-response', '', ''),
('sdp', 'application/sdp', 'mime-type-presentation.png', 'file-powerpoint-o'),
('setpay', 'application/set-payment-initiation', '', ''),
('setreg', 'application/set-registration-initiation', '', ''),
('shf', 'application/shf+xml', '', ''),
('smi', 'application/smil+xml', '', ''),
('smil', 'application/smil+xml', '', ''),
('rq', 'application/sparql-query', '', 'file-code-o'),
('srx', 'application/sparql-results+xml', '', ''),
('gram', 'application/srgs', '', ''),
('grxml', 'application/srgs+xml', '', ''),
('sru', 'application/sru+xml', '', ''),
('ssdl', 'application/ssdl+xml', '', ''),
('ssml', 'application/ssml+xml', '', ''),
('tei', 'application/tei+xml', '', ''),
('teicorpus', 'application/tei+xml', '', ''),
('tfi', 'application/thraud+xml', '', ''),
('tsd', 'application/timestamped-data', '', ''),
('plb', 'application/vnd.3gpp.pic-bw-large', '', ''),
('psb', 'application/vnd.3gpp.pic-bw-small', '', ''),
('pvb', 'application/vnd.3gpp.pic-bw-var', '', ''),
('tcap', 'application/vnd.3gpp2.tcap', '', ''),
('pwn', 'application/vnd.3m.post-it-notes', '', ''),
('aso', 'application/vnd.accpac.simply.aso', '', ''),
('imp', 'application/vnd.accpac.simply.imp', '', ''),
('acu', 'application/vnd.acucobol', '', ''),
('atc', 'application/vnd.acucorp', '', ''),
('acutc', 'application/vnd.acucorp', '', ''),
('air', 'application/vnd.adobe.air-application-installer-package+zip', '', ''),
('fcdt', 'application/vnd.adobe.formscentral.fcdt', '', ''),
('fxp', 'application/vnd.adobe.fxp', '', ''),
('fxpl', 'application/vnd.adobe.fxp', '', ''),
('xdp', 'application/vnd.adobe.xdp+xml', '', ''),
('xfdf', 'application/vnd.adobe.xfdf', '', ''),
('ahead', 'application/vnd.ahead.space', '', ''),
('azf', 'application/vnd.airzip.filesecure.azf', '', ''),
('azs', 'application/vnd.airzip.filesecure.azs', '', ''),
('azw', 'application/vnd.amazon.ebook', '', ''),
('acc', 'application/vnd.americandynamics.acc', '', ''),
('ami', 'application/vnd.amiga.ami', '', ''),
('apk', 'application/vnd.android.package-archive', '', ''),
('cii', 'application/vnd.anser-web-certificate-issue-initiation', '', ''),
('fti', 'application/vnd.anser-web-funds-transfer-initiation', '', ''),
('atx', 'application/vnd.antix.game-component', '', ''),
('mpkg', 'application/vnd.apple.installer+xml', '', ''),
('m3u8', 'application/vnd.apple.mpegurl', '', ''),
('swi', 'application/vnd.aristanetworks.swi', '', ''),
('iota', 'application/vnd.astraea-software.iota', '', ''),
('aep', 'application/vnd.audiograph', '', ''),
('mpm', 'application/vnd.blueice.multipass', '', ''),
('bmi', 'application/vnd.bmi', '', ''),
('rep', 'application/vnd.businessobjects', '', ''),
('cdxml', 'application/vnd.chemdraw+xml', '', ''),
('mmd', 'application/vnd.chipnuts.karaoke-mmd', '', ''),
('cdy', 'application/vnd.cinderella', '', ''),
('cla', 'application/vnd.claymore', '', ''),
('rp9', 'application/vnd.cloanto.rp9', '', ''),
('c4g', 'application/vnd.clonk.c4group', '', ''),
('c4d', 'application/vnd.clonk.c4group', '', ''),
('c4f', 'application/vnd.clonk.c4group', '', ''),
('c4p', 'application/vnd.clonk.c4group', '', ''),
('c4u', 'application/vnd.clonk.c4group', '', ''),
('c11amc', 'application/vnd.cluetrust.cartomobile-config', '', ''),
('c11amz', 'application/vnd.cluetrust.cartomobile-config-pkg', '', ''),
('csp', 'application/vnd.commonspace', '', ''),
('cdbcmsg', 'application/vnd.contact.cmsg', '', ''),
('cmc', 'application/vnd.cosmocaller', '', ''),
('clkx', 'application/vnd.crick.clicker', '', ''),
('clkk', 'application/vnd.crick.clicker.keyboard', '', ''),
('clkp', 'application/vnd.crick.clicker.palette', '', ''),
('clkt', 'application/vnd.crick.clicker.template', '', ''),
('clkw', 'application/vnd.crick.clicker.wordbank', '', ''),
('wbs', 'application/vnd.criticaltools.wbs+xml', '', ''),
('pml', 'application/vnd.ctc-posml', '', ''),
('ppd', 'application/vnd.cups-ppd', '', ''),
('car', 'application/vnd.curl.car', '', ''),
('pcurl', 'application/vnd.curl.pcurl', '', ''),
('dart', 'application/vnd.dart', '', ''),
('rdz', 'application/vnd.data-vision.rdz', '', ''),
('uvf', 'application/vnd.dece.data', '', ''),
('uvvf', 'application/vnd.dece.data', '', ''),
('uvd', 'application/vnd.dece.data', '', ''),
('uvvd', 'application/vnd.dece.data', '', ''),
('uvt', 'application/vnd.dece.ttml+xml', '', ''),
('uvvt', 'application/vnd.dece.ttml+xml', '', ''),
('uvx', 'application/vnd.dece.unspecified', '', ''),
('uvvx', 'application/vnd.dece.unspecified', '', ''),
('uvz', 'application/vnd.dece.zip', '', ''),
('uvvz', 'application/vnd.dece.zip', '', ''),
('fe_launch', 'application/vnd.denovo.fcselayout-link', '', ''),
('dna', 'application/vnd.dna', '', ''),
('mlp', 'application/vnd.dolby.mlp', '', ''),
('dpg', 'application/vnd.dpgraph', '', ''),
('dfac', 'application/vnd.dreamfactory', '', ''),
('kpxx', 'application/vnd.ds-keypoint', '', ''),
('ait', 'application/vnd.dvb.ait', 'mime-type-vector.png', ''),
('svc', 'application/vnd.dvb.service', '', '');
INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('geo', 'application/vnd.dynageo', '', ''),
('mag', 'application/vnd.ecowin.chart', '', ''),
('nml', 'application/vnd.enliven', '', ''),
('esf', 'application/vnd.epson.esf', '', ''),
('msf', 'application/vnd.epson.msf', '', ''),
('qam', 'application/vnd.epson.quickanime', '', ''),
('slt', 'application/vnd.epson.salt', '', ''),
('ssf', 'application/vnd.epson.ssf', '', ''),
('es3', 'application/vnd.eszigno3+xml', '', ''),
('et3', 'application/vnd.eszigno3+xml', '', ''),
('ez2', 'application/vnd.ezpix-album', '', ''),
('ez3', 'application/vnd.ezpix-package', '', ''),
('fdf', 'application/vnd.fdf', '', ''),
('mseed', 'application/vnd.fdsn.mseed', '', ''),
('seed', 'application/vnd.fdsn.seed', '', ''),
('dataless', 'application/vnd.fdsn.seed', '', ''),
('gph', 'application/vnd.flographit', '', ''),
('ftc', 'application/vnd.fluxtime.clip', '', ''),
('fm', 'application/vnd.framemaker', '', ''),
('frame', 'application/vnd.framemaker', '', ''),
('maker', 'application/vnd.framemaker', '', ''),
('book', 'application/vnd.framemaker', '', ''),
('fnc', 'application/vnd.frogans.fnc', '', ''),
('ltf', 'application/vnd.frogans.ltf', '', ''),
('fsc', 'application/vnd.fsc.weblaunch', '', ''),
('oas', 'application/vnd.fujitsu.oasys', '', ''),
('oa2', 'application/vnd.fujitsu.oasys2', '', ''),
('oa3', 'application/vnd.fujitsu.oasys3', '', ''),
('fg5', 'application/vnd.fujitsu.oasysgp', '', ''),
('bh2', 'application/vnd.fujitsu.oasysprs', '', ''),
('ddd', 'application/vnd.fujixerox.ddd', '', ''),
('xdw', 'application/vnd.fujixerox.docuworks', '', ''),
('xbd', 'application/vnd.fujixerox.docuworks.binder', '', ''),
('fzs', 'application/vnd.fuzzysheet', '', ''),
('txd', 'application/vnd.genomatix.tuxedo', '', ''),
('ggb', 'application/vnd.geogebra.file', '', ''),
('ggt', 'application/vnd.geogebra.tool', '', ''),
('gex', 'application/vnd.geometry-explorer', '', ''),
('gre', 'application/vnd.geometry-explorer', '', ''),
('gxt', 'application/vnd.geonext', '', ''),
('g2w', 'application/vnd.geoplan', '', ''),
('g3w', 'application/vnd.geospace', '', ''),
('gmx', 'application/vnd.gmx', '', ''),
('kml', 'application/vnd.google-earth.kml+xml', '', ''),
('kmz', 'application/vnd.google-earth.kmz', '', ''),
('gqf', 'application/vnd.grafeq', '', ''),
('gqs', 'application/vnd.grafeq', '', ''),
('gac', 'application/vnd.groove-account', '', ''),
('ghf', 'application/vnd.groove-help', '', ''),
('gim', 'application/vnd.groove-identity-message', '', ''),
('grv', 'application/vnd.groove-injector', '', ''),
('gtm', 'application/vnd.groove-tool-message', '', ''),
('tpl', 'application/vnd.groove-tool-template', '', 'file-code-o'),
('vcg', 'application/vnd.groove-vcard', '', ''),
('hal', 'application/vnd.hal+xml', '', ''),
('zmm', 'application/vnd.handheld-entertainment+xml', '', ''),
('hbci', 'application/vnd.hbci', '', ''),
('les', 'application/vnd.hhe.lesson-player', '', ''),
('hpgl', 'application/vnd.hp-hpgl', 'mime-type-vector.png', ''),
('hpid', 'application/vnd.hp-hpid', '', ''),
('hps', 'application/vnd.hp-hps', '', ''),
('jlt', 'application/vnd.hp-jlyt', '', ''),
('pcl', 'application/vnd.hp-pcl', '', ''),
('pclxl', 'application/vnd.hp-pclxl', '', ''),
('sfd-hdstx', 'application/vnd.hydrostatix.sof-data', '', ''),
('mpy', 'application/vnd.ibm.minipay', '', ''),
('afp', 'application/vnd.ibm.modcap', '', ''),
('listafp', 'application/vnd.ibm.modcap', '', ''),
('list3820', 'application/vnd.ibm.modcap', '', ''),
('irm', 'application/vnd.ibm.rights-management', '', ''),
('sc', 'application/vnd.ibm.secure-container', '', ''),
('icc', 'application/vnd.iccprofile', '', ''),
('icm', 'application/vnd.iccprofile', '', ''),
('igl', 'application/vnd.igloader', '', ''),
('ivp', 'application/vnd.immervision-ivp', '', ''),
('ivu', 'application/vnd.immervision-ivu', '', ''),
('igm', 'application/vnd.insors.igm', '', ''),
('xpw', 'application/vnd.intercon.formnet', '', ''),
('xpx', 'application/vnd.intercon.formnet', '', ''),
('i2g', 'application/vnd.intergeo', '', ''),
('qbo', 'application/vnd.intu.qbo', '', ''),
('qfx', 'application/vnd.intu.qfx', '', ''),
('rcprofile', 'application/vnd.ipunplugged.rcprofile', '', ''),
('irp', 'application/vnd.irepository.package+xml', '', ''),
('xpr', 'application/vnd.is-xpr', '', ''),
('fcs', 'application/vnd.isac.fcs', '', ''),
('jam', 'application/vnd.jam', '', ''),
('rms', 'application/vnd.jcp.javame.midlet-rms', '', ''),
('jisp', 'application/vnd.jisp', '', ''),
('joda', 'application/vnd.joost.joda-archive', '', ''),
('ktz', 'application/vnd.kahootz', '', ''),
('ktr', 'application/vnd.kahootz', '', ''),
('karbon', 'application/vnd.kde.karbon', '', ''),
('chrt', 'application/vnd.kde.kchart', '', ''),
('kfo', 'application/vnd.kde.kformula', '', ''),
('flw', 'application/vnd.kde.kivio', '', ''),
('kon', 'application/vnd.kde.kontour', '', ''),
('kpr', 'application/vnd.kde.kpresenter', '', ''),
('kpt', 'application/vnd.kde.kpresenter', '', ''),
('ksp', 'application/vnd.kde.kspread', '', ''),
('kwd', 'application/vnd.kde.kword', '', ''),
('kwt', 'application/vnd.kde.kword', '', ''),
('htke', 'application/vnd.kenameaapp', '', ''),
('kia', 'application/vnd.kidspiration', '', ''),
('kne', 'application/vnd.kinar', '', ''),
('knp', 'application/vnd.kinar', '', ''),
('skp', 'application/vnd.koan', '', ''),
('skd', 'application/vnd.koan', '', ''),
('skt', 'application/vnd.koan', '', ''),
('skm', 'application/vnd.koan', '', ''),
('sse', 'application/vnd.kodak-descriptor', '', ''),
('lasxml', 'application/vnd.las.las+xml', '', ''),
('lbd', 'application/vnd.llamagraphics.life-balance.desktop', '', ''),
('lbe', 'application/vnd.llamagraphics.life-balance.exchange+xml', '', ''),
('123', 'application/vnd.lotus-1-2-3', '', ''),
('apr', 'application/vnd.lotus-approach', '', ''),
('pre', 'application/vnd.lotus-freelance', '', ''),
('nsf', 'application/vnd.lotus-notes', '', ''),
('org', 'application/vnd.lotus-organizer', '', ''),
('scm', 'application/vnd.lotus-screencam', '', ''),
('lwp', 'application/vnd.lotus-wordpro', '', ''),
('portpkg', 'application/vnd.macports.portpkg', '', ''),
('mcd', 'application/vnd.mcd', '', ''),
('mc1', 'application/vnd.medcalcdata', '', ''),
('cdkey', 'application/vnd.mediastation.cdkey', '', ''),
('mwf', 'application/vnd.mfer', '', ''),
('mfm', 'application/vnd.mfmp', '', ''),
('flo', 'application/vnd.micrografx.flo', '', ''),
('igx', 'application/vnd.micrografx.igx', '', ''),
('mif', 'application/vnd.mif', '', ''),
('daf', 'application/vnd.mobius.daf', '', ''),
('dis', 'application/vnd.mobius.dis', '', ''),
('mbk', 'application/vnd.mobius.mbk', '', ''),
('mqy', 'application/vnd.mobius.mqy', '', ''),
('msl', 'application/vnd.mobius.msl', '', ''),
('plc', 'application/vnd.mobius.plc', '', ''),
('txf', 'application/vnd.mobius.txf', '', ''),
('mpn', 'application/vnd.mophun.application', '', ''),
('mpc', 'application/vnd.mophun.certificate', '', ''),
('xul', 'application/vnd.mozilla.xul+xml', '', ''),
('cil', 'application/vnd.ms-artgalry', 'mime-type-vector.png', ''),
('cab', 'application/vnd.ms-cab-compressed', 'mime-type-archive.png', 'file-archive-o'),
('xls', 'application/vnd.ms-excel', 'mime-type-spreadsheet.png', 'file-excel-o'),
('xlm', 'application/vnd.ms-excel', '', ''),
('xla', 'application/vnd.ms-excel', '', ''),
('xlc', 'application/vnd.ms-excel', '', ''),
('xlt', 'application/vnd.ms-excel', 'mime-type-spreadsheet.png', 'file-excel-o'),
('xlw', 'application/vnd.ms-excel', '', ''),
('xlam', 'application/vnd.ms-excel.addin.macroenabled.12', '', ''),
('xlsb', 'application/vnd.ms-excel.sheet.binary.macroenabled.12', '', ''),
('xlsm', 'application/vnd.ms-excel.sheet.macroenabled.12', '', ''),
('xltm', 'application/vnd.ms-excel.template.macroenabled.12', '', ''),
('eot', 'application/vnd.ms-fontobject', '', ''),
('chm', 'application/vnd.ms-htmlhelp', '', ''),
('ims', 'application/vnd.ms-ims', '', ''),
('lrm', 'application/vnd.ms-lrm', '', ''),
('thmx', 'application/vnd.ms-officetheme', '', ''),
('cat', 'application/vnd.ms-pki.seccat', '', ''),
('stl', 'application/vnd.ms-pki.stl', '', ''),
('ppt', 'application/vnd.ms-powerpoint', 'mime-type-presentation.png', 'file-powerpoint-o'),
('pps', 'application/vnd.ms-powerpoint', '', ''),
('pot', 'application/vnd.ms-powerpoint', '', ''),
('ppam', 'application/vnd.ms-powerpoint.addin.macroenabled.12', '', ''),
('pptm', 'application/vnd.ms-powerpoint.presentation.macroenabled.12', '', ''),
('sldm', 'application/vnd.ms-powerpoint.slide.macroenabled.12', '', ''),
('ppsm', 'application/vnd.ms-powerpoint.slideshow.macroenabled.12', '', ''),
('potm', 'application/vnd.ms-powerpoint.template.macroenabled.12', '', ''),
('mpp', 'application/vnd.ms-project', '', ''),
('mpt', 'application/vnd.ms-project', '', ''),
('docm', 'application/vnd.ms-word.document.macroenabled.12', '', ''),
('dotm', 'application/vnd.ms-word.template.macroenabled.12', '', ''),
('wps', 'application/vnd.ms-works', '', ''),
('wks', 'application/vnd.ms-works', '', ''),
('wcm', 'application/vnd.ms-works', '', ''),
('wdb', 'application/vnd.ms-works', '', ''),
('wpl', 'application/vnd.ms-wpl', '', ''),
('xps', 'application/vnd.ms-xpsdocument', '', ''),
('mseq', 'application/vnd.mseq', '', ''),
('mus', 'application/vnd.musician', '', ''),
('msty', 'application/vnd.muvee.style', '', ''),
('taglet', 'application/vnd.mynfc', '', ''),
('nlu', 'application/vnd.neurolanguage.nlu', '', ''),
('ntf', 'application/vnd.nitf', '', ''),
('nitf', 'application/vnd.nitf', '', ''),
('nnd', 'application/vnd.noblenet-directory', '', ''),
('nns', 'application/vnd.noblenet-sealer', '', ''),
('nnw', 'application/vnd.noblenet-web', '', ''),
('ngdat', 'application/vnd.nokia.n-gage.data', '', ''),
('n-gage', 'application/vnd.nokia.n-gage.symbian.install', '', ''),
('rpst', 'application/vnd.nokia.radio-preset', '', ''),
('rpss', 'application/vnd.nokia.radio-presets', '', ''),
('edm', 'application/vnd.novadigm.edm', '', ''),
('edx', 'application/vnd.novadigm.edx', '', ''),
('ext', 'application/vnd.novadigm.ext', '', ''),
('odc', 'application/vnd.oasis.opendocument.chart', '', ''),
('otc', 'application/vnd.oasis.opendocument.chart-template', '', ''),
('odb', 'application/vnd.oasis.opendocument.database', '', ''),
('odf', 'application/vnd.oasis.opendocument.formula', '', ''),
('odft', 'application/vnd.oasis.opendocument.formula-template', '', '');
INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('odg', 'application/vnd.oasis.opendocument.graphics', 'mime-type-vector.png', ''),
('otg', 'application/vnd.oasis.opendocument.graphics-template', '', ''),
('odi', 'application/vnd.oasis.opendocument.image', '', ''),
('oti', 'application/vnd.oasis.opendocument.image-template', '', ''),
('odp', 'application/vnd.oasis.opendocument.presentation', 'mime-type-presentation.png', 'file-powerpoint-o'),
('otp', 'application/vnd.oasis.opendocument.presentation-template', '', ''),
('ods', 'application/vnd.oasis.opendocument.spreadsheet', 'mime-type-spreadsheet.png', 'file-excel-o'),
('ots', 'application/vnd.oasis.opendocument.spreadsheet-template', 'mime-type-spreadsheet.png', 'file-excel-o'),
('odt', 'application/vnd.oasis.opendocument.text', 'mime-type-document.png', ''),
('odm', 'application/vnd.oasis.opendocument.text-master', '', ''),
('ott', 'application/vnd.oasis.opendocument.text-template', 'mime-type-document.png', ''),
('oth', 'application/vnd.oasis.opendocument.text-web', '', ''),
('xo', 'application/vnd.olpc-sugar', '', ''),
('dd2', 'application/vnd.oma.dd2+xml', '', ''),
('oxt', 'application/vnd.openofficeorg.extension', '', ''),
('pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'mime-type-presentation.png', 'file-powerpoint-o'),
('sldx', 'application/vnd.openxmlformats-officedocument.presentationml.slide', '', ''),
('ppsx', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow', '', ''),
('potx', 'application/vnd.openxmlformats-officedocument.presentationml.template', '', ''),
('xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'mime-type-spreadsheet.png', 'file-excel-o'),
('xltx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.template', '', ''),
('docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'mime-type-document.png', 'file-word-o'),
('dotx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', '', ''),
('mgp', 'application/vnd.osgeo.mapguide.package', '', ''),
('dp', 'application/vnd.osgi.dp', '', ''),
('esa', 'application/vnd.osgi.subsystem', '', ''),
('pdb', 'application/vnd.palm', 'mime-type-document.png', ''),
('pqa', 'application/vnd.palm', '', ''),
('oprc', 'application/vnd.palm', '', ''),
('paw', 'application/vnd.pawaafile', '', ''),
('str', 'application/vnd.pg.format', '', ''),
('ei6', 'application/vnd.pg.osasli', '', ''),
('efif', 'application/vnd.picsel', '', ''),
('wg', 'application/vnd.pmi.widget', '', ''),
('plf', 'application/vnd.pocketlearn', '', ''),
('pbd', 'application/vnd.powerbuilder6', '', ''),
('box', 'application/vnd.previewsystems.box', '', ''),
('mgz', 'application/vnd.proteus.magazine', '', ''),
('qps', 'application/vnd.publishare-delta-tree', '', ''),
('ptid', 'application/vnd.pvi.ptid1', '', ''),
('qxd', 'application/vnd.quark.quarkxpress', '', ''),
('qxt', 'application/vnd.quark.quarkxpress', '', ''),
('qwd', 'application/vnd.quark.quarkxpress', '', ''),
('qwt', 'application/vnd.quark.quarkxpress', '', ''),
('qxl', 'application/vnd.quark.quarkxpress', '', ''),
('qxb', 'application/vnd.quark.quarkxpress', '', ''),
('bed', 'application/vnd.realvnc.bed', '', ''),
('mxl', 'application/vnd.recordare.musicxml', '', ''),
('musicxml', 'application/vnd.recordare.musicxml+xml', '', ''),
('cryptonote', 'application/vnd.rig.cryptonote', '', ''),
('cod', 'application/vnd.rim.cod', '', ''),
('rm', 'application/vnd.rn-realmedia', '', ''),
('rmvb', 'application/vnd.rn-realmedia-vbr', '', ''),
('link66', 'application/vnd.route66.link66+xml', '', ''),
('st', 'application/vnd.sailingtracker.track', '', ''),
('see', 'application/vnd.seemail', '', ''),
('sema', 'application/vnd.sema', '', ''),
('semd', 'application/vnd.semd', '', ''),
('semf', 'application/vnd.semf', '', ''),
('ifm', 'application/vnd.shana.informed.formdata', '', ''),
('itp', 'application/vnd.shana.informed.formtemplate', '', ''),
('iif', 'application/vnd.shana.informed.interchange', '', ''),
('ipk', 'application/vnd.shana.informed.package', '', ''),
('twd', 'application/vnd.simtech-mindmapper', '', ''),
('twds', 'application/vnd.simtech-mindmapper', '', ''),
('mmf', 'application/vnd.smaf', '', ''),
('teacher', 'application/vnd.smart.teacher', '', ''),
('sdkm', 'application/vnd.solent.sdkm+xml', '', ''),
('sdkd', 'application/vnd.solent.sdkm+xml', '', ''),
('dxp', 'application/vnd.spotfire.dxp', '', ''),
('sfs', 'application/vnd.spotfire.sfs', '', ''),
('sdc', 'application/vnd.stardivision.calc', 'mime-type-spreadsheet.png', 'file-excel-o'),
('sda', 'application/vnd.stardivision.draw', '', ''),
('sdd', 'application/vnd.stardivision.impress', 'mime-type-presentation.png', 'file-powerpoint-o'),
('smf', 'application/vnd.stardivision.math', '', ''),
('sdw', 'application/vnd.stardivision.writer', 'mime-type-document.png', ''),
('vor', 'application/vnd.stardivision.writer', '', ''),
('sgl', 'application/vnd.stardivision.writer-global', '', ''),
('smzip', 'application/vnd.stepmania.package', '', ''),
('sm', 'application/vnd.stepmania.stepchart', '', ''),
('sxc', 'application/vnd.sun.xml.calc', 'mime-type-spreadsheet.png', 'file-excel-o'),
('stc', 'application/vnd.sun.xml.calc.template', 'mime-type-spreadsheet.png', 'file-excel-o'),
('sxd', 'application/vnd.sun.xml.draw', 'mime-type-vector.png', ''),
('std', 'application/vnd.sun.xml.draw.template', '', ''),
('sxi', 'application/vnd.sun.xml.impress', 'mime-type-presentation.png', 'file-powerpoint-o'),
('sti', 'application/vnd.sun.xml.impress.template', 'mime-type-presentation.png', 'file-powerpoint-o'),
('sxm', 'application/vnd.sun.xml.math', '', ''),
('sxw', 'application/vnd.sun.xml.writer', 'mime-type-document.png', ''),
('sxg', 'application/vnd.sun.xml.writer.global', '', ''),
('stw', 'application/vnd.sun.xml.writer.template', 'mime-type-document.png', ''),
('sus', 'application/vnd.sus-calendar', '', ''),
('susp', 'application/vnd.sus-calendar', '', ''),
('svd', 'application/vnd.svd', '', ''),
('sis', 'application/vnd.symbian.install', '', ''),
('sisx', 'application/vnd.symbian.install', '', ''),
('xsm', 'application/vnd.syncml+xml', '', ''),
('bdm', 'application/vnd.syncml.dm+wbxml', '', ''),
('xdm', 'application/vnd.syncml.dm+xml', '', ''),
('tao', 'application/vnd.tao.intent-module-archive', '', ''),
('pcap', 'application/vnd.tcpdump.pcap', '', ''),
('cap', 'application/vnd.tcpdump.pcap', '', ''),
('dmp', 'application/vnd.tcpdump.pcap', '', ''),
('tmo', 'application/vnd.tmobile-livetv', '', ''),
('tpt', 'application/vnd.trid.tpt', '', ''),
('mxs', 'application/vnd.triscape.mxs', '', ''),
('tra', 'application/vnd.trueapp', '', ''),
('ufd', 'application/vnd.ufdl', '', ''),
('ufdl', 'application/vnd.ufdl', '', ''),
('utz', 'application/vnd.uiq.theme', '', ''),
('umj', 'application/vnd.umajin', '', ''),
('unityweb', 'application/vnd.unity', '', ''),
('uoml', 'application/vnd.uoml+xml', '', ''),
('vcx', 'application/vnd.vcx', '', ''),
('vsd', 'application/vnd.visio', '', ''),
('vst', 'application/vnd.visio', '', ''),
('vss', 'application/vnd.visio', 'mime-type-vector.png', ''),
('vsw', 'application/vnd.visio', '', ''),
('vis', 'application/vnd.visionary', '', ''),
('vsf', 'application/vnd.vsf', '', ''),
('wbxml', 'application/vnd.wap.wbxml', '', ''),
('wmlc', 'application/vnd.wap.wmlc', '', ''),
('wmlsc', 'application/vnd.wap.wmlscriptc', '', ''),
('wtb', 'application/vnd.webturbo', '', ''),
('nbp', 'application/vnd.wolfram.player', '', ''),
('wpd', 'application/vnd.wordperfect', '', ''),
('wqd', 'application/vnd.wqd', '', ''),
('stf', 'application/vnd.wt.stf', '', ''),
('xar', 'application/vnd.xara', '', ''),
('xfdl', 'application/vnd.xfdl', '', ''),
('hvd', 'application/vnd.yamaha.hv-dic', '', ''),
('hvs', 'application/vnd.yamaha.hv-script', '', ''),
('hvp', 'application/vnd.yamaha.hv-voice', '', ''),
('osf', 'application/vnd.yamaha.openscoreformat', '', ''),
('osfpvg', 'application/vnd.yamaha.openscoreformat.osfpvg+xml', '', ''),
('saf', 'application/vnd.yamaha.smaf-audio', '', ''),
('spf', 'application/vnd.yamaha.smaf-phrase', '', ''),
('cmp', 'application/vnd.yellowriver-custom-menu', '', ''),
('zir', 'application/vnd.zul', '', ''),
('zirz', 'application/vnd.zul', '', ''),
('zaz', 'application/vnd.zzazz.deck+xml', '', ''),
('vxml', 'application/voicexml+xml', '', ''),
('wgt', 'application/widget', '', ''),
('hlp', 'application/winhlp', '', ''),
('wsdl', 'application/wsdl+xml', '', ''),
('wspolicy', 'application/wspolicy+xml', '', ''),
('7z', 'application/x-7z-compressed', 'mime-type-archive.png', 'file-archive-o'),
('abw', 'application/x-abiword', '', ''),
('ace', 'application/x-ace-compressed', 'mime-type-archive.png', 'file-archive-o'),
('dmg', 'application/x-apple-diskimage', '', ''),
('aab', 'application/x-authorware-bin', '', ''),
('x32', 'application/x-authorware-bin', '', ''),
('u32', 'application/x-authorware-bin', '', ''),
('vox', 'application/x-authorware-bin', '', ''),
('aam', 'application/x-authorware-map', '', ''),
('aas', 'application/x-authorware-seg', '', ''),
('bcpio', 'application/x-bcpio', '', ''),
('torrent', 'application/x-bittorrent', '', ''),
('blb', 'application/x-blorb', '', ''),
('blorb', 'application/x-blorb', '', ''),
('bz', 'application/x-bzip', '', ''),
('bz2', 'application/x-bzip2', 'mime-type-archive.png', 'file-archive-o'),
('boz', 'application/x-bzip2', '', ''),
('cbr', 'application/x-cbr', '', ''),
('cba', 'application/x-cbr', '', ''),
('cbt', 'application/x-cbr', '', ''),
('cbz', 'application/x-cbr', '', ''),
('cb7', 'application/x-cbr', '', ''),
('vcd', 'application/x-cdlink', '', ''),
('cfs', 'application/x-cfs-compressed', '', ''),
('chat', 'application/x-chat', '', ''),
('pgn', 'application/x-chess-pgn', '', ''),
('nsc', 'application/x-conference', '', ''),
('cpio', 'application/x-cpio', '', ''),
('csh', 'application/x-csh', '', 'file-code-o'),
('deb', 'application/x-debian-package', '', ''),
('udeb', 'application/x-debian-package', '', ''),
('dgc', 'application/x-dgc-compressed', '', ''),
('dir', 'application/x-director', '', ''),
('dcr', 'application/x-director', '', ''),
('dxr', 'application/x-director', '', ''),
('cst', 'application/x-director', '', ''),
('cct', 'application/x-director', '', ''),
('cxt', 'application/x-director', '', '');
INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('w3d', 'application/x-director', '', ''),
('fgd', 'application/x-director', '', ''),
('swa', 'application/x-director', '', ''),
('wad', 'application/x-doom', '', ''),
('ncx', 'application/x-dtbncx+xml', '', ''),
('dtb', 'application/x-dtbook+xml', '', ''),
('res', 'application/x-dtbresource+xml', '', ''),
('dvi', 'application/x-dvi', '', ''),
('evy', 'application/x-envoy', '', ''),
('eva', 'application/x-eva', '', ''),
('bdf', 'application/x-font-bdf', '', ''),
('gsf', 'application/x-font-ghostscript', '', ''),
('psf', 'application/x-font-linux-psf', '', ''),
('otf', 'application/x-font-otf', '', ''),
('pcf', 'application/x-font-pcf', '', ''),
('snf', 'application/x-font-snf', '', ''),
('ttf', 'application/x-font-ttf', '', ''),
('ttc', 'application/x-font-ttf', '', ''),
('pfa', 'application/x-font-type1', '', ''),
('pfb', 'application/x-font-type1', '', ''),
('pfm', 'application/x-font-type1', '', ''),
('afm', 'application/x-font-type1', '', ''),
('woff', 'application/x-font-woff', '', ''),
('arc', 'application/x-freearc', '', ''),
('spl', 'application/x-futuresplash', '', ''),
('gca', 'application/x-gca-compressed', '', ''),
('ulx', 'application/x-glulx', '', ''),
('gnumeric', 'application/x-gnumeric', '', ''),
('gramps', 'application/x-gramps-xml', '', ''),
('gtar', 'application/x-gtar', '', ''),
('hdf', 'application/x-hdf', '', ''),
('install', 'application/x-install-instructions', '', ''),
('iso', 'application/x-iso9660-image', '', ''),
('jnlp', 'application/x-java-jnlp-file', '', ''),
('latex', 'application/x-latex', '', ''),
('lzh', 'application/x-lzh-compressed', '', ''),
('lha', 'application/x-lzh-compressed', '', ''),
('mie', 'application/x-mie', '', ''),
('prc', 'application/x-mobipocket-ebook', '', ''),
('mobi', 'application/x-mobipocket-ebook', '', ''),
('application', 'application/x-ms-application', '', ''),
('lnk', 'application/x-ms-shortcut', '', ''),
('wmd', 'application/x-ms-wmd', '', ''),
('wmz', 'application/x-ms-wmz', '', ''),
('xbap', 'application/x-ms-xbap', '', ''),
('mdb', 'application/x-msaccess', '', ''),
('obd', 'application/x-msbinder', '', ''),
('crd', 'application/x-mscardfile', '', ''),
('clp', 'application/x-msclip', '', ''),
('exe', 'application/x-msdownload', '', ''),
('dll', 'application/x-msdownload', '', ''),
('com', 'application/x-msdownload', '', ''),
('bat', 'application/x-msdownload', '', 'file-code-o'),
('msi', 'application/x-msdownload', '', ''),
('mvb', 'application/x-msmediaview', '', ''),
('m13', 'application/x-msmediaview', '', ''),
('m14', 'application/x-msmediaview', '', ''),
('wmf', 'application/x-msmetafile', '', ''),
('emf', 'application/x-msmetafile', '', ''),
('emz', 'application/x-msmetafile', '', ''),
('mny', 'application/x-msmoney', '', ''),
('pub', 'application/x-mspublisher', '', ''),
('scd', 'application/x-msschedule', '', ''),
('trm', 'application/x-msterminal', '', ''),
('wri', 'application/x-mswrite', '', ''),
('nc', 'application/x-netcdf', '', ''),
('cdf', 'application/x-netcdf', '', ''),
('nzb', 'application/x-nzb', '', ''),
('p12', 'application/x-pkcs12', '', ''),
('pfx', 'application/x-pkcs12', '', ''),
('p7b', 'application/x-pkcs7-certificates', '', ''),
('spc', 'application/x-pkcs7-certificates', '', ''),
('p7r', 'application/x-pkcs7-certreqresp', '', ''),
('rar', 'application/x-rar-compressed', 'mime-type-archive.png', 'file-archive-o'),
('ris', 'application/x-research-info-systems', '', ''),
('sh', 'application/x-sh', '', 'file-code-o'),
('shar', 'application/x-shar', '', ''),
('swf', 'application/x-shockwave-flash', '', ''),
('xap', 'application/x-silverlight-app', '', ''),
('sql', 'text/x-sql', '', 'file-code-o'),
('sit', 'application/x-stuffit', '', ''),
('sitx', 'application/x-stuffitx', '', ''),
('srt', 'application/x-subrip', '', ''),
('sv4cpio', 'application/x-sv4cpio', '', ''),
('sv4crc', 'application/x-sv4crc', '', ''),
('t3', 'application/x-t3vm-image', '', ''),
('gam', 'application/x-tads', '', ''),
('tar', 'application/x-tar', 'mime-type-archive.png', 'file-archive-o'),
('tcl', 'application/x-tcl', '', ''),
('tex', 'application/x-tex', '', ''),
('tfm', 'application/x-tex-tfm', '', ''),
('texinfo', 'application/x-texinfo', '', ''),
('texi', 'application/x-texinfo', '', ''),
('obj', 'application/x-tgif', '', ''),
('ustar', 'application/x-ustar', '', ''),
('src', 'application/x-wais-source', '', 'file-code-o'),
('der', 'application/x-x509-ca-cert', '', ''),
('crt', 'application/x-x509-ca-cert', '', ''),
('fig', 'application/x-xfig', '', ''),
('xlf', 'application/x-xliff+xml', '', ''),
('xpi', 'application/x-xpinstall', '', ''),
('xz', 'application/x-xz', '', ''),
('z1', 'application/x-zmachine', '', ''),
('z2', 'application/x-zmachine', '', ''),
('z3', 'application/x-zmachine', '', ''),
('z4', 'application/x-zmachine', '', ''),
('z5', 'application/x-zmachine', '', ''),
('z6', 'application/x-zmachine', '', ''),
('z7', 'application/x-zmachine', '', ''),
('z8', 'application/x-zmachine', '', ''),
('xaml', 'application/xaml+xml', '', ''),
('xdf', 'application/xcap-diff+xml', '', ''),
('xenc', 'application/xenc+xml', '', ''),
('xhtml', 'application/xhtml+xml', '', 'file-code-o'),
('xht', 'application/xhtml+xml', '', ''),
('xml', 'application/xml', '', 'file-code-o'),
('xsl', 'application/xml', '', 'file-code-o'),
('dtd', 'application/xml-dtd', '', 'file-code-o'),
('xop', 'application/xop+xml', '', ''),
('xpl', 'application/xproc+xml', '', ''),
('xslt', 'application/xslt+xml', '', 'file-code-o'),
('xspf', 'application/xspf+xml', '', ''),
('mxml', 'application/xv+xml', '', ''),
('xhvml', 'application/xv+xml', '', ''),
('xvml', 'application/xv+xml', '', ''),
('xvm', 'application/xv+xml', '', ''),
('yang', 'application/yang', '', ''),
('yin', 'application/yin+xml', '', ''),
('zip', 'application/zip', 'mime-type-archive.png', 'file-archive-o'),
('adp', 'audio/adpcm', 'mime-type-audio.png', 'file-audio-o'),
('au', 'audio/basic', 'mime-type-audio.png', 'file-audio-o'),
('snd', 'audio/basic', 'mime-type-audio.png', 'file-audio-o'),
('mid', 'audio/midi', 'mime-type-audio.png', 'file-audio-o'),
('midi', 'audio/midi', 'mime-type-audio.png', 'file-audio-o'),
('kar', 'audio/midi', 'mime-type-audio.png', 'file-audio-o'),
('rmi', 'audio/midi', 'mime-type-audio.png', 'file-audio-o'),
('m4a', 'audio/mp4', 'mime-type-audio.png', 'file-audio-o'),
('mp4a', 'audio/mp4', 'mime-type-audio.png', 'file-audio-o'),
('m4p', 'audio/mp4a-latm', 'mime-type-audio.png', 'file-audio-o'),
('mpga', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('mp2', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('mp2a', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('mp3', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('m2a', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('m3a', 'audio/mpeg', 'mime-type-audio.png', 'file-audio-o'),
('oga', 'audio/ogg', 'mime-type-audio.png', 'file-audio-o'),
('ogg', 'audio/ogg', 'mime-type-audio.png', 'file-audio-o'),
('spx', 'audio/ogg', 'mime-type-audio.png', 'file-audio-o'),
('s3m', 'audio/s3m', 'mime-type-audio.png', 'file-audio-o'),
('sil', 'audio/silk', 'mime-type-audio.png', 'file-audio-o'),
('uva', 'audio/vnd.dece.audio', 'mime-type-audio.png', 'file-audio-o'),
('uvva', 'audio/vnd.dece.audio', 'mime-type-audio.png', 'file-audio-o'),
('eol', 'audio/vnd.digital-winds', 'mime-type-audio.png', 'file-audio-o'),
('dra', 'audio/vnd.dra', 'mime-type-audio.png', 'file-audio-o'),
('dts', 'audio/vnd.dts', 'mime-type-audio.png', 'file-audio-o'),
('dtshd', 'audio/vnd.dts.hd', 'mime-type-audio.png', 'file-audio-o'),
('lvp', 'audio/vnd.lucent.voice', 'mime-type-audio.png', 'file-audio-o'),
('pya', 'audio/vnd.ms-playready.media.pya', 'mime-type-audio.png', 'file-audio-o'),
('ecelp4800', 'audio/vnd.nuera.ecelp4800', 'mime-type-audio.png', 'file-audio-o'),
('ecelp7470', 'audio/vnd.nuera.ecelp7470', 'mime-type-audio.png', 'file-audio-o'),
('ecelp9600', 'audio/vnd.nuera.ecelp9600', 'mime-type-audio.png', 'file-audio-o'),
('rip', 'audio/vnd.rip', 'mime-type-audio.png', 'file-audio-o'),
('weba', 'audio/webm', 'mime-type-audio.png', 'file-audio-o'),
('aac', 'audio/x-aac', 'mime-type-audio.png', 'file-audio-o'),
('aif', 'audio/x-aiff', 'mime-type-audio.png', 'file-audio-o'),
('aiff', 'audio/x-aiff', 'mime-type-audio.png', 'file-audio-o'),
('aifc', 'audio/x-aiff', 'mime-type-audio.png', 'file-audio-o'),
('caf', 'audio/x-caf', 'mime-type-audio.png', 'file-audio-o'),
('flac', 'audio/x-flac', 'mime-type-audio.png', 'file-audio-o'),
('mka', 'audio/x-matroska', 'mime-type-audio.png', 'file-audio-o'),
('m3u', 'audio/x-mpegurl', 'mime-type-audio.png', 'file-audio-o'),
('wax', 'audio/x-ms-wax', 'mime-type-audio.png', 'file-audio-o'),
('wma', 'audio/x-ms-wma', 'mime-type-audio.png', 'file-audio-o'),
('ram', 'audio/x-pn-realaudio', 'mime-type-audio.png', 'file-audio-o'),
('ra', 'audio/x-pn-realaudio', 'mime-type-audio.png', 'file-audio-o'),
('rmp', 'audio/x-pn-realaudio-plugin', 'mime-type-audio.png', 'file-audio-o'),
('wav', 'audio/x-wav', 'mime-type-audio.png', 'file-audio-o'),
('xm', 'audio/xm', 'mime-type-audio.png', 'file-audio-o'),
('cdx', 'chemical/x-cdx', 'mime-type-vector.png', ''),
('cif', 'chemical/x-cif', '', ''),
('cmdf', 'chemical/x-cmdf', '', ''),
('cml', 'chemical/x-cml', '', ''),
('csml', 'chemical/x-csml', '', ''),
('xyz', 'chemical/x-xyz', '', ''),
('bmp', 'image/bmp', 'mime-type-image.png', 'file-image-o'),
('cgm', 'image/cgm', 'mime-type-image.png', 'file-image-o'),
('g3', 'image/g3fax', 'mime-type-image.png', 'file-image-o'),
('gif', 'image/gif', 'mime-type-image.png', 'file-image-o'),
('ief', 'image/ief', 'mime-type-image.png', 'file-image-o'),
('jp2', 'image/jp2', 'mime-type-image.png', 'file-image-o'),
('jpeg', 'image/jpeg', 'mime-type-image.png', 'file-image-o'),
('jpg', 'image/jpeg', 'mime-type-image.png', 'file-image-o'),
('jpe', 'image/jpeg', 'mime-type-image.png', 'file-image-o'),
('ktx', 'image/ktx', 'mime-type-image.png', 'file-image-o'),
('pict', 'image/pict', 'mime-type-image.png', 'file-image-o'),
('pic', 'image/pict', 'mime-type-image.png', 'file-image-o'),
('pct', 'image/pict', 'mime-type-image.png', 'file-image-o'),
('png', 'image/png', 'mime-type-png.png', 'file-image-o'),
('btif', 'image/prs.btif', 'mime-type-image.png', 'file-image-o'),
('sgi', 'image/sgi', 'mime-type-image.png', 'file-image-o');
INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('svg', 'image/svg+xml', 'mime-type-image.png', 'file-image-o'),
('svgz', 'image/svg+xml', 'mime-type-image.png', 'file-image-o'),
('tiff', 'image/tiff', 'mime-type-image.png', 'file-image-o'),
('tif', 'image/tiff', 'mime-type-image.png', 'file-image-o'),
('psd', 'image/vnd.adobe.photoshop', 'mime-type-psd.png', 'file-image-o'),
('uvi', 'image/vnd.dece.graphic', 'mime-type-image.png', 'file-image-o'),
('uvvi', 'image/vnd.dece.graphic', 'mime-type-image.png', 'file-image-o'),
('uvg', 'image/vnd.dece.graphic', 'mime-type-image.png', 'file-image-o'),
('uvvg', 'image/vnd.dece.graphic', 'mime-type-image.png', 'file-image-o'),
('sub', 'image/vnd.dvb.subtitle', 'mime-type-image.png', 'file-image-o'),
('djvu', 'image/vnd.djvu', 'mime-type-image.png', 'file-image-o'),
('djv', 'image/vnd.djvu', 'mime-type-image.png', 'file-image-o'),
('dwg', 'image/vnd.dwg', 'mime-type-image.png', 'file-image-o'),
('dxf', 'image/vnd.dxf', 'mime-type-image.png', 'file-image-o'),
('fbs', 'image/vnd.fastbidsheet', 'mime-type-image.png', 'file-image-o'),
('fpx', 'image/vnd.fpx', 'mime-type-image.png', 'file-image-o'),
('fst', 'image/vnd.fst', 'mime-type-image.png', 'file-image-o'),
('mmr', 'image/vnd.fujixerox.edmics-mmr', 'mime-type-image.png', 'file-image-o'),
('rlc', 'image/vnd.fujixerox.edmics-rlc', 'mime-type-image.png', 'file-image-o'),
('mdi', 'image/vnd.ms-modi', 'mime-type-image.png', 'file-image-o'),
('wdp', 'image/vnd.ms-photo', 'mime-type-image.png', 'file-image-o'),
('npx', 'image/vnd.net-fpx', 'mime-type-image.png', 'file-image-o'),
('wbmp', 'image/vnd.wap.wbmp', 'mime-type-image.png', 'file-image-o'),
('xif', 'image/vnd.xiff', 'mime-type-image.png', 'file-image-o'),
('webp', 'image/webp', 'mime-type-image.png', 'file-image-o'),
('3ds', 'image/x-3ds', 'mime-type-image.png', 'file-image-o'),
('ras', 'image/x-cmu-raster', 'mime-type-image.png', 'file-image-o'),
('cmx', 'image/x-cmx', 'mime-type-image.png', 'file-image-o'),
('fh', 'image/x-freehand', 'mime-type-image.png', 'file-image-o'),
('fhc', 'image/x-freehand', 'mime-type-image.png', 'file-image-o'),
('fh4', 'image/x-freehand', 'mime-type-image.png', 'file-image-o'),
('fh5', 'image/x-freehand', 'mime-type-image.png', 'file-image-o'),
('fh7', 'image/x-freehand', 'mime-type-image.png', 'file-image-o'),
('ico', 'image/x-icon', 'mime-type-image.png', 'file-image-o'),
('pntg', 'image/x-macpaint', 'mime-type-image.png', 'file-image-o'),
('pnt', 'image/x-macpaint', 'mime-type-image.png', 'file-image-o'),
('mac', 'image/x-macpaint', 'mime-type-image.png', 'file-image-o'),
('sid', 'image/x-mrsid-image', 'mime-type-image.png', 'file-image-o'),
('pcx', 'image/x-pcx', 'mime-type-image.png', 'file-image-o'),
('pnm', 'image/x-portable-anymap', 'mime-type-image.png', 'file-image-o'),
('pbm', 'image/x-portable-bitmap', 'mime-type-image.png', 'file-image-o'),
('pgm', 'image/x-portable-graymap', 'mime-type-image.png', 'file-image-o'),
('ppm', 'image/x-portable-pixmap', 'mime-type-image.png', 'file-image-o'),
('qtif', 'image/x-quicktime', 'mime-type-image.png', 'file-image-o'),
('qti', 'image/x-quicktime', 'mime-type-image.png', 'file-image-o'),
('rgb', 'image/x-rgb', 'mime-type-image.png', 'file-image-o'),
('tga', 'image/x-tga', 'mime-type-image.png', 'file-image-o'),
('xbm', 'image/x-xbitmap', 'mime-type-image.png', 'file-image-o'),
('xpm', 'image/x-xpixmap', 'mime-type-image.png', 'file-image-o'),
('xwd', 'image/x-xwindowdump', 'mime-type-image.png', 'file-image-o'),
('eml', 'message/rfc822', '', ''),
('mime', 'message/rfc822', '', ''),
('igs', 'model/iges', '', ''),
('iges', 'model/iges', '', ''),
('msh', 'model/mesh', '', ''),
('mesh', 'model/mesh', '', ''),
('silo', 'model/mesh', '', ''),
('dae', 'model/vnd.collada+xml', '', ''),
('dwf', 'model/vnd.dwf', '', ''),
('gdl', 'model/vnd.gdl', '', ''),
('gtw', 'model/vnd.gtw', '', ''),
('mts', 'model/vnd.mts', '', ''),
('vtu', 'model/vnd.vtu', '', ''),
('wrl', 'model/vrml', '', ''),
('vrml', 'model/vrml', '', ''),
('x3db', 'model/x3d+binary', '', ''),
('x3dbz', 'model/x3d+binary', '', ''),
('x3dv', 'model/x3d+vrml', '', ''),
('x3dvz', 'model/x3d+vrml', '', ''),
('x3d', 'model/x3d+xml', '', ''),
('x3dz', 'model/x3d+xml', '', ''),
('manifest', 'text/cache-manifest', '', 'file-text-o'),
('appcache', 'text/cache-manifest', '', 'file-text-o'),
('ics', 'text/calendar', '', 'file-text-o'),
('ifb', 'text/calendar', '', 'file-text-o'),
('css', 'text/css', '', 'file-code-o'),
('csv', 'text/csv', 'mime-type-spreadsheet.png', 'file-excel-o'),
('html', 'text/html', '', 'file-code-o'),
('htm', 'text/html', '', 'file-code-o'),
('n3', 'text/n3', '', 'file-text-o'),
('txt', 'text/plain', 'mime-type-document.png', 'file-text-o'),
('text', 'text/plain', '', 'file-text-o'),
('conf', 'text/plain', '', 'file-text-o'),
('def', 'text/plain', '', 'file-text-o'),
('list', 'text/plain', '', 'file-text-o'),
('log', 'text/plain', '', 'file-text-o'),
('in', 'text/plain', '', 'file-text-o'),
('dsc', 'text/prs.lines.tag', '', 'file-text-o'),
('rtx', 'text/richtext', '', 'file-text-o'),
('sgml', 'text/sgml', '', 'file-text-o'),
('sgm', 'text/sgml', '', 'file-text-o'),
('tsv', 'text/tab-separated-values', '', 'file-text-o'),
('t', 'text/troff', '', 'file-text-o'),
('tr', 'text/troff', '', 'file-text-o'),
('roff', 'text/troff', '', 'file-text-o'),
('man', 'text/troff', '', 'file-text-o'),
('me', 'text/troff', '', 'file-text-o'),
('ms', 'text/troff', '', 'file-text-o'),
('ttl', 'text/turtle', '', 'file-code-o'),
('uri', 'text/uri-list', '', 'file-text-o'),
('uris', 'text/uri-list', '', 'file-text-o'),
('urls', 'text/uri-list', '', 'file-text-o'),
('vcard', 'text/vcard', '', 'file-text-o'),
('curl', 'text/vnd.curl', '', 'file-text-o'),
('dcurl', 'text/vnd.curl.dcurl', '', 'file-text-o'),
('scurl', 'text/vnd.curl.scurl', '', 'file-text-o'),
('mcurl', 'text/vnd.curl.mcurl', '', 'file-text-o'),
('fly', 'text/vnd.fly', '', 'file-text-o'),
('flx', 'text/vnd.fmi.flexstor', '', 'file-text-o'),
('gv', 'text/vnd.graphviz', '', 'file-text-o'),
('3dml', 'text/vnd.in3d.3dml', '', 'file-text-o'),
('spot', 'text/vnd.in3d.spot', '', 'file-text-o'),
('jad', 'text/vnd.sun.j2me.app-descriptor', '', 'file-text-o'),
('wml', 'text/vnd.wap.wml', '', 'file-text-o'),
('wmls', 'text/vnd.wap.wmlscript', '', 'file-text-o'),
('s', 'text/x-asm', '', 'file-text-o'),
('asm', 'text/x-asm', '', 'file-code-o'),
('c', 'text/x-c', '', 'file-code-o'),
('cc', 'text/x-c', '', 'file-code-o'),
('cxx', 'text/x-c', '', 'file-code-o'),
('cpp', 'text/x-c', '', 'file-code-o'),
('h', 'text/x-c', '', 'file-code-o'),
('hh', 'text/x-c', '', 'file-code-o'),
('dic', 'text/x-c', '', 'file-text-o'),
('f', 'text/x-fortran', '', 'file-code-o'),
('for', 'text/x-fortran', '', 'file-code-o'),
('f77', 'text/x-fortran', '', 'file-text-o'),
('f90', 'text/x-fortran', '', 'file-text-o'),
('java', 'text/x-java-source', '', 'file-code-o'),
('opml', 'text/x-opml', '', 'file-text-o'),
('p', 'text/x-pascal', '', 'file-text-o'),
('pas', 'text/x-pascal', '', 'file-code-o'),
('php', 'application/x-httpd-php', '', 'file-code-o'),
('coffee', 'text/x-coffeescript', '', 'file-code-o'),
('lsp', 'text/x-common-lisp', '', 'file-code-o'),
('lisp', 'text/x-common-lisp', '', 'file-code-o'),
('diff', 'text/x-diff', '', 'file-code-o'),
('go', 'text/x-go', '', 'file-code-o'),
('lua', 'text/x-lua', '', 'file-code-o'),
('pl', 'text/x-perl', '', 'file-code-o'),
('prl', 'text/x-perl', '', 'file-code-o'),
('perl', 'text/x-perl', '', 'file-code-o'),
('py', 'text/x-python', '', 'file-code-o'),
('nginx', 'text/nginx', '', 'file-code-o'),
('ini', 'text/x-ini', '', 'file-text-o'),
('rb', 'text/x-ruby', '', 'file-code-o'),
('sass', 'text/x-sass', '', 'file-code-o'),
('bash', 'text/x-sh', '', 'file-code-o'),
('swift', 'text/x-swift', '', 'file-code-o'),
('vb', 'text/x-vb', '', 'file-code-o'),
('vbs', 'text/vbscript', '', 'file-code-o'),
('vue', 'text/x-vue', '', 'file-code-o'),
('yaml', 'text/x-yaml', '', 'file-text-o'),
('md', 'text/x-markdown', '', 'file-text-o'),
('xq', 'application/xquery', '', 'file-code-o'),
('xquery', 'application/xquery', '', 'file-code-o'),
('ps1', 'application/x-powershell', '', 'file-code-o'),
('aps', 'application/x-aspx', '', ''),
('jsp', 'application/x-jsp', '', 'file-code-o'),
('nfo', 'text/x-nfo', '', 'file-text-o'),
('etx', 'text/x-setext', '', 'file-text-o'),
('sfv', 'text/x-sfv', '', 'file-text-o'),
('uu', 'text/x-uuencode', '', 'file-text-o'),
('vcs', 'text/x-vcalendar', '', 'file-text-o'),
('vcf', 'text/x-vcard', '', 'file-text-o'),
('3gp', 'video/3gpp', 'mime-type-video.png', 'file-video-o'),
('3g2', 'video/3gpp2', 'mime-type-video.png', 'file-video-o'),
('h261', 'video/h261', 'mime-type-video.png', 'file-video-o'),
('h263', 'video/h263', 'mime-type-video.png', 'file-video-o'),
('h264', 'video/h264', 'mime-type-video.png', 'file-video-o'),
('jpgv', 'video/jpeg', 'mime-type-video.png', 'file-video-o'),
('jpm', 'video/jpm', 'mime-type-video.png', 'file-video-o'),
('jpgm', 'video/jpm', 'mime-type-video.png', 'file-video-o'),
('mj2', 'video/mj2', 'mime-type-video.png', 'file-video-o'),
('mjp2', 'video/mj2', 'mime-type-video.png', 'file-video-o'),
('ts', 'video/mp2t', 'mime-type-video.png', 'file-video-o'),
('mp4', 'video/mp4', 'mime-type-video.png', 'file-video-o'),
('mp4v', 'video/mp4', 'mime-type-video.png', 'file-video-o'),
('mpg4', 'video/mp4', 'mime-type-video.png', 'file-video-o'),
('m4v', 'video/mp4', 'mime-type-video.png', 'file-video-o'),
('mpeg', 'video/mpeg', 'mime-type-video.png', 'file-video-o'),
('mpg', 'video/mpeg', 'mime-type-video.png', 'file-video-o'),
('mpe', 'video/mpeg', 'mime-type-video.png', 'file-video-o'),
('m1v', 'video/mpeg', 'mime-type-video.png', 'file-video-o'),
('m2v', 'video/mpeg', 'mime-type-video.png', 'file-video-o'),
('ogv', 'video/ogg', 'mime-type-video.png', 'file-video-o'),
('qt', 'video/quicktime', 'mime-type-video.png', 'file-video-o'),
('mov', 'video/quicktime', 'mime-type-video.png', 'file-video-o'),
('uvh', 'video/vnd.dece.hd', 'mime-type-video.png', 'file-video-o'),
('uvvh', 'video/vnd.dece.hd', 'mime-type-video.png', 'file-video-o'),
('uvm', 'video/vnd.dece.mobile', 'mime-type-video.png', 'file-video-o'),
('uvvm', 'video/vnd.dece.mobile', 'mime-type-video.png', 'file-video-o'),
('uvp', 'video/vnd.dece.pd', 'mime-type-video.png', 'file-video-o'),
('uvvp', 'video/vnd.dece.pd', 'mime-type-video.png', 'file-video-o');
INSERT INTO `sys_storage_mime_types` (`ext`, `mime_type`, `icon`, `icon_font`) VALUES
('uvs', 'video/vnd.dece.sd', 'mime-type-video.png', 'file-video-o'),
('uvvs', 'video/vnd.dece.sd', 'mime-type-video.png', 'file-video-o'),
('uvv', 'video/vnd.dece.video', 'mime-type-video.png', 'file-video-o'),
('uvvv', 'video/vnd.dece.video', 'mime-type-video.png', 'file-video-o'),
('dvb', 'video/vnd.dvb.file', 'mime-type-video.png', 'file-video-o'),
('fvt', 'video/vnd.fvt', 'mime-type-video.png', 'file-video-o'),
('mxu', 'video/vnd.mpegurl', 'mime-type-video.png', 'file-video-o'),
('m4u', 'video/vnd.mpegurl', 'mime-type-video.png', 'file-video-o'),
('pyv', 'video/vnd.ms-playready.media.pyv', 'mime-type-video.png', 'file-video-o'),
('uvu', 'video/vnd.uvvu.mp4', 'mime-type-video.png', 'file-video-o'),
('uvvu', 'video/vnd.uvvu.mp4', 'mime-type-video.png', 'file-video-o'),
('viv', 'video/vnd.vivo', 'mime-type-video.png', 'file-video-o'),
('dv', 'video/x-dv', 'mime-type-video.png', 'file-video-o'),
('dif', 'video/x-dv', 'mime-type-video.png', 'file-excel-o'),
('webm', 'video/webm', 'mime-type-video.png', 'file-video-o'),
('f4v', 'video/x-f4v', 'mime-type-video.png', 'file-video-o'),
('fli', 'video/x-fli', 'mime-type-video.png', 'file-video-o'),
('flv', 'video/x-flv', 'mime-type-video.png', 'file-video-o'),
('mkv', 'video/x-matroska', 'mime-type-video.png', 'file-video-o'),
('mk3d', 'video/x-matroska', 'mime-type-video.png', 'file-video-o'),
('mks', 'video/x-matroska', 'mime-type-video.png', 'file-video-o'),
('mng', 'video/x-mng', 'mime-type-video.png', 'file-video-o'),
('asf', 'video/x-ms-asf', 'mime-type-video.png', 'file-video-o'),
('asx', 'video/x-ms-asf', 'mime-type-video.png', 'file-video-o'),
('vob', 'video/x-ms-vob', 'mime-type-video.png', 'file-video-o'),
('wm', 'video/x-ms-wm', 'mime-type-video.png', 'file-video-o'),
('wmv', 'video/x-ms-wmv', 'mime-type-video.png', 'file-video-o'),
('wmx', 'video/x-ms-wmx', 'mime-type-video.png', 'file-video-o'),
('wvx', 'video/x-ms-wvx', 'mime-type-video.png', 'file-video-o'),
('avi', 'video/x-msvideo', 'mime-type-video.png', 'file-video-o'),
('movie', 'video/x-sgi-movie', 'mime-type-video.png', 'file-video-o'),
('smv', 'video/x-smv', 'mime-type-video.png', 'file-video-o'),
('ice', 'x-conference/x-cooltalk', '', '');


-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_uploader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM;

INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_simple', 1, 'BxTemplUploaderSimple', ''),
('sys_html5', 1, 'BxTemplUploaderHTML5', ''),
('sys_crop', 1, 'BxTemplUploaderCrop', ''),
('sys_cmts_simple', 1, 'BxTemplCmtsUploaderSimple', ''),
('sys_settings_html5', 1, 'BxTemplStudioSettingsUploaderHTML5', ''),
('sys_builder_page_simple', 1, 'BxTemplStudioBuilderPageUploaderSimple', ''),
('sys_builder_page_html5', 1, 'BxTemplStudioBuilderPageUploaderHTML5', ''),
('sys_std_crop_cover', 1, 'BxTemplStudioUploaderCropCover', '');


-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `form_attrs` text NOT NULL,
  `submit_name` varchar(255) NOT NULL,
  `table` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `uri_title` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_login', 'system', '_sys_form_login', 'member.php', 'a:3:{s:2:"id";s:14:"sys-form-login";s:6:"action";s:10:"member.php";s:8:"onsubmit";s:31:"return validateLoginForm(this);";}', 'role', '', '', '', '', '', 0, 1, 'BxTemplFormLogin', ''),
('sys_account', 'system', '_sys_form_account', '', '', 'do_submit', 'sys_accounts', 'id', '', '', 'a:1:{s:14:"checker_helper";s:26:"BxFormAccountCheckerHelper";}', 0, 1, 'BxTemplFormAccount', ''),
('sys_forgot_password', 'system', '_sys_form_forgot_password', '', '', 'do_submit', '', '', '', '', 'a:1:{s:14:"checker_helper";s:33:"BxFormForgotPasswordCheckerHelper";}', 0, 1, 'BxTemplFormForgotPassword', ''),
('sys_confirm_email', 'system', '_sys_form_confirm_email', '', '', 'do_submit', '', '', '', '', 'a:1:{s:14:"checker_helper";s:31:"BxFormConfirmEmailCheckerHelper";}', 0, 1, 'BxTemplFormConfirmEmail', ''),
('sys_unsubscribe', 'system', '_sys_form_unsubscribe', '', '', 'do_submit', 'sys_accounts', 'id', '', '', '', 0, 1, 'BxTemplFormAccount', ''),
('sys_comment', 'system', '_sys_form_comment', 'cmts.php', 'a:3:{s:2:"id";s:17:"cmt-%s-form-%s-%d";s:4:"name";s:17:"cmt-%s-form-%s-%d";s:5:"class";s:14:"cmt-post-reply";}', 'cmt_submit', '', 'cmt_id', '', '', '', 0, 1, 'BxTemplCmtsForm', ''),
('sys_report', 'system', '_sys_form_report', 'report.php', 'a:3:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:5:"class";s:17:"bx-report-do-form";}', 'submit', '', 'id', '', '', '', 0, 1, '', '');

CREATE TABLE IF NOT EXISTS `sys_form_displays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `view_mode` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_display_name` (`object`,`display_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_login', 'system', 'sys_login', '_sys_form_display_login', 0),
('sys_account_create', 'system', 'sys_account', '_sys_form_display_account_create', 0),
('sys_account_settings_email', 'system', 'sys_account', '_sys_form_display_account_settings_email', 0),
('sys_account_settings_pwd', 'system', 'sys_account', '_sys_form_display_account_settings_password', 0),
('sys_account_settings_info', 'system', 'sys_account', '_sys_form_display_account_settings_info', 0),
('sys_account_settings_del_account', 'system', 'sys_account', '_sys_form_display_account_settings_delete', 0),
('sys_forgot_password', 'system', 'sys_forgot_password', '_sys_form_display_forgot_password', 0),
('sys_confirm_email', 'system', 'sys_confirm_email', '_sys_form_display_confirm_email', 0),
('sys_unsubscribe_updates', 'system', 'sys_unsubscribe', '_sys_form_display_unsubscribe_updates', 0),
('sys_unsubscribe_news', 'system', 'sys_unsubscribe', '_sys_form_display_unsubscribe_news', 0),
('sys_comment_post', 'system', 'sys_comment', '_sys_form_display_comment_post', 0),
('sys_comment_edit', 'system', 'sys_comment', '_sys_form_display_comment_edit', 0),
('sys_report_post', 'system', 'sys_report', '_sys_form_display_report_post', 0);


CREATE TABLE IF NOT EXISTS `sys_form_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `values` text NOT NULL,
  `checked` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL,
  `caption_system` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `unique` tinyint(4) NOT NULL DEFAULT '0',
  `collapsed` tinyint(4) NOT NULL DEFAULT '0',
  `html` tinyint(4) NOT NULL DEFAULT '0',
  `attrs` text NOT NULL,
  `attrs_tr` text NOT NULL,
  `attrs_wrapper` text NOT NULL,
  `checker_func` varchar(32) NOT NULL,
  `checker_params` text NOT NULL,
  `checker_error` varchar(255) NOT NULL,
  `db_pass` varchar(32) NOT NULL,
  `db_params` text NOT NULL,
  `editable` tinyint(4) NOT NULL DEFAULT '1',
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `display_name` (`object`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_login', 'system', 'role', '1', '', 0, 'hidden', '_sys_form_login_input_caption_system_role', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'relocate', '', '', 0, 'hidden', '_sys_form_login_input_caption_system_relocate', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'ID', '', '', 0, 'text', '_sys_form_login_input_caption_system_id', '_sys_form_login_input_email', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'Password', '', '', 0, 'password', '_sys_form_login_input_caption_system_password', '_sys_form_login_input_password', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'rememberMe', '1', '', 0, 'switcher', '_sys_form_login_input_caption_system_remember_me', '_sys_form_login_input_remember_me', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_login', 'system', 'login', '_sys_form_login_input_submit', '', 0, 'submit', '_sys_form_login_input_caption_system_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_login', 'system', 'submit_text', '', '', 0, 'custom', '_sys_form_login_input_caption_system_submit_text', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_account', 'system', 'email', '', '', 0, 'text', '_sys_form_login_input_caption_system_email', '_sys_form_account_input_email', '', 1, 0, 0, '', '', '', 'EmailUniq', '', '_sys_form_account_input_email_error', 'Xss', '', 0, 0),
('sys_account', 'system', 'password', '', '', 0, 'password', '_sys_form_login_input_caption_system_password', '_sys_form_account_input_password', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:6;s:3:"max";i:1024;}', '_sys_form_account_input_password_error', '', '', 0, 0),
('sys_account', 'system', 'password_confirm', '', '', 0, 'password', '_sys_form_login_input_caption_system_password_confirm', '_sys_form_account_input_password_confirm', '', 1, 0, 0, '', '', '', 'PasswordConfirm', '', '_sys_form_account_input_password_confirm_error', '', '', 0, 0),
('sys_account', 'system', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_sys_form_login_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_account', 'system', 'name', '', '', 0, 'text', '_sys_form_login_input_caption_system_name', '_sys_form_account_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_sys_form_account_input_name_error', 'Xss', '', 1, 0),
('sys_account', 'system', 'captcha', '', '', 0, 'captcha', '_sys_form_login_input_caption_system_captcha', '_sys_form_account_input_captcha', '', 1, 0, 0, '', '', '', 'Captcha', '', '_sys_form_account_input_captcha_error', '', '', 1, 0),
('sys_account', 'system', 'password_current', '', '', 0, 'password', '_sys_form_login_input_caption_system_password_current', '_sys_form_account_input_password_current', '', 1, 0, 0, '', '', '', 'PasswordCurrent', '', '_sys_form_account_input_password_current_error', '', '', 0, 0),
('sys_account', 'system', 'delete_content', '0', '', 0, 'hidden', '_sys_form_login_input_caption_system_delete_content', '', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_account', 'system', 'delete_confirm', '1', '', 0, 'checkbox', '_sys_form_login_input_caption_system_delete_confirm', '_sys_form_account_input_delete_confirm', '_sys_form_account_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_sys_form_account_input_delete_confirm_error', '', '', 0, 0),
('sys_account', 'system', 'receive_updates', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_updates', '_sys_form_account_input_receive_updates', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_account', 'system', 'receive_news', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_news', '_sys_form_account_input_receive_news', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('sys_account', 'system', 'agreement', '', '', 0, 'custom', '_sys_form_login_input_caption_system_agreement', '_sys_form_account_input_agreement', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('sys_forgot_password', 'system', 'email', '', '', 0, 'text', '_sys_form_forgot_password_input_caption_system_email', '_sys_form_forgot_password_input_email', '', 1, 0, 0, '', '', '', 'EmailExist', '', '_sys_form_account_input_email_error', 'Xss', '', 0, 0),
('sys_forgot_password', 'system', 'captcha', '', '', 0, 'captcha', '_sys_form_login_input_caption_system_captcha', '_sys_form_account_input_captcha', '', 1, 0, 0, '', '', '', 'Captcha', '', '_sys_form_account_input_captcha_error', '', '', 0, 0),
('sys_forgot_password', 'system', 'do_submit', '_sys_form_forgot_password_input_submit', '', 0, 'submit', '_sys_form_forgot_password_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_confirm_email', 'system', 'code', '', '', 0, 'text', '_sys_form_confirm_email_input_caption_system_code', '_sys_form_confirm_email_input_code', '', 1, 0, 0, '', '', '', 'CodeExist', '', '_sys_form_confirm_email_input_code_error', 'Xss', '', 0, 0),
('sys_confirm_email', 'system', 'do_submit', '_sys_form_confirm_email_input_submit', '', 0, 'submit', '_sys_form_confirm_email_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_unsubscribe', 'system', 'id', '', '', 0, 'hidden', '_sys_form_unsubscribe_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_unsubscribe', 'system', 'code', '', '', 0, 'hidden', '_sys_form_unsubscribe_input_caption_system_code', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_unsubscribe', 'system', 'receive_updates', '1', '', 0, 'switcher', '_sys_form_unsubscribe_input_caption_system_receive_updates', '_sys_form_unsubscribe_input_receive_updates', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_unsubscribe', 'system', 'receive_news', '1', '', 0, 'switcher', '_sys_form_unsubscribe_input_caption_system_receive_news', '_sys_form_unsubscribe_input_receive_news', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_unsubscribe', 'system', 'do_submit', '_sys_form_unsubscribe_input_submit', '', 0, 'submit', '_sys_form_unsubscribe_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_comment', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_comment_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_comment', 'system', 'id', '', '', 0, 'hidden', '_sys_form_comment_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_comment', 'system', 'action', '', '', 0, 'hidden', '_sys_form_comment_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_comment', 'system', 'cmt_id', '', '', 0, 'hidden', '_sys_form_comment_input_caption_system_cmt_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_comment', 'system', 'cmt_parent_id', '', '', 0, 'hidden', '_sys_form_comment_input_caption_system_cmt_parent_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_comment', 'system', 'cmt_text', '', '', 0, 'textarea', '_sys_form_comment_input_caption_system_cmt_text', '', '', 0, 0, 0, 'a:1:{s:12:"autocomplete";s:3:"off";}', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:5000;}', '_Please enter n1-n2 characters', 'Xss', '', 1, 0),
('sys_comment', 'system', 'cmt_image', 'a:1:{i:0;s:15:"sys_cmts_simple";}', 'a:1:{s:15:"sys_cmts_simple";s:26:"_sys_uploader_simple_title";}', 0, 'files', '_sys_form_comment_input_caption_system_cmt_image', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_comment', 'system', 'cmt_submit', '_sys_form_comment_input_submit', '', 0, 'submit', '_sys_form_comment_input_caption_system_cmt_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_report', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_report', 'system', 'object_id', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_object_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_report', 'system', 'action', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_report', 'system', 'id', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_report', 'system', 'type', '', '#!sys_report_types', 0, 'select', '_sys_form_report_input_caption_system_type', '_sys_form_report_input_caption_type', '', 1, 0, 0, '', '', '', 'Avail', '', '_Please select value', 'Xss', '', 1, 0),
('sys_report', 'system', 'text', '', '', 0, 'textarea', '_sys_form_report_input_caption_system_text', '_sys_form_report_input_caption_text', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_report', 'system', 'submit', '_sys_form_report_input_caption_submit', '', 0, 'submit', '_sys_form_report_input_caption_system_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);


CREATE TABLE IF NOT EXISTS `sys_form_display_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(64) NOT NULL,
  `input_name` varchar(32) NOT NULL,
  `visible_for_levels` int(11) NOT NULL DEFAULT '2147483647',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `display_input` (`display_name`,`input_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES

('sys_login', 'role', 2147483647, 1, 1),
('sys_login', 'relocate', 2147483647, 1, 2),
('sys_login', 'ID', 2147483647, 1, 3),
('sys_login', 'Password', 2147483647, 1, 4),
('sys_login', 'rememberMe', 2147483647, 1, 5),
('sys_login', 'login', 2147483647, 1, 6),
('sys_login', 'submit_text', 2147483647, 1, 7),

('sys_account_create', 'name', 2147483647, 1, 1),
('sys_account_create', 'email', 2147483647, 1, 2),
('sys_account_create', 'password', 2147483647, 1, 3),
('sys_account_create', 'receive_news', 2147483647, 1, 4),
('sys_account_create', 'do_submit', 2147483647, 1, 5),
('sys_account_create', 'agreement', 2147483647, 1, 7),

('sys_account_settings_email', 'email', 2147483647, 1, 1),
('sys_account_settings_email', 'password_current', 2147483647, 1, 2),
('sys_account_settings_email', 'receive_updates', 2147483647, 1, 3),
('sys_account_settings_email', 'receive_news', 2147483647, 1, 4),
('sys_account_settings_email', 'do_submit', 2147483647, 1, 5),

('sys_account_settings_pwd', 'password_current', 2147483647, 1, 1),
('sys_account_settings_pwd', 'password', 2147483647, 1, 2),
('sys_account_settings_pwd', 'password_confirm', 2147483647, 1, 3),
('sys_account_settings_pwd', 'do_submit', 2147483647, 1, 4),

('sys_account_settings_del_account', 'delete_content', 2147483647, 1, 0),
('sys_account_settings_del_account', 'delete_confirm', 2147483647, 1, 1),
('sys_account_settings_del_account', 'password_current', 2147483647, 1, 2),
('sys_account_settings_del_account', 'do_submit', 2147483647, 1, 3),

('sys_account_settings_info', 'name', 2147483647, 1, 1),
('sys_account_settings_info', 'do_submit', 2147483647, 1, 2),

('sys_forgot_password', 'email', 2147483647, 1, 1),
('sys_forgot_password', 'captcha', 2147483647, 1, 2),
('sys_forgot_password', 'do_submit', 2147483647, 1, 3),

('sys_confirm_email', 'code', 2147483647, 1, 1),
('sys_confirm_email', 'do_submit', 2147483647, 1, 2),

('sys_unsubscribe_updates', 'id', 2147483647, 1, 1),
('sys_unsubscribe_updates', 'code', 2147483647, 1, 2),
('sys_unsubscribe_updates', 'receive_updates', 2147483647, 1, 3),
('sys_unsubscribe_updates', 'do_submit', 2147483647, 1, 4),

('sys_unsubscribe_news', 'id', 2147483647, 1, 1),
('sys_unsubscribe_news', 'code', 2147483647, 1, 2),
('sys_unsubscribe_news', 'receive_news', 2147483647, 1, 3),
('sys_unsubscribe_news', 'do_submit', 2147483647, 1, 4),

('sys_comment_post', 'sys', 2147483647, 1, 1),
('sys_comment_post', 'id', 2147483647, 1, 2),
('sys_comment_post', 'action', 2147483647, 1, 3),
('sys_comment_post', 'cmt_id', 2147483647, 0, 4),
('sys_comment_post', 'cmt_parent_id', 2147483647, 1, 5),
('sys_comment_post', 'cmt_text', 2147483647, 1, 6),
('sys_comment_post', 'cmt_image', 2147483647, 1, 7),
('sys_comment_post', 'cmt_submit', 2147483647, 1, 8),

('sys_comment_edit', 'sys', 2147483647, 1, 1),
('sys_comment_edit', 'id', 2147483647, 1, 2),
('sys_comment_edit', 'action', 2147483647, 1, 3),
('sys_comment_edit', 'cmt_id', 2147483647, 1, 4),
('sys_comment_edit', 'cmt_parent_id', 2147483647, 1, 5),
('sys_comment_edit', 'cmt_text', 2147483647, 1, 6),
('sys_comment_edit', 'cmt_image', 2147483647, 0, 7),
('sys_comment_edit', 'cmt_submit', 2147483647, 1, 8),

('sys_report_post', 'sys', 2147483647, 1, 1),
('sys_report_post', 'object_id', 2147483647, 1, 2),
('sys_report_post', 'action', 2147483647, 1, 3),
('sys_report_post', 'id', 2147483647, 0, 4),
('sys_report_post', 'type', 2147483647, 1, 5),
('sys_report_post', 'text', 2147483647, 1, 6),
('sys_report_post', 'submit', 2147483647, 1, 7);


CREATE TABLE `sys_form_pre_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL default '',
  `key` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `use_for_sets` tinyint(4) unsigned NOT NULL default '1',
  `extendable` tinyint(4) unsigned NOT NULL default '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  FULLTEXT KEY `ModuleAndKey` (`module`, `key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('Country', '_adm_form_txt_pre_lists_country', 'system', '0', '0'),
('Sex', '_adm_form_txt_pre_lists_sex', 'system', '1', '1'),
('Language', '_adm_form_txt_pre_lists_language', 'system', '0', '1'),
('sys_report_types', '_sys_pre_lists_report_types', 'system', '0', '0');


CREATE TABLE `sys_form_pre_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(255) NOT NULL default '',
  `Value` varchar(255) NOT NULL default '',
  `Order` int(10) unsigned NOT NULL default '0',
  `LKey` varchar(255) NOT NULL default '',
  `LKey2` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `KeyAndValue` (`Key`, `Value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('Country', 'AF', 1, '__Afghanistan', ''),
('Country', 'AX', 2, '__Aland_Islands', ''),
('Country', 'AL', 3, '__Albania', ''),
('Country', 'DZ', 4, '__Algeria', ''),
('Country', 'AS', 5, '__American Samoa', ''),
('Country', 'AD', 6, '__Andorra', ''),
('Country', 'AO', 7, '__Angola', ''),
('Country', 'AI', 8, '__Anguilla', ''),
('Country', 'AQ', 9, '__Antarctica', ''),
('Country', 'AG', 10, '__Antigua and Barbuda', ''),
('Country', 'AR', 11, '__Argentina', ''),
('Country', 'AM', 12, '__Armenia', ''),
('Country', 'AW', 13, '__Aruba', ''),
('Country', 'AU', 14, '__Australia', ''),
('Country', 'AT', 15, '__Austria', ''),
('Country', 'AZ', 16, '__Azerbaijan', ''),
('Country', 'BH', 17, '__Bahrain', ''),
('Country', 'BD', 18, '__Bangladesh', ''),
('Country', 'BB', 19, '__Barbados', ''),
('Country', 'BY', 20, '__Belarus', ''),
('Country', 'BE', 21, '__Belgium', ''),
('Country', 'BZ', 22, '__Belize', ''),
('Country', 'BJ', 23, '__Benin', ''),
('Country', 'BM', 24, '__Bermuda', ''),
('Country', 'BT', 25, '__Bhutan', ''),
('Country', 'BO', 26, '__Bolivia', ''),
('Country', 'BA', 27, '__Bosnia and Herzegovina', ''),
('Country', 'BW', 28, '__Botswana', ''),
('Country', 'BV', 29, '__Bouvet Island', ''),
('Country', 'BR', 30, '__Brazil', ''),
('Country', 'IO', 31, '__British Indian Ocean Territory', ''),
('Country', 'VG', 32, '__British Virgin Islands', ''),
('Country', 'BN', 33, '__Brunei Darussalam', ''),
('Country', 'BG', 34, '__Bulgaria', ''),
('Country', 'BF', 35, '__Burkina Faso', ''),
('Country', 'MM', 36, '__Burma', ''),
('Country', 'BI', 37, '__Burundi', ''),
('Country', 'KH', 38, '__Cambodia', ''),
('Country', 'CM', 39, '__Cameroon', ''),
('Country', 'CA', 40, '__Canada', ''),
('Country', 'CV', 41, '__Cape Verde', ''),
('Country', 'KY', 42, '__Cayman Islands', ''),
('Country', 'CF', 43, '__Central African Republic', ''),
('Country', 'TD', 44, '__Chad', ''),
('Country', 'CL', 45, '__Chile', ''),
('Country', 'CN', 46, '__China', ''),
('Country', 'CX', 47, '__Christmas Island', ''),
('Country', 'CC', 48, '__Cocos (Keeling) Islands', ''),
('Country', 'CO', 49, '__Colombia', ''),
('Country', 'KM', 50, '__Comoros', ''),
('Country', 'CD', 51, '__Congo, Democratic Republic of the', ''),
('Country', 'CG', 52, '__Congo, Republic of the', ''),
('Country', 'CK', 53, '__Cook Islands', ''),
('Country', 'CR', 54, '__Costa Rica', ''),
('Country', 'CI', 55, '__Cote d''Ivoire', ''),
('Country', 'HR', 56, '__Croatia', ''),
('Country', 'CU', 57, '__Cuba', ''),
('Country', 'CY', 58, '__Cyprus', ''),
('Country', 'CZ', 59, '__Czech Republic', ''),
('Country', 'DK', 60, '__Denmark', ''),
('Country', 'DJ', 61, '__Djibouti', ''),
('Country', 'DM', 62, '__Dominica', ''),
('Country', 'DO', 63, '__Dominican Republic', ''),
('Country', 'TL', 64, '__East Timor', ''),
('Country', 'EC', 65, '__Ecuador', ''),
('Country', 'EG', 66, '__Egypt', ''),
('Country', 'SV', 67, '__El Salvador', ''),
('Country', 'GQ', 68, '__Equatorial Guinea', ''),
('Country', 'ER', 69, '__Eritrea', ''),
('Country', 'EE', 70, '__Estonia', ''),
('Country', 'ET', 71, '__Ethiopia', ''),
('Country', 'FK', 72, '__Falkland Islands (Islas Malvinas)', ''),
('Country', 'FO', 73, '__Faroe Islands', ''),
('Country', 'FJ', 74, '__Fiji', ''),
('Country', 'FI', 75, '__Finland', ''),
('Country', 'FR', 76, '__France', ''),
('Country', 'GF', 77, '__French Guiana', ''),
('Country', 'PF', 78, '__French Polynesia', ''),
('Country', 'TF', 79, '__French Southern and Antarctic Lands', ''),
('Country', 'GA', 80, '__Gabon', ''),
('Country', 'GE', 81, '__Georgia', ''),
('Country', 'DE', 82, '__Germany', ''),
('Country', 'GH', 83, '__Ghana', ''),
('Country', 'GI', 84, '__Gibraltar', ''),
('Country', 'GR', 85, '__Greece', ''),
('Country', 'GL', 86, '__Greenland', ''),
('Country', 'GD', 87, '__Grenada', ''),
('Country', 'GP', 88, '__Guadeloupe', ''),
('Country', 'GU', 89, '__Guam', ''),
('Country', 'GT', 90, '__Guatemala', ''),
('Country', 'GG', 91, '__Guernsey', ''),
('Country', 'GN', 92, '__Guinea', ''),
('Country', 'GW', 93, '__Guinea-Bissau', ''),
('Country', 'GY', 94, '__Guyana', ''),
('Country', 'HT', 95, '__Haiti', ''),
('Country', 'HM', 96, '__Heard Island and McDonald Islands', ''),
('Country', 'VA', 97, '__Holy See (Vatican City)', ''),
('Country', 'HN', 98, '__Honduras', ''),
('Country', 'HK', 99, '__Hong Kong (SAR)', ''),
('Country', 'HU', 100, '__Hungary', ''),
('Country', 'IS', 101, '__Iceland', ''),
('Country', 'IN', 102, '__India', ''),
('Country', 'ID', 103, '__Indonesia', ''),
('Country', 'IR', 104, '__Iran', ''),
('Country', 'IQ', 105, '__Iraq', ''),
('Country', 'IE', 106, '__Ireland', ''),
('Country', 'IM', 107, '__Isle_of_Man', ''),
('Country', 'IL', 108, '__Israel', ''),
('Country', 'IT', 109, '__Italy', ''),
('Country', 'JM', 110, '__Jamaica', ''),
('Country', 'JP', 111, '__Japan', ''),
('Country', 'JE', 112, '__Jersey', ''),
('Country', 'JO', 113, '__Jordan', ''),
('Country', 'KZ', 114, '__Kazakhstan', ''),
('Country', 'KE', 115, '__Kenya', ''),
('Country', 'KI', 116, '__Kiribati', ''),
('Country', 'KP', 117, '__Korea, North', ''),
('Country', 'KR', 118, '__Korea, South', ''),
('Country', 'KW', 119, '__Kuwait', ''),
('Country', 'KG', 120, '__Kyrgyzstan', ''),
('Country', 'LA', 121, '__Laos', ''),
('Country', 'LV', 122, '__Latvia', ''),
('Country', 'LB', 123, '__Lebanon', ''),
('Country', 'LS', 124, '__Lesotho', ''),
('Country', 'LR', 125, '__Liberia', ''),
('Country', 'LY', 126, '__Libya', ''),
('Country', 'LI', 127, '__Liechtenstein', ''),
('Country', 'LT', 128, '__Lithuania', ''),
('Country', 'LU', 129, '__Luxembourg', ''),
('Country', 'MO', 130, '__Macao', ''),
('Country', 'MK', 131, '__Macedonia, The Former Yugoslav Republic of', ''),
('Country', 'MG', 132, '__Madagascar', ''),
('Country', 'MW', 133, '__Malawi', ''),
('Country', 'MY', 134, '__Malaysia', ''),
('Country', 'MV', 135, '__Maldives', ''),
('Country', 'ML', 136, '__Mali', ''),
('Country', 'MT', 137, '__Malta', ''),
('Country', 'MH', 138, '__Marshall Islands', ''),
('Country', 'MQ', 139, '__Martinique', ''),
('Country', 'MR', 140, '__Mauritania', ''),
('Country', 'MU', 141, '__Mauritius', ''),
('Country', 'YT', 142, '__Mayotte', ''),
('Country', 'MX', 143, '__Mexico', ''),
('Country', 'FM', 144, '__Micronesia, Federated States of', ''),
('Country', 'MD', 145, '__Moldova', ''),
('Country', 'MC', 146, '__Monaco', ''),
('Country', 'MN', 147, '__Mongolia', ''),
('Country', 'ME', 148, '__Montenegro', ''),
('Country', 'MS', 149, '__Montserrat', ''),
('Country', 'MA', 150, '__Morocco', ''),
('Country', 'MZ', 151, '__Mozambique', ''),
('Country', 'NA', 152, '__Namibia', ''),
('Country', 'NR', 153, '__Nauru', ''),
('Country', 'NP', 154, '__Nepal', ''),
('Country', 'NL', 155, '__Netherlands', ''),
('Country', 'AN', 156, '__Netherlands Antilles', ''),
('Country', 'NC', 157, '__New Caledonia', ''),
('Country', 'NZ', 158, '__New Zealand', ''),
('Country', 'NI', 159, '__Nicaragua', ''),
('Country', 'NE', 160, '__Niger', ''),
('Country', 'NG', 161, '__Nigeria', ''),
('Country', 'NU', 162, '__Niue', ''),
('Country', 'NF', 163, '__Norfolk Island', ''),
('Country', 'MP', 164, '__Northern Mariana Islands', ''),
('Country', 'NO', 165, '__Norway', ''),
('Country', 'OM', 166, '__Oman', ''),
('Country', 'PK', 167, '__Pakistan', ''),
('Country', 'PW', 168, '__Palau', ''),
('Country', 'PS', 169, '__Palestinian Territory, Occupied', ''),
('Country', 'PA', 170, '__Panama', ''),
('Country', 'PG', 171, '__Papua New Guinea', ''),
('Country', 'PY', 172, '__Paraguay', ''),
('Country', 'PE', 173, '__Peru', ''),
('Country', 'PH', 174, '__Philippines', ''),
('Country', 'PN', 175, '__Pitcairn Islands', ''),
('Country', 'PL', 176, '__Poland', ''),
('Country', 'PT', 177, '__Portugal', ''),
('Country', 'PR', 178, '__Puerto Rico', ''),
('Country', 'QA', 179, '__Qatar', ''),
('Country', 'RE', 180, '__Reunion', ''),
('Country', 'RO', 181, '__Romania', ''),
('Country', 'RU', 182, '__Russia', ''),
('Country', 'RW', 183, '__Rwanda', ''),
('Country', 'SH', 184, '__Saint Helena', ''),
('Country', 'KN', 185, '__Saint Kitts and Nevis', ''),
('Country', 'LC', 186, '__Saint Lucia', ''),
('Country', 'PM', 187, '__Saint Pierre and Miquelon', ''),
('Country', 'VC', 188, '__Saint Vincent and the Grenadines', ''),
('Country', 'BL', 189, '__Saint_Barthelemy', ''),
('Country', 'MF', 190, '__Saint_Martin_French_part', ''),
('Country', 'WS', 191, '__Samoa', ''),
('Country', 'SM', 192, '__San Marino', ''),
('Country', 'ST', 193, '__Sao Tome and Principe', ''),
('Country', 'SA', 194, '__Saudi Arabia', ''),
('Country', 'SN', 195, '__Senegal', ''),
('Country', 'RS', 196, '__Serbia', ''),
('Country', 'SC', 197, '__Seychelles', ''),
('Country', 'SL', 198, '__Sierra Leone', ''),
('Country', 'SG', 199, '__Singapore', ''),
('Country', 'SK', 200, '__Slovakia', ''),
('Country', 'SI', 201, '__Slovenia', ''),
('Country', 'SB', 202, '__Solomon Islands', ''),
('Country', 'SO', 203, '__Somalia', ''),
('Country', 'ZA', 204, '__South Africa', ''),
('Country', 'GS', 205, '__South Georgia and the South Sandwich Islands', ''),
('Country', 'ES', 206, '__Spain', ''),
('Country', 'LK', 207, '__Sri Lanka', ''),
('Country', 'SD', 208, '__Sudan', ''),
('Country', 'SR', 209, '__Suriname', ''),
('Country', 'SJ', 210, '__Svalbard', ''),
('Country', 'SZ', 211, '__Swaziland', ''),
('Country', 'SE', 212, '__Sweden', ''),
('Country', 'CH', 213, '__Switzerland', ''),
('Country', 'SY', 214, '__Syria', ''),
('Country', 'TW', 215, '__Taiwan', ''),
('Country', 'TJ', 216, '__Tajikistan', ''),
('Country', 'TZ', 217, '__Tanzania', ''),
('Country', 'TH', 218, '__Thailand', ''),
('Country', 'BS', 219, '__The Bahamas', ''),
('Country', 'GM', 220, '__The Gambia', ''),
('Country', 'TG', 221, '__Togo', ''),
('Country', 'TK', 222, '__Tokelau', ''),
('Country', 'TO', 223, '__Tonga', ''),
('Country', 'TT', 224, '__Trinidad and Tobago', ''),
('Country', 'TN', 225, '__Tunisia', ''),
('Country', 'TR', 226, '__Turkey', ''),
('Country', 'TM', 227, '__Turkmenistan', ''),
('Country', 'TC', 228, '__Turks and Caicos Islands', ''),
('Country', 'TV', 229, '__Tuvalu', ''),
('Country', 'UG', 230, '__Uganda', ''),
('Country', 'UA', 231, '__Ukraine', ''),
('Country', 'AE', 232, '__United Arab Emirates', ''),
('Country', 'GB', 233, '__United Kingdom', ''),
('Country', 'US', 234, '__United States', ''),
('Country', 'UM', 235, '__United States Minor Outlying Islands', ''),
('Country', 'UY', 236, '__Uruguay', ''),
('Country', 'UZ', 237, '__Uzbekistan', ''),
('Country', 'VU', 238, '__Vanuatu', ''),
('Country', 'VE', 239, '__Venezuela', ''),
('Country', 'VN', 240, '__Vietnam', ''),
('Country', 'VI', 241, '__Virgin Islands', ''),
('Country', 'WF', 242, '__Wallis and Futuna', ''),
('Country', 'EH', 243, '__Western Sahara', ''),
('Country', 'YE', 244, '__Yemen', ''),
('Country', 'ZM', 245, '__Zambia', ''),
('Country', 'ZW', 246, '__Zimbabwe', '');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('Sex', '', 1, '_sys_not_specified', ''),
('Sex', '1', 2, '_Male', '_LookinMale'),
('Sex', '2', 3, '_Female', '_LookinFemale');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES 
('Language', '1', 1, '__English', ''),
('Language', '2', 2, '__Afrikaans', ''),
('Language', '3', 3, '__Arabic', ''),
('Language', '4', 4, '__Bulgarian', ''),
('Language', '5', 5, '__Burmese', ''),
('Language', '6', 6, '__Cantonese', ''),
('Language', '7', 7, '__Croatian', ''),
('Language', '8', 8, '__Danish', ''),
('Language', '9', 9, '__Dutch', ''),
('Language', '10', 10, '__Esperanto', ''),
('Language', '11', 11, '__Estonian', ''),
('Language', '12', 12, '__Finnish', ''),
('Language', '13', 13, '__French', ''),
('Language', '14', 14, '__German', ''),
('Language', '15', 15, '__Greek', ''),
('Language', '16', 16, '__Gujrati', ''),
('Language', '17', 17, '__Hebrew', ''),
('Language', '18', 18, '__Hindi', ''),
('Language', '19', 19, '__Hungarian', ''),
('Language', '20', 20, '__Icelandic', ''),
('Language', '21', 21, '__Indian', ''),
('Language', '22', 22, '__Indonesian', ''),
('Language', '23', 23, '__Italian', ''),
('Language', '24', 24, '__Japanese', ''),
('Language', '25', 25, '__Korean', ''),
('Language', '26', 26, '__Latvian', ''),
('Language', '27', 27, '__Lithuanian', ''),
('Language', '28', 28, '__Malay', ''),
('Language', '29', 29, '__Mandarin', ''),
('Language', '30', 30, '__Marathi', ''),
('Language', '31', 31, '__Moldovian', ''),
('Language', '32', 32, '__Nepalese', ''),
('Language', '33', 33, '__Norwegian', ''),
('Language', '34', 34, '__Persian', ''),
('Language', '35', 35, '__Polish', ''),
('Language', '36', 36, '__Portuguese', ''),
('Language', '37', 37, '__Punjabi', ''),
('Language', '38', 38, '__Romanian', ''),
('Language', '39', 39, '__Russian', ''),
('Language', '40', 40, '__Serbian', ''),
('Language', '41', 41, '__Spanish', ''),
('Language', '42', 42, '__Swedish', ''),
('Language', '43', 43, '__Tagalog', ''),
('Language', '44', 44, '__Taiwanese', ''),
('Language', '45', 45, '__Tamil', ''),
('Language', '46', 46, '__Telugu', ''),
('Language', '47', 47, '__Thai', ''),
('Language', '48', 48, '__Tongan', ''),
('Language', '49', 49, '__Turkish', ''),
('Language', '50', 50, '__Ukrainian', ''),
('Language', '51', 51, '__Urdu', ''),
('Language', '52', 52, '__Vietnamese', ''),
('Language', '53', 53, '__Visayan', '');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('sys_report_types', 'spam', 1, '_sys_pre_lists_report_types_spam', ''),
('sys_report_types', 'scam', 2, '_sys_pre_lists_report_types_scam', ''),
('sys_report_types', 'fraud', 3, '_sys_pre_lists_report_types_fraud', ''),
('sys_report_types', 'nude', 4, '_sys_pre_lists_report_types_nude', ''),
('sys_report_types', 'other', 5, '_sys_pre_lists_report_types_other', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sys_menu_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(1, 'menu_empty.html', '_sys_menu_template_title_empty', 1),
(2, 'menu_footer.html', '_sys_menu_template_title_footer', 0),
(3, 'menu_horizontal.html', '_sys_menu_template_title_hor', 1),
(4, 'menu_vertical_lite.html', '_sys_menu_template_title_ver_lite', 1),
(5, 'menu_toolbar.html', '_sys_menu_template_title_toolbar', 0),
(6, 'menu_vertical.html', '_sys_menu_template_title_ver', 1),
(7, 'menu_floating_blocks.html', '_sys_menu_template_title_floating_blocks', 1),
(8, 'menu_main_submenu.html', '_sys_menu_template_title_main_submenu', 0),
(9, 'menu_buttons_hor.html', '_sys_menu_template_title_buttons_hor', 1),
(10, 'menu_inline.html', '_sys_menu_template_title_inline', 1),
(11, 'menu_interactive_vertical.html', '_sys_menu_template_title_interactive_vertical', 1),
(12, 'menu_account_popup.html', '_sys_menu_template_title_account_popup', 0),
(13, 'menu_account_notifications.html', '_sys_menu_template_title_account_notifications', 0),
(14, 'menu_floating_blocks_big.html', '_sys_menu_template_title_floating_blocks_big', 1),
(15, 'menu_custom.html', '_sys_menu_template_title_custom', 0),
(16, 'menu_buttons_ver.html', '_sys_menu_template_title_buttons_ver', 1),
(17, 'menu_inline_sbtn.html', '_sys_menu_template_title_inline_sbtn', 1),
(18, 'menu_icon_buttons_hor.html', '_sys_menu_template_title_icon_buttons_hor', 1),
(19, 'menu_floating_blocks_wide.html', '_sys_menu_template_title_floating_blocks_wide', 0);

CREATE TABLE IF NOT EXISTS `sys_objects_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `set_name` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `template_id` int(11) NOT NULL,
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_site', '_sys_menu_title_main', 'sys_site', 'system', 14, 0, 1, 'BxTemplMenuSite', ''),
('sys_homepage', '_sys_menu_title_homepage', 'sys_homepage', 'system', 14, 0, 1, 'BxTemplMenuHomepage', ''),
('sys_site_submenu', '_sys_menu_title_submenu', 'sys_site', 'system', 1, 0, 1, 'BxTemplMenuSubmenu', ''),
('sys_footer', '_sys_menu_title_footer', 'sys_footer', 'system', 2, 0, 1, 'BxTemplMenuFooter', ''),
('sys_toolbar_site', '_sys_menu_title_toolbar_site', 'sys_toolbar_site', 'system', 5, 0, 1, 'BxTemplMenuToolbar', ''),
('sys_toolbar_member', '_sys_menu_title_toolbar_member', 'sys_toolbar_member', 'system', 5, 0, 1, 'BxTemplMenuToolbar', ''),
('sys_add_content', '_sys_menu_title_add_content', 'sys_add_content_links', 'system', 14, 0, 1, 'BxTemplMenuSite', ''),
('sys_add_profile', '_sys_menu_title_add_profile', 'sys_add_profile_links', 'system', 14, 0, 1, 'BxTemplMenuProfileAdd', ''),
('sys_add_profile_vertical', '_sys_menu_title_add_profile_vertical', 'sys_add_profile_links', 'system', 6, 0, 1, 'BxTemplMenuProfileAdd', ''),
('sys_account_dashboard', '_sys_menu_title_account_dashboard', 'sys_account_dashboard', 'system', 8, 0, 1, 'BxTemplMenuAccountDashboard', ''),
('sys_account_dashboard_manage_tools', '_sys_menu_title_account_dashboard_manage_tools', 'sys_account_dashboard_manage_tools', 'system', 4, 0, 1, '', ''),
('sys_account_settings_submenu', '_sys_menu_title_account_settings', 'sys_account_settings', 'system', 8, 0, 1, '', ''),
('sys_account_settings_more', '_sys_menu_title_account_settings_more', 'sys_account_settings_more', 'system', 4, 0, 1, '', ''),
('sys_profiles_create', '_sys_menu_title_profiles_create', 'sys_profiles_create', 'system', 4, 0, 1, '', ''),
('sys_cmts_item_manage', '_sys_menu_title_cmts_item_manage', 'sys_cmts_item_manage', 'system', 6, 0, 1, 'BxTemplCmtsMenuManage', ''),
('sys_cmts_item_actions', '_sys_menu_title_cmts_item_actions', 'sys_cmts_item_actions', 'system', 15, 0, 1, 'BxTemplCmtsMenuActions', ''),
('sys_account_popup', '_sys_menu_title_account_popup', '', 'system', 12, 0, 1, 'BxTemplMenuAccountPopup', ''),
('sys_account_notifications', '_sys_menu_title_account_notifications', 'sys_account_notifications', 'system', 19, 0, 1, 'BxTemplMenuAccountNotifications', ''),
('sys_profile_stats', '_sys_menu_title_profile_stats', 'sys_profile_stats', 'system', 6, 0, 1, 'BxTemplMenuProfileStats', ''),
('sys_switch_language_popup', '_sys_menu_title_switch_language_popup', 'sys_switch_language', 'system', 6, 0, 1, 'BxTemplMenuSwitchLanguage', ''),
('sys_switch_language_inline', '_sys_menu_title_switch_language_inline', 'sys_switch_language', 'system', 3, 0, 1, 'BxTemplMenuSwitchLanguage', ''),
('sys_switch_template', '_sys_menu_title_switch_template', 'sys_switch_template', 'system', 6, 0, 1, 'BxTemplMenuSwitchTemplate', ''),
('sys_set_acl_level', '_sys_menu_title_set_acl_level', '', 'system', 6, 0, 1, 'BxTemplMenuSetAclLevel', ''),
('sys_social_sharing', '_sys_menu_title_social_sharing', 'sys_social_sharing', 'system', 18, 0, 1, 'BxTemplMenuSocialSharing', '');

CREATE TABLE IF NOT EXISTS `sys_menu_sets` (
  `set_name` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`set_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_site', 'system', '_sys_menu_set_title_site', 0),
('sys_homepage', 'system', '_sys_menu_set_title_homepage', 0),
('sys_footer', 'system', '_sys_menu_set_title_footer', 0),
('sys_toolbar_site', 'system', '_sys_menu_set_title_toolbar_site', 0),
('sys_toolbar_member', 'system', '_sys_menu_set_title_toolbar_member', 0),
('sys_account_notifications', 'system', '_sys_menu_set_title_account_notifications', 0),
('sys_add_content_links', 'system', '_sys_menu_set_title_add_content', 0),
('sys_add_profile_links', 'system', '_sys_menu_set_title_add_profile', 0),
('sys_account_dashboard', 'system', '_sys_menu_set_title_account_dashboard', 0),
('sys_account_dashboard_manage_tools', 'system', '_sys_menu_set_title_account_dashboard_manage_tools', 0),
('sys_account_settings', 'system', '_sys_menu_set_title_account_settings', 0),
('sys_account_settings_more', 'system', '_sys_menu_set_title_account_settings_more', 0),
('sys_profiles_create', 'system', '_sys_menu_set_title_profile_create_links', 0),
('sys_profile_stats', 'system', '_sys_menu_set_title_profile_stats', 0),
('sys_cmts_item_manage', 'system', '_sys_menu_set_title_cmts_item_manage', 0),
('sys_cmts_item_actions', 'system', '_sys_menu_set_title_cmts_item_actions', 0),
('sys_switch_language', 'system', '_sys_menu_set_title_switch_language', 0),
('sys_switch_template', 'system', '_sys_menu_set_title_switch_template', 0),
('sys_social_sharing', 'system', '_sys_menu_set_title_sys_social_sharing', 0);

CREATE TABLE IF NOT EXISTS `sys_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(64) NOT NULL,
  `module` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title_system` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `onclick` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `addon` text NOT NULL,
  `submenu_object` varchar(64) NOT NULL,
  `submenu_popup` tinyint(4) NOT NULL DEFAULT '0',
  `visible_for_levels` int(11) NOT NULL DEFAULT '2147483647',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `copyable` tinyint(4) NOT NULL DEFAULT '1',
  `editable` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- site menu
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_site', 'system', 'home', '_sys_menu_item_title_system_home', '_sys_menu_item_title_home', 'index.php', '', '', 'home col-gray-dark', '', 2147483647, 1, 1, 1),
('sys_site', 'system', 'about', '_sys_menu_item_title_system_about', '_sys_menu_item_title_about', 'page.php?i=about', '', '', 'info-circle col-blue3-dark', '', 2147483647, 1, 1, 2),
('sys_site', 'system', 'search', '_sys_menu_item_title_system_search', '_sys_menu_item_title_search', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-search\', this, \'site\');', '', 'search', '', 2147483647, 1, 1, 3);

-- footer menu
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_footer', 'system', 'about', '_sys_menu_item_title_system_about', '_sys_menu_item_title_about', 'page.php?i=about', '', '', '', '', 2147483647, 1, 1, 1),
('sys_footer', 'system', 'terms', '_sys_menu_item_title_system_terms', '_sys_menu_item_title_terms', 'page.php?i=terms', '', '', '', '', 2147483647, 1, 1, 2),
('sys_footer', 'system', 'privacy', '_sys_menu_item_title_system_privacy', '_sys_menu_item_title_privacy', 'page.php?i=privacy', '', '', '', '', 2147483647, 1, 1, 3),
('sys_footer', 'system', 'switch_language', '_sys_menu_item_title_system_switch_language', '_sys_menu_item_title_switch_language', 'javascript:void(0);', 'bx_menu_popup(''sys_switch_language_popup'', window);', '', '', '', 2147483647, 0, 1, 4),
('sys_footer', 'system', 'switch_template', '_sys_menu_item_title_system_switch_template', '_sys_menu_item_title_switch_template', 'javascript:void(0);', 'bx_menu_popup(''sys_switch_template'', window);', '', '', '', 2147483647, 1, 1, 5),
('sys_footer', 'system', 'powered_by', '_sys_menu_item_title_system_powered_by', '', 'https://una.io', '', '_blank', 'una.png', '', 2147483647, 1, 1, 9999);

-- site toolbar menu
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_site', 'system', 'main-menu', '_sys_menu_item_title_system_main_menu', '', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_site\', this, \'site\');', '', 'a:bars', '', 2147483647, 1, 1, 1),
('sys_toolbar_site', 'system', 'search', '_sys_menu_item_title_system_search', '', 'javascript:void(0);', 'bx_menu_slide(''#bx-sliding-menu-search'', this, ''site'');', '', 'search', '', 2147483647, 1, 1, 2);

-- member toolbar menu
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_member', 'system', 'add-content', '_sys_menu_item_title_system_add_content', '', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_add_content\', this, \'site\');', '', 'a:plus', '', '', 0, 510, 1, 1, 0),
('sys_toolbar_member', 'system', 'account', '_sys_menu_item_title_system_account_menu', '_sys_menu_item_title_account_menu', 'javascript:void(0);', 'bx_menu_slide(''#bx-sliding-menu-account'', this, ''site'');', '', 'user',  'a:3:{s:6:"module";s:6:"system";s:6:"method";s:21:"profile_notifications";s:5:"class";s:20:"TemplServiceProfiles";}', 'sys_account_popup', 1, 510, 1, 0, 1),
('sys_toolbar_member', 'system', 'login', '_sys_menu_item_title_system_login', '', 'page.php?i=login', '', '', 'user',  '', '', 0, 1, 1, 0, 2);

-- notifications menu in account popup
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'dashboard', '_sys_menu_item_title_system_dashboard', '_sys_menu_item_title_dashboard', 'page.php?i=dashboard', '', '', 'dashboard', '', '', 2147483646, 1, 1, 1),
('sys_account_notifications', 'system', 'profile', '_sys_menu_item_title_system_profile', '_sys_menu_item_title_profile', '{member_url}', '', '', 'user', '', '', 2147483646, 1, 1, 2),
('sys_account_notifications', 'system', 'account-settings', '_sys_menu_item_title_system_account_settings', '_sys_menu_item_title_account_settings', 'page.php?i=account-settings-email', '', '', 'cog', '', '', 2147483646, 1, 1, 3),
('sys_account_notifications', 'system', 'add-content', '_sys_menu_item_title_system_add_content', '_sys_menu_item_title_add_content', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_add_content\', $(\'bx-menu-toolbar-item-add-content a\').get(0), \'site\');', '', 'plus', '', '', 2147483646, 1, 1, 4),
('sys_account_notifications', 'system', 'studio', '_sys_menu_item_title_system_studio', '_sys_menu_item_title_studio', '{studio_url}', '', '', 'wrench', '', '', 2147483646, 1, 0, 5),
('sys_account_notifications', 'system', 'cart', '_sys_menu_item_title_system_cart', '_sys_menu_item_title_cart', 'cart.php', '', '', 'cart-plus col-red3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_cart_items_count";s:6:"params";a:0:{}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 1, 1, 6),
('sys_account_notifications', 'system', 'orders', '_sys_menu_item_title_system_orders', '_sys_menu_item_title_orders', 'orders.php', '', '', 'cart-arrow-down col-green3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_orders_count";s:6:"params";a:1:{i:0;s:3:"new";}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 0, 1, 7),
('sys_account_notifications', 'system', 'logout', '_sys_menu_item_title_system_logout', '_sys_menu_item_title_logout', 'logout.php', '', '', 'sign-out', '', '', 2147483646, 1, 1, 9999);

-- account settings menu
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_settings', 'system', 'account-settings-profile-context', '_sys_menu_item_title_system_account_profile_context', '_sys_menu_item_title_account_profile_context', 'page.php?i=account-profile-switcher', '', '', 'user', '', 2147483646, 1, 1, 1),
('sys_account_settings', 'system', 'account-settings-info', '_sys_menu_item_title_system_account_settings_info', '_sys_menu_item_title_account_settings_info', 'page.php?i=account-settings-info', '', '', 'info-circle', '', 2147483646, 1, 1, 2),
('sys_account_settings', 'system', 'account-settings-email', '_sys_menu_item_title_system_account_settings_email', '_sys_menu_item_title_account_settings_email', 'page.php?i=account-settings-email', '', '', 'envelope', '', 2147483646, 1, 1, 3),
('sys_account_settings', 'system', 'account-settings-password', '_sys_menu_item_title_system_account_settings_pwd', '_sys_menu_item_title_account_settings_pwd', 'page.php?i=account-settings-password', '', '', 'key', '', 2147483646, 1, 1, 4),
('sys_account_settings', 'system', 'account-settings-more', '_sys_menu_item_title_system_more', '_sys_menu_item_title_more', 'javascript:void(0);', 'bx_menu_popup(''sys_account_settings_more'', this);', '', '', '', 2147483647, 1, 1, 9999);

-- account settings menu more
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_settings_more', 'system', 'account-settings-delete', '_sys_menu_item_title_system_account_settings_delete', '_sys_menu_item_title_account_settings_delete', 'page.php?i=account-settings-delete', '', '', 'remove', '', 2147483646, 1, 1, 1);

-- account dashboard
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_dashboard', 'system', 'dashboard', '_sys_menu_item_title_system_account_dashboard', '_sys_menu_item_title_account_dashboard', 'page.php?i=dashboard', '', '', 'dashboard', '', '', 2147483646, 1, 1, 1),
('sys_account_dashboard', 'system', 'dashboard-subscriptions', '_sys_menu_item_title_system_subscriptions', '_sys_menu_item_title_subscriptions', 'subscriptions.php', '', '', 'money col-blue3', '', '', 2147483646, 1, 1, 2),
('sys_account_dashboard', 'system', 'dashboard-orders', '_sys_menu_item_title_system_orders', '_sys_menu_item_title_orders', 'orders.php', '', '', 'cart-arrow-down col-green3', '', '', 2147483646, 1, 1, 3);

-- comment manage menu
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_cmts_item_manage', 'system', 'item-edit', '_sys_menu_item_title_system_cmts_item_edit', '_sys_menu_item_title_cmts_item_edit', 'javascript:void(0)', 'javascript:{js_object}.cmtEdit(this, {content_id})', '_self', 'pencil', '', 2147483647, 1, 0, 0),
('sys_cmts_item_manage', 'system', 'item-delete', '_sys_menu_item_title_system_cmts_item_delete', '_sys_menu_item_title_cmts_item_delete', 'javascript:void(0)', 'javascript:{js_object}.cmtRemove(this, {content_id})', '_self', 'remove', '', 2147483647, 1, 0, 0);

-- comment actions menu
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_actions', 'system', 'item-vote', '_sys_menu_item_title_system_cmts_item_vote', '_sys_menu_item_title_cmts_item_vote', 'javascript:void(0)', '', '', '', '', '', 2147483647, 1, 0, 0, 1),
('sys_cmts_item_actions', 'system', 'item-reply', '_sys_menu_item_title_system_cmts_item_reply', '_sys_menu_item_title_cmts_item_reply', 'javascript:void(0)', 'javascript:{reply_onclick}', '_self', 'reply', '', '', 2147483647, 1, 0, 1, 2);

-- social sharing menu
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_social_sharing', 'system', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '_sys_menu_item_title_social_sharing_facebook', 'https://www.facebook.com/sharer/sharer.php?u={url_encoded}', '', '_blank', 'facebook', '', 2147483647, 1, 1, 1),
('sys_social_sharing', 'system', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '_sys_menu_item_title_social_sharing_googleplus', 'https://plus.google.com/share?url={url_encoded}', '', '_blank', 'google-plus', '', 2147483647, 1, 1, 2),
('sys_social_sharing', 'system', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '_sys_menu_item_title_social_sharing_twitter', 'https://twitter.com/share?url={url_encoded}', '', '_blank', 'twitter', '', 2147483647, 1, 1, 3),
('sys_social_sharing', 'system', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '_sys_menu_item_title_social_sharing_pinterest', 'http://pinterest.com/pin/create/button/?url={url_encoded}&media={img_url_encoded}&description={title_encoded}', '', '_blank', 'pinterest', '', 2147483647, 1, 1, 4);

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_grid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `source_type` enum('Array','Sql') NOT NULL,
  `source` text NOT NULL,
  `table` varchar(255) NOT NULL,
  `field_id` varchar(255) NOT NULL,
  `field_order` varchar(255) NOT NULL,
  `field_active` varchar(255) NOT NULL,
  `paginate_url` varchar(255) NOT NULL,
  `paginate_per_page` int(11) NOT NULL DEFAULT '10',
  `paginate_simple` varchar(255) DEFAULT NULL,
  `paginate_get_start` varchar(255) NOT NULL,
  `paginate_get_per_page` varchar(255) NOT NULL,
  `filter_fields` text NOT NULL,
  `filter_fields_translatable` text NOT NULL,
  `filter_mode` enum('like','fulltext','auto') NOT NULL DEFAULT 'auto',
  `sorting_fields` text NOT NULL,
  `sorting_fields_translatable` text NOT NULL,
  `visible_for_levels` int(11) NOT NULL DEFAULT '2147483647',
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- GRIDS: studio

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_lang_keys', 'Sql', 'SELECT `tlk`.`ID` AS `id`, `tlk`.`Key` AS `key`, `tlc`.`Name` AS `module`, `tls`.`String` AS `string` FROM `sys_localization_keys` AS `tlk` LEFT JOIN `sys_localization_categories` AS `tlc` ON `tlk`.`IDCategory`=`tlc`.`ID` LEFT JOIN `sys_localization_strings` AS `tls` ON `tlk`.`ID`=`tls`.`IDKey` WHERE `tls`.`IDLanguage`=\'%d\'', 'sys_localization_keys', 'id', '', '', '', 20, NULL, 'start', '', 'key,string', '', 'like', 'key,module,string', '', 'BxTemplStudioPolyglotKeys', ''),
('sys_studio_lang_etemplates', 'Sql', 'SELECT * FROM `sys_email_templates` WHERE 1 ', 'sys_email_templates', 'ID', '', '', '', 20, NULL, 'start', '', 'Module', 'NameSystem,Subject,Body', 'auto', 'Module', 'NameSystem', 'BxTemplStudioPolyglotEtemplates', ''),

('sys_studio_acl', 'Sql', 'SELECT * FROM `sys_acl_levels` WHERE 1 ', 'sys_acl_levels', 'ID', 'Order', 'Active', '', 100, NULL, 'start', '', 'Description', 'Name', 'auto', '', '', 'BxTemplStudioPermissionsLevels', ''),
('sys_studio_acl_actions', 'Sql', 'SELECT *, ''0'' AS `Active` FROM `sys_acl_actions` WHERE 1 ', 'sys_acl_actions', 'ID', '', 'Active', '', 20, NULL, 'start', '', 'Module,Name', 'Title,Desc', 'auto', 'Module,Name', 'Title,Desc', 'BxTemplStudioPermissionsActions', ''),

('sys_studio_nav_menus', 'Sql', 'SELECT `tm`.*, `tms`.`title` AS `set_title`, `tmt`.`title` AS `template_title` FROM `sys_objects_menu` AS `tm` LEFT JOIN `sys_menu_sets` AS `tms` ON `tm`.`set_name`=`tms`.`set_name` LEFT JOIN `sys_menu_templates` AS `tmt` ON `tm`.`template_id`=`tmt`.`id` WHERE 1 ', 'sys_objects_menu', 'id', '', 'active', '', 100, NULL, 'start', '', '', 'tm`.`title,tms`.`title,tmt`.`title', 'auto', '', '', 'BxTemplStudioNavigationMenus', ''),
('sys_studio_nav_sets', 'Sql', 'SELECT * FROM `sys_menu_sets` WHERE 1 ', 'sys_menu_sets', 'set_name', '', '', '', 100, NULL, 'start', '', '', 'title', 'auto', '', '', 'BxTemplStudioNavigationSets', ''),
('sys_studio_nav_items', 'Sql', 'SELECT * FROM `sys_menu_items` WHERE 1 ', 'sys_menu_items', 'id', 'order', 'active', '', 100, NULL, 'start_it', '', 'link', 'title_system', 'like', '', '', 'BxTemplStudioNavigationItems', ''),
('sys_studio_nav_import', 'Sql', 'SELECT * FROM `sys_menu_items` WHERE 1 AND `copyable`=\'1\' ', 'sys_menu_items', 'id', '', '', '', 5, NULL, 'start_im', '', 'link', 'title_system', 'like', '', '', 'BxTemplStudioNavigationImport', ''),

('sys_studio_forms', 'Sql', 'SELECT * FROM `sys_objects_form` WHERE 1 ', 'sys_objects_form', 'id', 'module,title', 'active', '', 100, NULL, 'start', '', 'module', 'title', 'like', 'module', 'title', 'BxTemplStudioFormsForms', ''),
('sys_studio_forms_displays', 'Sql', 'SELECT `td`.`id` AS `id`, `td`.`object` AS `object`, `td`.`display_name` AS `display_name`, `td`.`title` AS `display_title`, `td`.`module` AS `module`, `tf`.`title` AS `form_title` FROM `sys_form_displays` AS `td` LEFT JOIN `sys_objects_form` AS `tf` ON `td`.`object`=`tf`.`object` WHERE 1 ', 'sys_form_displays', 'id', 'module,object,display_title', '', '', 100, NULL, 'start', '', 'td`.`module', 'td`.`title', 'like', 'module', 'display_title,form_title', 'BxTemplStudioFormsDisplays', ''),
('sys_studio_forms_fields', 'Sql', 'SELECT `tdi`.`id` AS `id`, `ti`.`caption_system` AS `caption_system`, `ti`.`type` AS `type`, `ti`.`module` AS `module`, `tdi`.`visible_for_levels` AS `visible_for_levels`, `tdi`.`active` AS `active`, `ti`.`editable` AS `editable`, `ti`.`deletable` AS `deletable`, `tdi`.`order` AS `order` FROM `sys_form_display_inputs` AS `tdi` LEFT JOIN `sys_form_inputs` AS `ti` ON `tdi`.`input_name`=`ti`.`name` AND `ti`.`object`=? WHERE 1 AND `tdi`.`display_name`=?', 'sys_form_display_inputs', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'ti`.`type', 'ti`.`caption_system', 'like', '', '', 'BxTemplStudioFormsFields', ''),
('sys_studio_forms_pre_lists', 'Sql', 'SELECT * FROM `sys_form_pre_lists` WHERE 1 ', 'sys_form_pre_lists', 'id', '', '', '', 100, NULL, 'start', '', 'module,key', 'title', 'auto', 'module', 'title', 'BxTemplStudioFormsPreLists', ''),
('sys_studio_forms_pre_values', 'Sql', 'SELECT * FROM `sys_form_pre_values` WHERE 1 ', 'sys_form_pre_values', 'id', 'Order', '', '', 1000, NULL, 'start', '', 'Key,Value', 'LKey,LKey2', 'auto', '', '', 'BxTemplStudioFormsPreValues', ''),

('sys_studio_search_forms', 'Sql', 'SELECT * FROM `sys_objects_search_extended` WHERE 1 ', 'sys_objects_search_extended', 'id', 'module,title', 'active', '', 100, NULL, 'start', '', 'module', 'title', 'like', 'module', 'title', 'BxTemplStudioFormsSearchForms', ''),
('sys_studio_search_forms_fields', 'Sql', 'SELECT * FROM `sys_search_extended_fields` WHERE 1 AND `object`=?', 'sys_search_extended_fields', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'type', 'caption', 'like', '', '', 'BxTemplStudioFormsSearchFields', '');

CREATE TABLE IF NOT EXISTS `sys_grid_fields` (
  `object` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `width` varchar(16) NOT NULL,
  `translatable` tinyint(4) NOT NULL DEFAULT '0',
  `chars_limit` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `order` int(11) NOT NULL,
  UNIQUE KEY `object_name` (`object`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_lang_keys', 'checkbox', 'Select', '1%', 0, '', '', 1),
('sys_studio_lang_keys', 'key', '_adm_pgt_txt_key', '24%', 0, '20', '', 2),
('sys_studio_lang_keys', 'module', '_adm_pgt_txt_module', '15%', 0, '12', '', 3),
('sys_studio_lang_keys', 'string', '_adm_pgt_txt_text', '30%', 0, '30', '', 4),
('sys_studio_lang_keys', 'languages', '_adm_pgt_txt_languages', '10%', 0, '', '', 5),
('sys_studio_lang_keys', 'actions', '', '20%', 0, '', '', 6),

('sys_studio_lang_etemplates', 'NameSystem', '_adm_pgt_txt_etemplates_gl_name_system', '60%', 1, '58', '', 1),
('sys_studio_lang_etemplates', 'Module', '_adm_pgt_txt_etemplates_gl_module', '20%', 0, '18', '', 2),
('sys_studio_lang_etemplates', 'actions', '', '20%', 0, '', '', 3),

('sys_studio_acl', 'Order', '', '1%', 0, '', '', 1),
('sys_studio_acl', 'switcher', '_adm_prm_txt_enable', '5%', 0, '', '', 2),
('sys_studio_acl', 'Icon', '_adm_prm_txt_icon', '5%', 0, '', '', 3),
('sys_studio_acl', 'Name', '_adm_prm_txt_title', '29%', 1, '15', '', 4),
('sys_studio_acl', 'ActionsList', '_adm_prm_txt_actions', '10%', 0, '', '', 5),
('sys_studio_acl', 'QuotaSize', '_adm_prm_txt_storage', '10%', 0, '', '', 6),
('sys_studio_acl', 'QuotaMaxFileSize', '_adm_prm_txt_max_file_size', '10%', 0, '', '', 7),
('sys_studio_acl', 'QuotaNumber', '_adm_prm_txt_max_files', '10%', 0, '', '', 8),
('sys_studio_acl', 'actions', '', '20%', 0, '', '', 9),

('sys_studio_acl_actions', 'switcher', '_adm_prm_txt_enable', '10%', 0, '', '', 1),
('sys_studio_acl_actions', 'Title', '_adm_prm_txt_title', '25%', 1, '25', '', 2),
('sys_studio_acl_actions', 'Module', '_adm_prm_txt_module', '20%', 0, '18', '', 3),
('sys_studio_acl_actions', 'Desc', '_adm_prm_txt_description', '25%', 0, '25', '', 4),
('sys_studio_acl_actions', 'actions', '', '20%', 0, '', '', 5),

('sys_studio_nav_menus', 'switcher', '', '10%', 0, '', '', 1),
('sys_studio_nav_menus', 'title', '_adm_nav_txt_menus_gl_title', '17%', 0, '15', '', 2),
('sys_studio_nav_menus', 'set_title', '_adm_nav_txt_menus_gl_set_title', '17%', 1, '15', '', 3),
('sys_studio_nav_menus', 'items', '_adm_nav_txt_menus_gl_items', '10%', 0, '8', '', 4),
('sys_studio_nav_menus', 'template_title', '_adm_nav_txt_menus_gl_template_title', '17%', 1, '15', '', 5),
('sys_studio_nav_menus', 'module', '_adm_nav_txt_menus_gl_module', '12%', 0, '10', '', 6),
('sys_studio_nav_menus', 'actions', '', '17%', 0, '', '', 7),

('sys_studio_nav_sets', 'title', '_adm_nav_txt_sets_gl_title', '50%', 1, '48', '', 1),
('sys_studio_nav_sets', 'module', '_adm_nav_txt_sets_gl_module', '15%', 0, '13', '', 2),
('sys_studio_nav_sets', 'items', '_adm_nav_txt_sets_gl_items', '15%', 0, '13', '', 3),
('sys_studio_nav_sets', 'actions', '', '20%', 0, '', '', 4),

('sys_studio_nav_items', 'order', '', '1%', 0, '', '', 1),
('sys_studio_nav_items', 'switcher', '', '9%', 0, '', '', 2),
('sys_studio_nav_items', 'icon', '_adm_nav_txt_items_gl_icon', '5%', 0, '', '', 3),
('sys_studio_nav_items', 'title_system', '_adm_nav_txt_items_gl_title_system', '23%', 1, '23', '', 4),
('sys_studio_nav_items', 'link', '_adm_nav_txt_items_gl_link', '23%', 0, '23', '', 5),
('sys_studio_nav_items', 'module', '_adm_nav_txt_items_gl_module', '12%', 0, '12', '', 6),
('sys_studio_nav_items', 'visible_for_levels', '_adm_nav_txt_items_gl_visible', '10%', 0, '10', '', 7),
('sys_studio_nav_items', 'actions', '', '17%', 0, '', '', 8),

('sys_studio_nav_import', 'icon', '_adm_nav_txt_items_gl_icon', '10%', 0, '', '', 1),
('sys_studio_nav_import', 'title_system', '_adm_nav_txt_items_gl_title_system', '30%', 1, '28', '', 2),
('sys_studio_nav_import', 'link', '_adm_nav_txt_items_gl_link', '25%', 0, '23', '', 3),
('sys_studio_nav_import', 'module', '_adm_nav_txt_items_gl_module', '15%', 0, '13', '', 4),
('sys_studio_nav_import', 'actions', '', '20%', 0, '', '', 5),

('sys_studio_forms', 'switcher', '', '10%', 0, '', '', 1),
('sys_studio_forms', 'title', '_adm_form_txt_forms_gl_title', '40%', 1, '38', '', 2),
('sys_studio_forms', 'module', '_adm_form_txt_forms_gl_module', '15%', 0, '13', '', 3),
('sys_studio_forms', 'displays', '_adm_form_txt_forms_gl_displays', '15%', 0, '13', '', 4),
('sys_studio_forms', 'actions', '', '20%', 0, '', '', 5),

('sys_studio_forms_displays', 'display_title', '_adm_form_txt_displays_gl_title', '30%', 1, '48', '', 1),
('sys_studio_forms_displays', 'module', '_adm_form_txt_displays_gl_module', '13%', 0, '11', '', 2),
('sys_studio_forms_displays', 'form_title', '_adm_form_txt_displays_gl_form', '24%', 1, '22', '', 3),
('sys_studio_forms_displays', 'fields', '_adm_form_txt_displays_gl_fields', '13%', 0, '11', '', 4),
('sys_studio_forms_displays', 'actions', '', '20%', 0, '', '', 5),

('sys_studio_forms_fields', 'order', '', '1%', 0, '', '', 1),
('sys_studio_forms_fields', 'switcher', '', '9%', 0, '', '', 2),
('sys_studio_forms_fields', 'type', '_adm_form_txt_fields_type', '5%', 0, '', '', 3),
('sys_studio_forms_fields', 'caption_system', '_adm_form_txt_fields_caption_system', '40%', 1, '38', '', 4),
('sys_studio_forms_fields', 'module', '_adm_form_txt_fields_module', '15%', 0, '13', '', 5),
('sys_studio_forms_fields', 'visible_for_levels', '_adm_form_txt_fields_visible', '10%', 0, '10', '', 6),
('sys_studio_forms_fields', 'actions', '', '20%', 0, '', '', 7),

('sys_studio_forms_pre_lists', 'title', '_adm_form_txt_pre_lists_gl_title', '45%', 1, '50', '', 1),
('sys_studio_forms_pre_lists', 'values', '_adm_form_txt_pre_lists_gl_values', '12%', 0, '10', '', 2),
('sys_studio_forms_pre_lists', 'module', '_adm_form_txt_pre_lists_gl_module', '13%', 0, '11', '', 3),
('sys_studio_forms_pre_lists', 'use_for_sets', '_adm_form_txt_pre_lists_gl_use_for_sets', '10%', 0, '8', '', 4),
('sys_studio_forms_pre_lists', 'actions', '', '20%', 0, '', '', 5),
('sys_studio_forms_pre_values', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_forms_pre_values', 'order', '', '1%', 0, '', '', 2),
('sys_studio_forms_pre_values', 'LKey', '_adm_form_txt_pre_values_gl_lkey', '78%', 1, '75', '', 3),
('sys_studio_forms_pre_values', 'actions', '', '20%', 0, '', '', 4),

('sys_studio_search_forms', 'switcher', '', '10%', 0, '', '', 1),
('sys_studio_search_forms', 'title', '_adm_form_txt_search_forms_title', '40%', 1, '38', '', 2),
('sys_studio_search_forms', 'module', '_adm_form_txt_search_forms_module', '15%', 0, '13', '', 3),
('sys_studio_search_forms', 'fields', '_adm_form_txt_search_forms_fields', '15%', 0, '13', '', 4),
('sys_studio_search_forms', 'actions', '', '20%', 0, '', '', 5),

('sys_studio_search_forms_fields', 'order', '', '1%', 0, '', '', 1),
('sys_studio_search_forms_fields', 'switcher', '', '9%', 0, '', '', 2),
('sys_studio_search_forms_fields', 'type', '_adm_form_txt_search_forms_fields_type', '15%', 0, '', '', 3),
('sys_studio_search_forms_fields', 'caption', '_adm_form_txt_search_forms_fields_caption', '40%', 1, '38', '', 4),
('sys_studio_search_forms_fields', 'search_type', '_adm_form_txt_search_forms_fields_search_type', '15%', 0, '', '', 5),
('sys_studio_search_forms_fields', 'actions', '', '20%', 0, '', '', 6);


CREATE TABLE IF NOT EXISTS `sys_grid_actions` (
  `object` varchar(64) NOT NULL,
  `type` enum('bulk','single','independent') NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `icon_only` tinyint(4) NOT NULL DEFAULT '0',
  `confirm` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL,
  UNIQUE KEY `object_name_type` (`object`,`type`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_lang_keys', 'bulk', 'delete', '_adm_pgt_btn_delete', '', 1, 1),
('sys_studio_lang_keys', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_lang_keys', 'single', 'delete', '', 'remove', 1, 2),
('sys_studio_lang_keys', 'independent', 'add', '_adm_pgt_btn_add_new_key', '', 0, 1),

('sys_studio_lang_etemplates', 'single', 'edit', '', 'pencil', 0, 1),

('sys_studio_acl', 'independent', 'add', '_adm_prm_btn_add_level', '', 0, 1),
('sys_studio_acl', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_acl', 'single', 'delete', '', 'remove', 1, 2),

('sys_studio_acl_actions', 'single', 'options', '', 'cog', 0, 1),

('sys_studio_nav_menus', 'independent', 'add', '_adm_nav_btn_menus_create', '', 0, 1),
('sys_studio_nav_menus', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_nav_menus', 'single', 'delete', '', 'remove', 1, 2),

('sys_studio_nav_sets', 'independent', 'add', '_adm_nav_btn_sets_create', '', 0, 1),
('sys_studio_nav_sets', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_nav_sets', 'single', 'delete', '', 'remove', 1, 2),

('sys_studio_nav_items', 'independent', 'import', '_adm_nav_btn_items_gl_import', '', 0, 1),
('sys_studio_nav_items', 'independent', 'add', '_adm_nav_btn_items_gl_create', '', 0, 2),
('sys_studio_nav_items', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_nav_items', 'single', 'delete', '', 'remove', 1, 2),
('sys_studio_nav_items', 'single', 'show_to', '_adm_nav_btn_items_gl_visible', '', 0, 3),

('sys_studio_nav_import', 'single', 'import', '', 'plus', 0, 1),
('sys_studio_nav_import', 'bulk', 'done', '_adm_nav_btn_items_done', '', 0, 1),

('sys_studio_forms', 'single', 'edit', '', 'pencil', 0, 1),

('sys_studio_forms_displays', 'single', 'edit', '', 'pencil', 0, 1),

('sys_studio_forms_fields', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_forms_fields', 'single', 'delete', '', 'remove', 1, 2),
('sys_studio_forms_fields', 'single', 'show_to', '_adm_form_btn_fields_visible', '', 0, 3),
('sys_studio_forms_fields', 'independent', 'add', '_adm_form_btn_fields_create', '', 0, 1),

('sys_studio_forms_pre_lists', 'independent', 'add', '_adm_form_btn_pre_lists_create', '', 0, 1),
('sys_studio_forms_pre_lists', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_forms_pre_lists', 'single', 'delete', '', 'remove', 1, 2),

('sys_studio_forms_pre_values', 'independent', 'add', '_adm_form_btn_pre_values_create', '', 0, 1),
('sys_studio_forms_pre_values', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_forms_pre_values', 'single', 'delete', '', 'remove', 1, 2),
('sys_studio_forms_pre_values', 'bulk', 'delete', '_adm_form_btn_pre_values_delete', '', 1, 1),

('sys_studio_search_forms', 'single', 'edit', '', 'pencil', 0, 1),

('sys_studio_search_forms_fields', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_search_forms_fields', 'independent', 'reset', '_adm_form_btn_search_forms_fields_reset', '', 0, 1);


-- GRID: Storage managers
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_strg_files', 'Sql', 'SELECT * FROM `sys_files` WHERE 1 ', 'sys_files', 'id', '', '', '', 20, NULL, 'start', '', 'file_name,mime_type', '', 'auto', 'file_name,added', '', 'BxTemplStudioStoragesFiles', ''),
('sys_studio_strg_images', 'Sql', 'SELECT * FROM `sys_images` WHERE 1 ', 'sys_images', 'id', '', '', '', 20, NULL, 'start', '', 'file_name', '', 'auto', 'file_name,added', '', 'BxTemplStudioStoragesImages', '');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
-- Storages: Files
('sys_studio_strg_files', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_strg_files', 'file_name', '_adm_strg_txt_file_name', '24%', 0, '25', '', 2),
('sys_studio_strg_files', 'path', '_adm_strg_txt_path', '25%', 0, '', '', 3),
('sys_studio_strg_files', 'mime_type', '_adm_strg_txt_mime_type', '15%', 0, '15', '', 4),
('sys_studio_strg_files', 'added', '_adm_strg_txt_added', '10%', 0, '10', '', 5),
('sys_studio_strg_files', 'actions', '', '25%', 0, '', '', 6),

-- Storages: Images
('sys_studio_strg_images', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_strg_images', 'file_name', '_adm_strg_txt_file_name', '24%', 0, '25', '', 2),
('sys_studio_strg_images', 'path', '_adm_strg_txt_path', '25%', 0, '', '', 3),
('sys_studio_strg_images', 'mime_type', '_adm_strg_txt_mime_type', '15%', 0, '15', '', 4),
('sys_studio_strg_images', 'added', '_adm_strg_txt_added', '10%', 0, '10', '', 5),
('sys_studio_strg_images', 'actions', '', '25%', 0, '', '', 6);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
-- Storages: Files
('sys_studio_strg_files', 'bulk', 'delete', '_adm_strg_btn_delete', '', 0, 1, 1),
('sys_studio_strg_files', 'single', 'download', '_adm_strg_btn_download', 'download', 1, 0, 1),
('sys_studio_strg_files', 'single', 'delete', '_adm_strg_btn_delete', 'remove', 1, 1, 2),
('sys_studio_strg_files', 'independent', 'add', '_adm_strg_btn_add', '', 0, 0, 1),

-- Storages: Images
('sys_studio_strg_images', 'bulk', 'delete', '_adm_strg_btn_delete', '', 0, 1, 1),
('sys_studio_strg_images', 'single', 'download', '_adm_strg_btn_download', 'download', 1, 0, 1),
('sys_studio_strg_images', 'single', 'resize', '_adm_strg_btn_resize', 'compress', 1, 0, 2),
('sys_studio_strg_images', 'single', 'delete', '_adm_strg_btn_delete', 'remove', 1, 1, 3),
('sys_studio_strg_images', 'independent', 'add', '_adm_strg_btn_add', '', 0, 0, 1);


-- GRID: connections
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('sys_grid_connections', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridConnections', ''),
('sys_grid_connections_requests', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridConnectionsRequests', ''),
('sys_grid_subscriptions', 'Sql', 'SELECT `p`.`id`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridSubscriptions', ''),
('sys_grid_subscribed_me', 'Sql', 'SELECT `p`.`id`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridSubscribedMe', '');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('sys_grid_connections', 'name', '_sys_name', '40%', '', 1),
('sys_grid_connections', 'info', '', '30%', '', 2),
('sys_grid_connections', 'actions', '', '30%', '', 3),

('sys_grid_connections_requests', 'name', '_sys_name', '40%', '', 1),
('sys_grid_connections_requests', 'info', '', '30%', '', 2),
('sys_grid_connections_requests', 'actions', '', '30%', '', 3),

('sys_grid_subscriptions', 'name', '_sys_name', '70%', '', 1),
('sys_grid_subscriptions', 'actions', '', '30%', '', 2),

('sys_grid_subscribed_me', 'name', '_sys_name', '70%', '', 1),
('sys_grid_subscribed_me', 'actions', '', '30%', '', 2);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_grid_connections', 'single', 'accept', '_sys_accept', '', 0, 1),
('sys_grid_connections', 'single', 'delete', '', 'remove', 1, 2),
('sys_grid_connections', 'single', 'add_friend', '_sys_add_friend', 'plus', 0, 3),

('sys_grid_connections_requests', 'single', 'accept', '_sys_accept', '', 0, 1),
('sys_grid_connections_requests', 'single', 'delete', '', 'remove', 1, 2),

('sys_grid_subscriptions', 'single', 'subscribe', '_sys_subscribe', 'check', 0, 1),
('sys_grid_subscriptions', 'single', 'delete', '', 'remove', 1, 2),

('sys_grid_subscribed_me', 'single', 'subscribe', '_sys_subscribe', 'check', 0, 1);

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_connection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `table` varchar(255) NOT NULL,
  `type` enum('one-way','mutual') NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('sys_profiles_friends', 'sys_profiles_conn_friends', 'mutual', '', ''),
('sys_profiles_subscriptions', 'sys_profiles_conn_subscriptions', 'one-way', '', '');


CREATE TABLE IF NOT EXISTS `sys_profiles_conn_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_profiles_conn_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_transcoder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `storage_object` varchar(64) NOT NULL,
  `source_type` enum('Folder','Storage','Proxy') NOT NULL,
  `source_params` text NOT NULL,
  `private` enum('auto','yes','no') NOT NULL,
  `atime_tracking` int(11) NOT NULL,
  `atime_pruning` int(11) NOT NULL,
  `ts` int(11) NOT NULL DEFAULT '0',
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_image_resize', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', ''),
('sys_icon_apple', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', ''),
('sys_icon_facebook', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', ''),
('sys_icon_favicon', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '0', '0', '0', '', ''),
('sys_cover', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '0', '0', '0', '', ''),
('sys_cover_unit_profile', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '0', '0', '0', '', ''),
('sys_cover_preview', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', ''),
('sys_builder_page_preview', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', ''),
('sys_builder_page_embed', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', ''),
('sys_cmts_images_preview', 'sys_cmts_images_preview', 'Storage', 'a:1:{s:6:"object";s:15:"sys_cmts_images";}', 'no', '1', '2592000', '0', '', ''),
('sys_custom_images', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:17:"sys_images_custom";}', 'no', '1', '2592000', '0', '', '');


CREATE TABLE IF NOT EXISTS `sys_transcoder_images_files` (
  `transcoder_object` varchar(64) NOT NULL,
  `file_id` int(11) NOT NULL,
  `handler` varchar(255) NOT NULL,
  `atime` int(11) NOT NULL,
  `data` text NOT NULL,
  UNIQUE KEY `transcoder_object` (`transcoder_object`,`handler`),
  KEY `atime` (`atime`),
  KEY `file_id` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sys_transcoder_videos_files` (
  `transcoder_object` varchar(64) NOT NULL,
  `file_id` int(11) NOT NULL,
  `handler` varchar(255) NOT NULL,
  `atime` int(11) NOT NULL,
  UNIQUE KEY `transcoder_object` (`transcoder_object`,`handler`),
  KEY `atime` (`atime`),
  KEY `file_id` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sys_transcoder_filters` (
  `transcoder_object` varchar(64) NOT NULL,
  `filter` varchar(32) NOT NULL,
  `filter_params` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  KEY `transcoder_object` (`transcoder_object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_image_resize', 'ResizeVar', '', '0'),
('sys_icon_apple', 'Resize', 'a:4:{s:1:"w";s:3:"152";s:1:"h";s:3:"152";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"png";}', '0'),
('sys_icon_facebook', 'Resize', 'a:4:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"png";}', '0'),
('sys_icon_favicon', 'Resize', 'a:4:{s:1:"w";s:2:"16";s:1:"h";s:2:"16";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"png";}', '0'),
('sys_cover', 'Resize', 'a:3:{s:1:"w";s:4:"1920";s:1:"h";s:3:"720";s:10:"force_type";s:3:"png";}', '0'),
('sys_cover_unit_profile', 'Resize', 'a:3:{s:1:"w";s:3:"640";s:1:"h";s:3:"240";s:10:"force_type";s:3:"png";}', '0'),
('sys_cover_preview', 'Resize', 'a:3:{s:1:"w";s:3:"120";s:1:"h";s:2:"45";s:10:"force_type";s:3:"jpg";}', '0'),
('sys_builder_page_preview', 'Resize', 'a:4:{s:1:"w";s:3:"128";s:1:"h";s:3:"128";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('sys_builder_page_embed', 'ResizeVar', '', '0'),
('sys_cmts_images_preview', 'Resize', 'a:4:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('sys_custom_images', 'ResizeVar', '', '0');


CREATE TABLE IF NOT EXISTS `sys_transcoder_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transcoder_object` varchar(64) NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `file_url_source` varchar(255) NOT NULL,
  `file_id_source` varchar(255) NOT NULL,
  `file_url_result` varchar(255) NOT NULL,
  `file_ext_result` varchar(255) NOT NULL,
  `file_id_result` int(11) NOT NULL,
  `server` varchar(255) NOT NULL,
  `status` enum('pending','processing','complete','failed','delete') NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `log` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transcoder_object` (`transcoder_object`,`file_id_source`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `sys_transcoder_queue_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `sys_objects_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `title_system` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `module` varchar(32) NOT NULL,
  `cover` tinyint(4) NOT NULL DEFAULT '1',
  `cover_image` int(11) NOT NULL DEFAULT '0',
  `layout_id` int(11) NOT NULL,
  `visible_for_levels` int(11) NOT NULL DEFAULT '2147483647',
  `visible_for_levels_editable` tinyint(4) NOT NULL DEFAULT '1',
  `url` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_robots` varchar(255) NOT NULL,
  `cache_lifetime` int(11) NOT NULL DEFAULT '0',
  `cache_editable` tinyint(4) NOT NULL DEFAULT '1',
  `deletable` tinyint(1) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_home', 'home', '_sys_page_title_system_home', '_sys_page_title_home', 'system', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxTemplPageHome', ''),
('sys_about', 'about', '_sys_page_title_system_about', '_sys_page_title_about', 'system', 5, 2147483647, 1, 'page.php?i=about', '', '', '', 0, 1, 0, '', ''),
('sys_terms', 'terms', '_sys_page_title_system_terms', '_sys_page_title_terms', 'system', 5, 2147483647, 1, 'page.php?i=terms', '', '', '', 0, 1, 0, '', ''),
('sys_privacy', 'privacy', '_sys_page_title_system_privacy', '_sys_page_title_privacy', 'system', 5, 2147483647, 1, 'page.php?i=privacy', '', '', '', 0, 1, 0, '', ''),
('sys_dashboard', 'dashboard', '_sys_page_title_system_dashboard', '_sys_page_title_dashboard', 'system', 12, 2147483646, 1, 'page.php?i=dashboard', '', '', '', 0, 1, 0, 'BxTemplPageDashboard', ''),
('sys_create_account', 'create-account', '_sys_page_title_system_create_account', '_sys_page_title_create_account', 'system', 5, 2147483647, 1, 'page.php?i=create-account', '', '', '', 0, 1, 0, '', ''),
('sys_login', 'login', '_sys_page_title_system_login', '_sys_page_title_login', 'system', 5, 2147483647, 1, 'page.php?i=login', '', '', '', 0, 1, 0, '', ''),
('sys_forgot_password', 'forgot-password', '_sys_page_title_system_forgot_password', '_sys_page_title_forgot_password', 'system', 5, 2147483647, 1, 'page.php?i=forgot-password', '', '', '', 0, 1, 0, '', ''),
('sys_confirm_email', 'confirm-email', '_sys_page_title_system_confirm_email', '_sys_page_title_confirm_email', 'system', 5, 2147483647, 1, 'page.php?i=confirm-email', '', '', '', 0, 1, 0, '', ''),
('sys_account_settings_email', 'account-settings-email', '_sys_page_title_system_account_settings_email', '_sys_page_title_account_settings_email', 'system', 5, 2147483647, 1, 'member.php', '', '', '', 0, 1, 0, 'BxTemplPageAccount', ''),
('sys_account_settings_pwd', 'account-settings-password', '_sys_page_title_system_account_settings_pwd', '_sys_page_title_account_settings_pwd', 'system', 5, 2147483647, 1, 'page.php?i=account-settings-pwd', '', '', '', 0, 1, 0, 'BxTemplPageAccount', ''),
('sys_account_settings_info', 'account-settings-info', '_sys_page_title_system_account_settings_info', '_sys_page_title_account_settings_info', 'system', 5, 2147483647, 1, 'page.php?i=account-settings-info', '', '', '', 0, 1, 0, 'BxTemplPageAccount', ''),
('sys_account_settings_delete', 'account-settings-delete', '_sys_page_title_system_account_settings_delete', '_sys_page_title_account_settings_delete', 'system', 5, 2147483647, 1, 'page.php?i=account-settings-delete', '', '', '', 0, 1, 0, 'BxTemplPageAccount', ''),
('sys_account_profile_switcher', 'account-profile-switcher', '_sys_page_title_system_account_profile_switcher', '_sys_page_title_account_profile_switcher', 'system', 5, 2147483647, 1, 'page.php?i=account-profile-switcher', '', '', '', 0, 1, 0, 'BxTemplPageAccount', ''),
('sys_unsubscribe_notifications', 'unsubscribe-notifications', '_sys_page_title_system_unsubscribe_notifications', '_sys_page_title_unsubscribe_notifications', 'system', 5, 2147483647, 1, 'page.php?i=unsubscribe-notifications', '', '', '', 0, 1, 0, '', ''),
('sys_unsubscribe_news', 'unsubscribe-news', '_sys_page_title_system_unsubscribe_news', '_sys_page_title_unsubscribe_news', 'system', 5, 2147483647, 1, 'page.php?i=unsubscribe-news', '', '', '', 0, 1, 0, '', ''),
('sys_std_dashboard', '', '_sys_page_title_system_studio_dashboard', '_sys_page_title_studio_dashboard', 'system', 4, 2147483647, 1, '', '', '', '', 0, 1, 0, '', '');



CREATE TABLE IF NOT EXISTS `sys_pages_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `cells_number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES
(1, 'bar_left', 'layout_bar_left.png', '_sys_layout_bar_left', 'layout_bar_left.html', 2),
(2, 'bar_right', 'layout_bar_right.png', '_sys_layout_bar_right', 'layout_bar_right.html', 2),
(3, '3_columns', 'layout_3_columns.png', '_sys_layout_3_columns', 'layout_3_columns.html', 3),
(4, '2_columns', 'layout_2_columns.png', '_sys_layout_2_columns', 'layout_2_columns.html', 2),
(5, '1_column', 'layout_1_column.png', '_sys_layout_1_column', 'layout_1_column.html', 1),
(6, 'top_area_bar_left', 'layout_top_area_bar_left.png', '_sys_layout_top_area_bar_left', 'layout_top_area_bar_left.html', 3),
(7, 'top_area_bar_right', 'layout_top_area_bar_right.png', '_sys_layout_top_area_bar_right', 'layout_top_area_bar_right.html', 3),
(8, 'top_area_3_columns', 'layout_top_area_3_columns.png', '_sys_layout_top_area_3_columns', 'layout_top_area_3_columns.html', 4),
(9, 'top_area_2_columns', 'layout_top_area_2_columns.png', '_sys_layout_top_area_2_columns', 'layout_top_area_2_columns.html', 3),
(10, 'topbottom_area_2_columns', 'layout_topbottom_area_2_columns.png', '_sys_layout_topbottom_area_2_columns', 'layout_topbottom_area_2_columns.html', 4),
(11, 'bottom_area_2_columns', 'layout_bottom_area_2_columns.png', '_sys_layout_bottom_area_2_columns', 'layout_bottom_area_2_columns.html', 3),
(12, 'topbottom_area_bar_right', 'layout_topbottom_area_bar_right.png', '_sys_layout_topbottom_area_bar_right', 'layout_topbottom_area_bar_right.html', 4),
(13, 'topbottom_area_bar_left', 'layout_topbottom_area_bar_left.png', '_sys_layout_topbottom_area_bar_left', 'layout_topbottom_area_bar_left.html', 4);

CREATE TABLE IF NOT EXISTS `sys_pages_design_boxes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_pages_design_boxes` (`id`, `title`, `template`, `order`) VALUES
(0, '_sys_designbox_0', 'designbox_0.html', '2'),
(1, '_sys_designbox_1', 'designbox_1.html', '8'),
(2, '_sys_designbox_2', 'designbox_2.html', '1'),
(3, '_sys_designbox_3', 'designbox_3.html', '4'),
(4, '_sys_designbox_4', 'designbox_4.html', '6'),
(10, '_sys_designbox_10', 'designbox_10.html', '3'),
(11, '_sys_designbox_11', 'designbox_11.html', '9'),
(13, '_sys_designbox_13', 'designbox_13.html', '5'),
(14, '_sys_designbox_14', 'designbox_14.html', '7');



CREATE TABLE IF NOT EXISTS `sys_pages_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `cell_id` int(11) NOT NULL DEFAULT '1',
  `module` varchar(32) NOT NULL,
  `title_system` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `designbox_id` int(11) NOT NULL DEFAULT '11',
  `visible_for_levels` int(11) NOT NULL DEFAULT '2147483647',
  `hidden_on` varchar(255) NOT NULL DEFAULT '',
  `type` enum('raw','html','lang','image','rss','menu','service') NOT NULL DEFAULT 'raw',
  `content` text NOT NULL,
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  `copyable` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- skeleton blocks
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'skeletons', '_sys_block_type_raw', 11, 2147483647, 'raw', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_html', 11, 2147483647, 'html', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_rss', 11, 2147483647, 'rss', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_image', 11, 2147483647, 'image', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_lang', 11, 2147483647, 'lang', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_menu', 11, 2147483647, 'menu', '', 0, 1, 1, 0),
('', 0, 'skeletons', '_sys_block_type_service', 11, 2147483647, 'service', '', 0, 0, 1, 0);

-- content blocks
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES

('sys_home', 1, 'system', '', '_sys_page_block_title_homepage_menu', 3, 2147483647, 'menu', 'sys_homepage', 0, 1, 1, 0),

('sys_about', 1, 'system', '', '_sys_page_block_title_about', 11, 2147483647, 'lang', '_sys_page_lang_block_about', 0, 1, 1, 1),

('sys_terms', 1, 'system', '', '_sys_page_block_title_terms', 11, 2147483647, 'lang', '_sys_page_lang_block_terms', 0, 1, 1, 1),

('sys_privacy', 1, 'system', '', '_sys_page_block_title_privacy', 11, 2147483647, 'lang', '_sys_page_lang_block_privacy', 0, 1, 1, 1),

('sys_dashboard', 3, 'system', '', '_sys_page_block_title_profile_stats', 13, 2147483646, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"profile_stats";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1, 1),

('sys_dashboard', 3, 'system', '', '_sys_page_block_title_profile_membership', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"profile_membership";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 0, 0),

('sys_dashboard', 3, 'system', '', '_sys_page_block_title_manage_tools', 11, 192, 'menu', 'sys_account_dashboard_manage_tools', 0, 1, 1, 3),

('sys_dashboard', 2, 'system', '', '_sys_page_block_title_chart_growth', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_chart_growth";s:6:"params";a:0:{}s:5:"class";s:18:"TemplChartServices";}', 0, 1, 1, 4),

('sys_dashboard', 2, 'system', '', '_sys_page_block_title_chart_stats', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_chart_stats";s:6:"params";a:0:{}s:5:"class";s:18:"TemplChartServices";}', 0, 1, 1, 3),

('sys_create_account', 1, 'system', '', '_sys_page_block_title_create_account', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:19:\"create_account_form\";s:6:\"params\";a:0:{}s:5:\"class\";s:19:\"TemplServiceAccount\";}', 0, 1, 1, 1),

('sys_login', 1, 'system', '_sys_page_block_system_title_login', '_sys_page_block_title_login', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:10:\"login_form\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1, 1),

('sys_login', 0, 'system', '_sys_page_block_system_title_login_only', '_sys_page_block_title_login', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"login_form_only\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1, 0),

('sys_forgot_password', 1, 'system', '', '_sys_page_block_title_forgot_password', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"forgot_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_confirm_email', 1, 'system', '', '_sys_page_block_title_confirm_email', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"email_confirmation";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_account_settings_email', 1, 'system', '', '_sys_page_block_title_account_settings_email', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:22:"account_settings_email";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_account_settings_pwd', 1, 'system', '', '_sys_page_block_title_account_settings_pwd', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"account_settings_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_account_settings_info', 1, 'system', '', '_sys_page_block_title_account_settings_info', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"account_settings_info";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_account_settings_delete', 1, 'system', '', '_sys_page_block_title_account_settings_delete', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:28:"account_settings_del_account";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_account_profile_switcher', 1, 'system', '', '_sys_page_block_title_account_profile_create', 11, 2147483647, 'menu', 'sys_add_profile', 0, 1, 1, 1),

('sys_account_profile_switcher', 1, 'system', '', '_sys_page_block_title_account_profile_switcher', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:24:"account_profile_switcher";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1, 2),

('sys_unsubscribe_notifications', 1, 'system', '', '_sys_page_block_title_unsubscribe_notifications', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"unsubscribe_notifications";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

('sys_unsubscribe_news', 1, 'system', '', '_sys_page_block_title_unsubscribe_news', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"unsubscribe_news";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 1, 1, 1),

-- studio dashboard blocks
('sys_std_dashboard', 1, 'system', '', '_sys_page_block_title_std_dash_version', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_block_version";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', 0, 0, 1, 1),

('sys_std_dashboard', 1, 'system', '', '_sys_page_block_title_std_dash_space', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_block_space";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', 0, 0, 1, 2),

('sys_std_dashboard', 2, 'system', '', '_sys_page_block_title_std_dash_host_tools', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_block_host_tools";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', 0, 0, 1, 1),

('sys_std_dashboard', 2, 'system', '', '_sys_page_block_title_std_dash_cache', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_block_cache";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', 0, 0, 1, 2);

-- --------------------------------------------------------

CREATE TABLE `sys_objects_metatags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `table_keywords` varchar(255) NOT NULL,
  `table_locations` varchar(255) NOT NULL,
  `table_mentions` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts', 'sys_cmts_meta_keywords', '', '', '', '');

-- --------------------------------------------------------

CREATE TABLE `sys_objects_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `search_object` varchar(64) NOT NULL,
  `form_object` varchar(64) NOT NULL,
  `list_name` varchar(255) NOT NULL,
  `table` varchar(255) NOT NULL,
  `field` varchar(255) NOT NULL,
  `join` varchar(255) NOT NULL,
  `where` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `form_object` (`form_object`,`list_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_objects_live_updates`
--

CREATE TABLE `sys_objects_live_updates` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `frequency` tinyint(4) NOT NULL DEFAULT '1',
  `service_call` text NOT NULL default '', 
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('sys_payments_cart', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_live_updates_cart";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:4:"cart";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1),
('sys_payments_orders', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_live_updates_orders";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:6:"orders";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sys_objects_payments`
--

CREATE TABLE IF NOT EXISTS `sys_objects_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `uri` varchar(32) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_std_pages`
--
CREATE TABLE `sys_std_pages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `index` int(11) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `header` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_std_widgets`
--
CREATE TABLE `sys_std_widgets` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `page_id` varchar(255) NOT NULL default '',
  `module` varchar(32) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `click` text NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `cnt_notices` text NOT NULL default '',
  `cnt_actions` text NOT NULL default '',
  `bookmark` tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `widget-page` (`id`, `page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_std_pages_widgets`
--
CREATE TABLE `sys_std_pages_widgets` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `page_id` int(11) unsigned NOT NULL default '0',
  `widget_id` int(11) unsigned NOT NULL default '0',
  `order` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wid_pag` (`widget_id`, `page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
  

--
-- Dumping data for table `sys_std_pages`
--
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(0, 'home', '_adm_page_cpt_home', '_adm_page_cpt_home', '');
SET @iIdHome = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(4, 'dashboard', '_adm_page_cpt_dashboard', '_adm_page_cpt_dashboard', 'wi-dashboard.svg');
SET @iIdDashboard = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'settings', '_adm_page_cpt_settings', '_adm_page_cpt_settings', 'wi-settings.svg');
SET @iIdSettings = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'store', '_adm_page_cpt_store', '_adm_page_cpt_store', 'wi-store.svg');
SET @iIdStore = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'designer', '_adm_page_cpt_designer', '_adm_page_cpt_designer', 'wi-designer.svg');
SET @iIdDesigner = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'polyglot', '_adm_page_cpt_polyglot', '_adm_page_cpt_polyglot', 'wi-polyglot.svg');
SET @iIdPolyglot = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'builder_pages', '_adm_page_cpt_builder_pages', '_adm_page_cpt_builder_pages', 'wi-bld-pages.svg');
SET @iIdBuilderPages = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'builder_menus', '_adm_page_cpt_builder_menus', '_adm_page_cpt_builder_menus', 'wi-bld-navigation.svg');
SET @iIdBuilderMenus = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'builder_forms', '_adm_page_cpt_builder_forms', '_adm_page_cpt_builder_forms', 'wi-bld-forms.svg');
SET @iIdBuilderForms = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'builder_permissions', '_adm_page_cpt_builder_permissions', '_adm_page_cpt_builder_permissions', 'wi-bld-permissions.svg');
SET @iIdBuilderPermissions = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'storages', '_adm_page_cpt_storages', '_adm_page_cpt_storages', 'wi-storages.svg');
SET @iIdManagerStorages = LAST_INSERT_ID();

--
-- Dumping data for table `sys_std_widgets` and `sys_std_pages_widgets`
-- Home Page
--
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdSettings, 'system', '{url_studio}settings.php', '', 'wi-settings.svg', '_adm_wgt_cpt_settings', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdStore, 'system', '{url_studio}store.php', '', 'wi-store.svg', '_adm_wgt_cpt_store', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 2);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdDashboard, 'system', '{url_studio}dashboard.php', '', 'wi-dashboard.svg', '_adm_wgt_cpt_dashboard', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"get_widget_notices";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioDashboard";}', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 3);


--
-- Templates Page
--
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdDesigner, 'system', '{url_studio}designer.php', '', 'wi-designer.svg', '_adm_wgt_cpt_designer', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);


--
-- Languages Page
--
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdPolyglot, 'system', '{url_studio}polyglot.php', '', 'wi-polyglot.svg', '_adm_wgt_cpt_polyglot', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);


--
-- Builders Page
--
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBuilderPages, 'system', '{url_studio}builder_page.php', '', 'wi-bld-pages.svg', '_adm_wgt_cpt_builder_pages', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBuilderMenus, 'system', '{url_studio}builder_menu.php', '', 'wi-bld-navigation.svg', '_adm_wgt_cpt_builder_menus', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 2);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBuilderForms, 'system', '{url_studio}builder_forms.php', '', 'wi-bld-forms.svg', '_adm_wgt_cpt_builder_forms', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 3);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBuilderPermissions, 'system', '{url_studio}builder_permissions.php', '', 'wi-bld-permissions.svg', '_adm_wgt_cpt_builder_permissions', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 4);


--
-- Storages Page
--
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdManagerStorages, 'system', '{url_studio}storages.php', '', 'wi-storages.svg', '_adm_wgt_cpt_storages', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);
