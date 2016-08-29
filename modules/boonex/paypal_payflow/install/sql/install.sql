SET @sModuleName = 'bx_payflow';

--
-- Table structure for table `[db_prefix]providers`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(128) NOT NULL default '',
  `description` text NOT NULL default '',
  `option_prefix` varchar(32) NOT NULL default '',
  `for_visitor` tinyint(4) NOT NULL default '0',
  `class_name` varchar(128) NOT NULL default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]providers_options`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]providers_options` (
  `id` int(11) NOT NULL auto_increment,
  `provider_id` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default 'text',
  `caption` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `extra` varchar(255) NOT NULL default '',
  `check_type` varchar(64) NOT NULL default '',
  `check_params` varchar(128) NOT NULL default '',
  `check_error` varchar(128) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]user_values`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]user_values` (
  `user_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',  
  `value` varchar(255) NOT NULL default '',
  UNIQUE KEY `value`(`user_id`, `option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]cart`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]cart` (
  `client_id` int(11) NOT NULL default '0',
  `items` text NOT NULL default '',
  PRIMARY KEY(`client_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]transactions`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]transactions` (
  `id` int(11) NOT NULL auto_increment,
  `pending_id` int(11) NOT NULL default '0',
  `order_id` varchar(16) NOT NULL default '',
  `client_id` int(11) NOT NULL default '0',
  `seller_id` int(11) NOT NULL default '0',
  `module_id` int(11) NOT NULL default '0',  
  `item_id` int(11) NOT NULL default '0',
  `item_count` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `reported` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]transactions_pending`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]transactions_pending` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `seller_id` int(11) NOT NULL default '0',
  `items` text NOT NULL default '',
  `amount` float NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `order_ref` varchar(32) NOT NULL default '',
  `order_profile` varchar(32) NOT NULL default '',
  `error_code` varchar(16) NOT NULL default '',
  `error_msg` varchar(255) NOT NULL default '',
  `provider` varchar(32) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `processed` tinyint(4) NOT NULL default '0',
  `reported` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `[db_prefix]modules`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]modules` (
  `id` int(11) NOT NULL auto_increment,
  `uri` varchar(64) NOT NULL default '',  
  PRIMARY KEY(`id`),
  UNIQUE KEY `uri`(`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- options
SET @iCategoryOrder = (SELECT MAX(`menu_order`) FROM `sys_options_cats`) + 1;
INSERT INTO `sys_options_cats` (`name` , `menu_order` ) VALUES ('PayPal PayFlow Pro', @iCategoryOrder);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('permalinks_module_payflow', 'on', 26, 'Enable friendly PayPal PayFlow Pro permalink', 'checkbox', '', '', 0, ''),
('bx_pfw_default_currency_code', 'USD', @iCategoryId, 'Currency code', 'select', 'return strlen($arg0) > 0;', 'Cannot be empty.', 1, 'AUD,CAD,EUR,GBP,USD,YEN'),
('bx_pfw_default_currency_sign', '&#36;', @iCategoryId, 'Currency sign', 'digit', 'return strlen($arg0) > 0;', 'Cannot be empty.', 2, ''),
('bx_pfw_site_admin', '', @iCategoryId, 'Site administrator', 'select', '', '', 3, 'PHP:return BxDolService::call(''payment'', ''get_admins'');');


-- permalinks
INSERT INTO `sys_permalinks`(`standard`, `permalink`, `check`) VALUES
('modules/?r=payflow/', 'm/payflow/', 'permalinks_module_payflow');


-- pages and blocks
SET @iPCPOrder = (SELECT MAX(`Order`) FROM `sys_page_compose_pages`);
INSERT INTO `sys_page_compose_pages`(`Name`, `Title`, `Order`) VALUES 
('bx_pfw_cart', 'Payflow Shopping Cart', @iPCPOrder+1),
('bx_pfw_history', 'Payflow Cart History', @iPCPOrder+2),
('bx_pfw_orders', 'Payflow Order Administration', @iPCPOrder+3),
('bx_pfw_details', 'Payflow Payment Settings', @iPCPOrder+4);

INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
('bx_pfw_cart', '1140px', 'Site Cart', '_bx_pfw_bcpt_cart_featured', 2, 0, 'Featured', '', 1, 50, 'memb', 0),
('bx_pfw_cart', '1140px', 'Members Cart', '_bx_pfw_bcpt_cart_common', 3, 0, 'Common', '', 1, 50, 'memb', 0),
('bx_pfw_history', '1140px', 'History', '_bx_pfw_bcpt_cart_history', 2, 0, 'History', '', 1, 100, 'memb', 0),
('bx_pfw_orders', '1140px', 'Orders', '_bx_pfw_bcpt_processed_orders', 2, 0, 'Orders', '', 1, 100, 'memb', 0),
('bx_pfw_details', '1140px', 'Payment Details', '_bx_pfw_bcpt_details', 2, 0, 'Details', '', 1, 100, 'memb', 0);


-- menus
SET @iOrder = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id`='2');
INSERT INTO `sys_menu_admin`(`parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(2, @sModuleName, '_bx_pfw_admin_menu_sitem', '{siteUrl}modules/?r=payflow/admin/', 'For managing PayPal PayFlow Pro', 'credit-card', '', '', @iOrder+1);


-- alert
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `eval`) VALUES 
(@sModuleName, '', '', 'BxDolService::call(\'payflow\', \'response\', array($this));');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'join', @iHandlerId),
('profile', 'delete', @iHandlerId);


-- email templates
INSERT INTO `sys_email_templates`(`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES
('bx_pfw_paid_need_join', 'Payment was accepted', '<bx_include_auto:_email_header.html />\r\n\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>\r\nYour payment was accepted. If you did not fill in the join form yet, then you may do it using the following link. \r\n</p>\r\n\r\n<p>\r\n<a href="<JoinLink>">Join Now</a>\r\n</p>\r\n\r\n<bx_include_auto:_email_footer.html />', 'PayPal PayFlow Pro: Paid and need to join', 0);


-- chart
SET @iMaxOrderCharts = (SELECT MAX(`order`)+1 FROM `sys_objects_charts`);
INSERT INTO `sys_objects_charts` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `query`, `active`, `order`) VALUES
(@sModuleName, '_bx_pfw_chart', 'bx_pfw_transactions', 'date', '', '', 1, @iMaxOrderCharts);


-- payments
INSERT INTO `sys_objects_payments` (`object`, `title`, `uri`) VALUES
(@sModuleName, '_sys_module_payflow', 'payflow');


--
-- Hosted Checkout Pages with PayPal PayFlow Pro
--
INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `class_name`) VALUES
('hosted_checkout', '_bx_pfw_cpt_hosted_checkout', '_bx_pfw_dsc_hosted_checkout', 'hc_', 0, 'BxPfwHostedCheckout');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'hc_active', 'checkbox', '_bx_pfw_po_active_cpt', '_bx_pfw_po_active_dsc', '', '', '', '', 1),
(@iProviderId, 'hc_mode', 'select', '_bx_pfw_po_mode_cpt', '_bx_pfw_po_mode_dsc', '1|_bx_pfw_po_mode_live,2|_bx_pfw_po_mode_test', '', '', '', 2),
(@iProviderId, 'hc_partner', 'text', '_bx_pfw_po_partner_cpt', '_bx_pfw_po_partner_dsc', '', '', '', '', 3),
(@iProviderId, 'hc_vendor', 'text', '_bx_pfw_po_vendor_cpt', '_bx_pfw_po_vendor_dsc', '', '', '', '', 4),
(@iProviderId, 'hc_user', 'text', '_bx_pfw_po_user_cpt', '_bx_pfw_po_user_dsc', '', '', '', '', 5),
(@iProviderId, 'hc_password', 'text', '_bx_pfw_po_password_cpt', '_bx_pfw_po_password_dsc', '', '', '', '', 6);

--
-- Express Checkout with PayPal PayFlow Pro
--
INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `class_name`) VALUES
('express_checkout', '_bx_pfw_cpt_express_checkout', '_bx_pfw_dsc_express_checkout', 'ec_', 1, 'BxPfwExpressCheckout');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'ec_active', 'checkbox', '_bx_pfw_po_active_cpt', '_bx_pfw_po_active_dsc', '', '', '', '', 1),
(@iProviderId, 'ec_mode', 'select', '_bx_pfw_po_mode_cpt', '_bx_pfw_po_mode_dsc', '1|_bx_pfw_po_mode_live,2|_bx_pfw_po_mode_test', '', '', '', 2),
(@iProviderId, 'ec_partner', 'text', '_bx_pfw_po_partner_cpt', '_bx_pfw_po_partner_dsc', '', '', '', '', 3),
(@iProviderId, 'ec_vendor', 'text', '_bx_pfw_po_vendor_cpt', '_bx_pfw_po_vendor_dsc', '', '', '', '', 4),
(@iProviderId, 'ec_user', 'text', '_bx_pfw_po_user_cpt', '_bx_pfw_po_user_dsc', '', '', '', '', 5),
(@iProviderId, 'ec_password', 'text', '_bx_pfw_po_password_cpt', '_bx_pfw_po_password_dsc', '', '', '', '', 6);

--
-- Recurring Billing with PayPal PayFlow Pro
--
INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `class_name`) VALUES
('recurring_billing', '_bx_pfw_cpt_recurring_billing', '_bx_pfw_dsc_recurring_billing', 'rb_', 1, 'BxPfwRecurringBilling');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'rb_active', 'checkbox', '_bx_pfw_po_active_cpt', '_bx_pfw_po_active_dsc', '', '', '', '', 1),
(@iProviderId, 'rb_mode', 'select', '_bx_pfw_po_mode_cpt', '_bx_pfw_po_mode_dsc', '1|_bx_pfw_po_mode_live,2|_bx_pfw_po_mode_test', '', '', '', 2),
(@iProviderId, 'rb_partner', 'text', '_bx_pfw_po_partner_cpt', '_bx_pfw_po_partner_dsc', '', '', '', '', 3),
(@iProviderId, 'rb_vendor', 'text', '_bx_pfw_po_vendor_cpt', '_bx_pfw_po_vendor_dsc', '', '', '', '', 4),
(@iProviderId, 'rb_user', 'text', '_bx_pfw_po_user_cpt', '_bx_pfw_po_user_dsc', '', '', '', '', 5),
(@iProviderId, 'rb_password', 'text', '_bx_pfw_po_password_cpt', '_bx_pfw_po_password_dsc', '', '', '', '', 6);