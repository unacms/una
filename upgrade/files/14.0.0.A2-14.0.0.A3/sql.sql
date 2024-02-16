
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- Options

UPDATE `sys_options` SET `value` = '<html>\r\n    <head></head>\r\n    <body bgcolor="#fff" style="margin:0; padding:0;">\r\n        <div style="background-color:#fff;">\r\n            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>' WHERE `name` = 'site_email_html_template_header' AND `value` = '_adm_stg_cpt_option_site_email_html_template_header', '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #ccc; border-radius:3px; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>';

UPDATE `sys_options` SET `value` = '\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="color:#999; padding:0 20px 20px 20px; font:11px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-top:2px solid #eee; padding-top:10px;">{about_us}&nbsp;&nbsp;&nbsp;{unsubscribe}</div>\r\n                    </div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>' WHERE `name` = 'site_email_html_template_footer' AND `value` = '\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="margin-top:5px; text-align:center; color:#999; font:11px Helvetica, Arial, sans-serif;">{about_us}&nbsp;&nbsp;&nbsp;{unsubscribe}</div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_cf_unauthenticated', '_adm_stg_cpt_option_sys_cf_unauthenticated', '1', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:30:"get_options_cf_unauthenticated";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', '', '', 43);

 
-- Connections

INSERT IGNORE INTO `sys_objects_connection` (`object`, `table`, `profile_initiator`, `profile_content`, `type`, `override_class_name`, `override_class_file`) VALUES
('sys_profiles_bans', 'sys_profiles_conn_bans', 1, 1, 'one-way', 'BxDolBan', '');

CREATE TABLE IF NOT EXISTS `sys_profiles_conn_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL DEFAULT '',
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-A3' WHERE (`version` = '14.0.0.A2' OR `version` = '14.0.0-A2') AND `name` = 'system';

