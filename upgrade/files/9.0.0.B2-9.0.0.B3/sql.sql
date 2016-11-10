

DELETE FROM `sys_options` WHERE `name` IN('site_email_html_template_header', 'site_email_html_template_footer', 'permalinks_storage');

SET @iCategoryIdSystem = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSystem, 'site_email_html_template_header', '_adm_stg_cpt_option_site_email_html_template_header', '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #999; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>', 'text', '', '', '', 1),
(@iCategoryIdSystem, 'site_email_html_template_footer', '_adm_stg_cpt_option_site_email_html_template_footer', '\r\n                        <div style="margin-top:5px; color:#999; font:11px Helvetica, Arial, sans-serif;">{unsubscribe}</div>\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="margin-top:5px; text-align:center; color:#999; font:11px Helvetica, Arial, sans-serif;">{about_us}</div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>', 'text', '', '', '', 2);

UPDATE `sys_options` SET `extra` = 'File,Memcache,APC,XCache' WHERE `name` IN('sys_db_cache_engine', 'sys_page_cache_engine', 'sys_pb_cache_engine');
UPDATE `sys_options` SET `extra` = 'FileHtml,Memcache,APC,XCache' WHERE `name` IN('sys_template_cache_engine');

SET @iCategoryIdPermalinks = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'permalinks');
INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdPermalinks, 'permalinks_storage', '_adm_stg_cpt_option_permalinks_storage', 'on', 'checkbox', '', '', '', 3);



DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `sys_acl_actions`.`Name` IN('favorite', 'favorite_view');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` IN('favorite', 'favorite_view');

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'favorite', NULL, '_sys_acl_action_favorite', '', 0, 0);
SET @iIdActionFavorite = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'favorite_view', NULL, '_sys_acl_action_favorite_view', '', 0, 0);
SET @iIdActionFavoriteView = LAST_INSERT_ID();

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
(@iPremium, @iIdActionFavoriteView);



DELETE FROM `sys_permalinks` WHERE `standard` = 'storage.php?o=';
INSERT INTO `sys_permalinks` (`standard`, `permalink`, `check`, `compare_by_prefix`) VALUES
('storage.php?o=', 's/', 'permalinks_storage', 1);



CREATE TABLE IF NOT EXISTS `sys_objects_favorite` (
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



DELETE FROM `sys_objects_uploader` WHERE `object` = 'sys_std_crop_cover';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_std_crop_cover', 1, 'BxTemplStudioUploaderCropCover', '');



DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `name` = 'subscriptions';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'subscriptions', '_sys_menu_item_title_system_subscriptions', '_sys_menu_item_title_subscriptions', 'subscriptions.php', '', '', 'money col-blue3', '', '', 2147483646, 1, 1, 2);

UPDATE `sys_menu_items` SET `order` = 3 WHERE `set_name` = 'sys_account_notifications' AND `name` = 'orders' AND `order` = 2;



DELETE FROM `sys_objects_transcoder` WHERE `object` = 'sys_cover_preview' AND `storage_object` = 'sys_images_resized';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_cover_preview', 'sys_images_resized', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'sys_cover_preview' AND `filter` = 'Resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_cover_preview', 'Resize', 'a:3:{s:1:"w";s:3:"120";s:1:"h";s:2:"45";s:10:"force_type";s:3:"jpg";}', '0');



UPDATE `sys_std_pages` SET `icon` = 'wi-dashboard.svg' WHERE `name` = 'dashboard';
UPDATE `sys_std_pages` SET `icon` = 'wi-settings.svg' WHERE `name` = 'settings';
UPDATE `sys_std_pages` SET `icon` = 'wi-store.svg' WHERE `name` = 'store';
UPDATE `sys_std_pages` SET `icon` = 'wi-designer.svg' WHERE `name` = 'designer';
UPDATE `sys_std_pages` SET `icon` = 'wi-polyglot.svg' WHERE `name` = 'polyglot';
UPDATE `sys_std_pages` SET `icon` = 'wi-bld-pages.svg' WHERE `name` = 'builder_pages';
UPDATE `sys_std_pages` SET `icon` = 'wi-bld-navigation.svg' WHERE `name` = 'builder_menus';
UPDATE `sys_std_pages` SET `icon` = 'wi-bld-forms.svg' WHERE `name` = 'builder_forms';
UPDATE `sys_std_pages` SET `icon` = 'wi-bld-permissions.svg' WHERE `name` = 'builder_permissions';
UPDATE `sys_std_pages` SET `icon` = 'wi-storages.svg' WHERE `name` = 'storages';

UPDATE `sys_std_widgets` SET `icon` = 'wi-settings.svg' WHERE `caption` = '_adm_wgt_cpt_settings' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-store.svg' WHERE `caption` = '_adm_wgt_cpt_store' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-dashboard.svg' WHERE `caption` = '_adm_wgt_cpt_dashboard' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-designer.svg' WHERE `caption` = '_adm_wgt_cpt_designer' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-polyglot.svg' WHERE `caption` = '_adm_wgt_cpt_polyglot' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-bld-pages.svg' WHERE `caption` = '_adm_wgt_cpt_builder_pages' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-bld-navigation.svg' WHERE `caption` = '_adm_wgt_cpt_builder_menus' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-bld-forms.svg' WHERE `caption` = '_adm_wgt_cpt_builder_forms' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-bld-permissions.svg' WHERE `caption` = '_adm_wgt_cpt_builder_permissions' AND `module` = 'system';
UPDATE `sys_std_widgets` SET `icon` = 'wi-storages.svg' WHERE `caption` = '_adm_wgt_cpt_storages' AND `module` = 'system';



UPDATE `sys_modules` SET `vendor` = 'UNA, Inc' WHERE `name` = 'system';


-- last step is to update current version


UPDATE `sys_modules` SET `version` = '9.0.0-B3' WHERE (`version` = '9.0.0.B2' OR `version` = '9.0.0-B2') AND `name` = 'system';

