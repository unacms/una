

ALTER TABLE  `sys_accounts` CHANGE  `receive_news`  `receive_news` TINYINT( 4 ) NOT NULL DEFAULT  '1';

-- Settings

DELETE FROM `sys_options` WHERE `name` = 'sys_iframely_api_key';
SET @iCategoryIdGeneral = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdGeneral, 'sys_iframely_api_key', '_adm_stg_cpt_option_sys_iframely_api_key', '', 'digit', '', '', '', 90);


DELETE FROM `sys_options` WHERE `name` IN('add_to_mobile_homepage', 'site_login_social_compact');
SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'add_to_mobile_homepage', '_adm_stg_cpt_option_add_to_mobile_homepage', 'on', 'checkbox', '', '', '', 8),
(@iCategoryIdSiteSettings, 'site_login_social_compact', '_adm_stg_cpt_option_site_login_social_compact', '', 'checkbox', '', '', '', 9);


DELETE FROM `sys_options` WHERE `name` IN('sys_account_default_profile_type');
SET @iCategoryIdSiteAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteAccount, 'sys_account_default_profile_type', '_adm_stg_cpt_option_sys_account_default_profile_type', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_profile_types";s:5:"class";s:20:"TemplServiceProfiles";}', '', '', 20);
UPDATE `sys_options` SET `order` = 21 WHERE `name` = 'sys_account_limit_profiles_number';


UPDATE `sys_options` SET `value` = '' WHERE `name` = 'enable_notification_pruning';


-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `Name` = 'chart view';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_convos' AND `Name` = 'chart view';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'chart view', NULL, '_sys_acl_action_chart_view', '_sys_acl_action_chart_view_desc', 0, 3);
SET @iIdActionChartView = LAST_INSERT_ID();

SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iAdministrator, @iIdActionChartView);


-- Stats

DROP TABLE IF EXISTS `sys_stat_site`;

CREATE TABLE IF NOT EXISTS `sys_statistics` (
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

TRUNCATE TABLE `sys_statistics`;

INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES
('system', 'sys_accounts', '_sys_accounts', '', 'user', 'SELECT COUNT(*) FROM `sys_accounts` WHERE 1', 1);


-- Extended search

CREATE TABLE IF NOT EXISTS `sys_objects_search_extended` (
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

CREATE TABLE IF NOT EXISTS `sys_search_extended_fields` (
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


-- Alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_connections' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_connections', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:26:"alert_response_connections";s:5:"class";s:23:"TemplServiceConnections";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_profiles_friends', 'connection_added', @iHandler),
('sys_profiles_friends', 'connection_removed', @iHandler);


-- Charts

CREATE TABLE IF NOT EXISTS `sys_objects_chart` (
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

TRUNCATE TABLE `sys_objects_chart`;

INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('sys_accounts_growth', '_sys_chart_accounts_growth', 'sys_accounts', 'added', '', '', '', 1, 1, 'BxDolChartGrowth', ''),
('sys_accounts_growth_speed', '_sys_chart_accounts_growth_speed', 'sys_accounts', 'added', '', '', '', 1, 1, 'BxDolChartGrowthSpeed', '');

CREATE TABLE IF NOT EXISTS `sys_objects_content_info` (
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

CREATE TABLE IF NOT EXISTS `sys_content_info_grids` (
  `id` int(11) NOT NULL auto_increment,
  `object` varchar(64) NOT NULL,
  `grid_object` varchar(64) NOT NULL,
  `grid_field_id` varchar(64) NOT NULL,
  `condition` text NOT NULL default '',
  `selection` varchar(256) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `grid_object` (`grid_object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- Forms

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_account' AND `name` = 'delete_content';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_account', 'system', 'delete_content', '0', '', 0, 'hidden', '_sys_form_login_input_caption_system_delete_content', '', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_account_create';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_account_create', 'name', 2147483647, 1, 1),
('sys_account_create', 'email', 2147483647, 1, 2),
('sys_account_create', 'password', 2147483647, 1, 3),
('sys_account_create', 'receive_news', 2147483647, 1, 4),
('sys_account_create', 'do_submit', 2147483647, 1, 5),
('sys_account_create', 'agreement', 2147483647, 1, 7);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_account_settings_del_account' AND `input_name` = 'delete_content';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_account_settings_del_account', 'delete_content', 2147483647, 1, 0);

-- Pre-values

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'Language';
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


-- Menu templates

DELETE FROM `sys_menu_templates` WHERE `template` = 'menu_floating_blocks_wide.html';

SET @iMax = (SELECT MAX(`id`)+1 FROM `sys_menu_templates`);
UPDATE `sys_menu_templates` SET `id` = @iMax WHERE `id` = 19;

INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(19, 'menu_floating_blocks_wide.html', '_sys_menu_template_title_floating_blocks_wide', 0);


-- Menu objects

DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_account', 'sys_add_profile_vertical', 'sys_account_dashboard');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_add_profile_vertical', '_sys_menu_title_add_profile_vertical', 'sys_add_profile_links', 'system', 6, 0, 1, 'BxTemplMenuProfileAdd', ''),
('sys_account_dashboard', '_sys_menu_title_account_dashboard', 'sys_account_dashboard', 'system', 8, 0, 1, 'BxTemplMenuAccountDashboard', '');

UPDATE `sys_objects_menu` SET `template_id` = 19 WHERE `object` = 'sys_account_notifications';


-- Menu sets

DELETE FROM `sys_menu_sets` WHERE `set_name` IN('sys_account_links', 'sys_account_dashboard');
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_account_dashboard', 'system', '_sys_menu_set_title_account_dashboard', 0);


-- Menu items

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_footer' AND `name` = 'powered_by';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_footer', 'system', 'powered_by', '_sys_menu_item_title_system_powered_by', '', 'https://una.io', '', '_blank', 'una.png', '', 2147483647, 1, 1, 9999);


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_links';


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `name` IN('cart', 'subscriptions', 'orders', 'dashboard', 'profile', 'account-settings', 'add-content', 'studio', 'notifications-cart', 'notifications-orders', 'logout');

SET @iOrderMin = (SELECT MIN(`order`) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
UPDATE `sys_menu_items` SET `order` = `order` + IF(@iOrderMin < 8, 8 - @iOrderMin, 0) WHERE `set_name` = 'sys_account_notifications';

INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'dashboard', '_sys_menu_item_title_system_dashboard', '_sys_menu_item_title_dashboard', 'page.php?i=dashboard', '', '', 'dashboard', '', '', 2147483646, 1, 1, 1),
('sys_account_notifications', 'system', 'profile', '_sys_menu_item_title_system_profile', '_sys_menu_item_title_profile', '{member_url}', '', '', 'user', '', '', 2147483646, 1, 1, 2),
('sys_account_notifications', 'system', 'account-settings', '_sys_menu_item_title_system_account_settings', '_sys_menu_item_title_account_settings', 'page.php?i=account-settings-email', '', '', 'cog', '', '', 2147483646, 1, 1, 3),
('sys_account_notifications', 'system', 'add-content', '_sys_menu_item_title_system_add_content', '_sys_menu_item_title_add_content', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_add_content\', $(\'bx-menu-toolbar-item-add-content a\').get(0), \'site\');', '', 'plus', '', '', 2147483646, 1, 1, 4),
('sys_account_notifications', 'system', 'studio', '_sys_menu_item_title_system_studio', '_sys_menu_item_title_studio', '{studio_url}', '', '', 'wrench', '', '', 2147483646, 1, 0, 5),
('sys_account_notifications', 'system', 'notifications-cart', '_sys_menu_item_title_system_cart', '_sys_menu_item_title_cart', 'cart.php', '', '', 'cart-plus col-red3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_cart_items_count";s:6:"params";a:0:{}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 1, 1, 6),
('sys_account_notifications', 'system', 'notifications-orders', '_sys_menu_item_title_system_orders', '_sys_menu_item_title_orders', 'orders.php', '', '', 'cart-arrow-down col-green3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_orders_count";s:6:"params";a:1:{i:0;s:3:"new";}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 0, 1, 7),
('sys_account_notifications', 'system', 'logout', '_sys_menu_item_title_system_logout', '_sys_menu_item_title_logout', 'logout.php', '', '', 'sign-out', '', '', 2147483646, 1, 1, 9999);


UPDATE `sys_menu_items` SET `order` = 9999 WHERE `set_name` = 'sys_account_settings' AND `name` = 'account-settings-more';


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_dashboard', 'system', 'dashboard', '_sys_menu_item_title_system_account_dashboard', '_sys_menu_item_title_account_dashboard', 'page.php?i=dashboard', '', '', 'dashboard', '', '', 2147483646, 1, 1, 1),
('sys_account_dashboard', 'system', 'dashboard-subscriptions', '_sys_menu_item_title_system_subscriptions', '_sys_menu_item_title_subscriptions', 'subscriptions.php', '', '', 'money col-blue3', '', '', 2147483646, 1, 1, 2),
('sys_account_dashboard', 'system', 'dashboard-orders', '_sys_menu_item_title_system_orders', '_sys_menu_item_title_orders', 'orders.php', '', '', 'cart-arrow-down col-green3', '', '', 2147483646, 1, 1, 3);


UPDATE `sys_menu_items` SET `visible_for_levels` = 2147483647 WHERE `set_name` = 'sys_social_sharing' AND `visible_for_levels` = 2147483646 AND `name` IN('social-sharing-facebook', 'social-sharing-googleplus', 'social-sharing-twitter', 'social-sharing-pinterest');


-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_studio_search_forms', 'sys_studio_search_forms_fields');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_search_forms', 'Sql', 'SELECT * FROM `sys_objects_search_extended` WHERE 1 ', 'sys_objects_search_extended', 'id', 'module,title', 'active', '', 100, NULL, 'start', '', 'module', 'title', 'like', 'module', 'title', 'BxTemplStudioFormsSearchForms', ''),
('sys_studio_search_forms_fields', 'Sql', 'SELECT * FROM `sys_search_extended_fields` WHERE 1 AND `object`=?', 'sys_search_extended_fields', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'type', 'caption', 'like', '', '', 'BxTemplStudioFormsSearchFields', '');


DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_studio_search_forms', 'sys_studio_search_forms_fields');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
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


DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_studio_search_forms', 'sys_studio_search_forms_fields');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_search_forms', 'single', 'edit', '', 'pencil', 0, 1),

('sys_studio_search_forms_fields', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_search_forms_fields', 'independent', 'reset', '_adm_form_btn_search_forms_fields_reset', '', 0, 1);



DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_grid_subscriptions', 'sys_grid_subscribed_me');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('sys_grid_subscriptions', 'Sql', 'SELECT `p`.`id`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridSubscriptions', ''),
('sys_grid_subscribed_me', 'Sql', 'SELECT `p`.`id`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridSubscribedMe', '');

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_grid_subscriptions', 'sys_grid_subscribed_me');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('sys_grid_subscriptions', 'name', '_sys_name', '70%', '', 1),
('sys_grid_subscriptions', 'actions', '', '30%', '', 2),
('sys_grid_subscribed_me', 'name', '_sys_name', '70%', '', 1),
('sys_grid_subscribed_me', 'actions', '', '30%', '', 2);

DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_grid_subscriptions', 'sys_grid_subscribed_me');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_grid_subscriptions', 'single', 'subscribe', '_sys_subscribe', 'check', 0, 1),
('sys_grid_subscriptions', 'single', 'delete', '', 'remove', 1, 2),
('sys_grid_subscribed_me', 'single', 'subscribe', '_sys_subscribe', 'check', 0, 1);


-- Pages blocks

UPDATE `sys_objects_page` SET `layout_id` = 12 WHERE `object` = 'sys_dashboard';

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `title` IN('_sys_page_block_title_profile_stats', '_sys_page_block_title_profile_membership', '_sys_page_block_title_manage_tools', '_sys_page_block_title_chart_growth', '_sys_page_block_title_chart_stats');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard', 3, 'system', '', '_sys_page_block_title_profile_stats', 13, 2147483646, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"profile_stats";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1, 1),
('sys_dashboard', 3, 'system', '', '_sys_page_block_title_profile_membership', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"profile_membership";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 0, 0),
('sys_dashboard', 3, 'system', '', '_sys_page_block_title_manage_tools', 11, 192, 'menu', 'sys_account_dashboard_manage_tools', 0, 1, 1, 3),
('sys_dashboard', 2, 'system', '', '_sys_page_block_title_chart_growth', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_chart_growth";s:6:"params";a:0:{}s:5:"class";s:18:"TemplChartServices";}', 0, 1, 1, 4),
('sys_dashboard', 2, 'system', '', '_sys_page_block_title_chart_stats', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_chart_stats";s:6:"params";a:0:{}s:5:"class";s:18:"TemplChartServices";}', 0, 1, 1, 3);


 -- Live updates

DELETE FROM `sys_objects_live_updates` WHERE `name` IN('sys_payments_cart', 'sys_payments_orders');
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('sys_payments_cart', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_live_updates_cart";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:4:"cart";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1),
('sys_payments_orders', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_live_updates_orders";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:6:"orders";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1);




-- last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC1' WHERE (`version` = '9.0.0.B5' OR `version` = '9.0.0-B5') AND `name` = 'system';

