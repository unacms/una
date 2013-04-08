SET @sModuleName = 'Payment';

--
-- Table structure for table `[db_prefix]providers`
--
CREATE TABLE IF NOT EXISTS `[db_prefix]providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(128) NOT NULL default '',
  `description` text NOT NULL default '',
  `option_prefix` varchar(32) NOT NULL default '',
  `class_name` varchar(128) NOT NULL default '',
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
  `error_code` varchar(16) NOT NULL default '',
  `error_msg` varchar(255) NOT NULL default '',
  `provider` varchar(16) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
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


SET @iCategoryOrder = (SELECT MAX(`menu_order`) FROM `sys_options_cats`) + 1;
INSERT INTO `sys_options_cats` (`name` , `menu_order` ) VALUES (@sModuleName, @iCategoryOrder);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('pmt_default_currency_code', 'USD', @iCategoryId, 'Currency code', 'select', 'return strlen($arg0) > 0;', 'Cannot be empty.', 0, 'AUD,CAD,EUR,GBP,USD,YEN'),
('pmt_default_currency_sign', '&#36;', @iCategoryId, 'Currency sign', 'text', 'return strlen($arg0) > 0;', 'Cannot be empty.', 1, ''),
('permalinks_module_payment', 'on', 26, 'Enable friendly payment permalink', 'checkbox', '', '', 0, '');


INSERT INTO `sys_permalinks`(`standard`, `permalink`, `check`) VALUES('modules/?r=payment/', 'm/payment/', 'permalinks_module_payment');

SET @iPCPOrder = (SELECT MAX(`Order`) FROM `sys_page_compose_pages`);
INSERT INTO `sys_page_compose_pages`(`Name`, `Title`, `Order`) VALUES 
('bx_pmt_cart', 'Shopping Cart', @iPCPOrder+1),
('bx_pmt_history', 'Cart History', @iPCPOrder+2),
('bx_pmt_orders', 'Order Administration', @iPCPOrder+3),
('bx_pmt_details', 'Payment Settings', @iPCPOrder+4);

INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
('bx_pmt_cart', '998px', 'Site Cart', '_payment_bcaption_cart_featured', 1, 0, 'Featured', '', 1, 34, 'memb', 0),
('bx_pmt_cart', '998px', 'Members Cart', '_payment_bcaption_cart_common', 2, 0, 'Common', '', 1, 66, 'memb', 0),
('bx_pmt_history', '998px', 'History', '_payment_bcaption_cart_history', 1, 0, 'History', '', 1, 100, 'memb', 0),
('bx_pmt_orders', '998px', 'Orders', '_payment_bcaption_processed_orders', 1, 0, 'Orders', '', 1, 100, 'memb', 0),
('bx_pmt_details', '998px', 'Payment Details', '_payment_bcaption_details', 1, 0, 'Details', '', 1, 100, 'memb', 0);

INSERT INTO `sys_menu_member` (`Caption`, `Name`, `Icon`, `Link`, `Script`, `Eval`, `PopupMenu`, `Order`, `Active`, `Editable`, `Deletable`, `Target`, `Position`, `Type`, `Parent`, `Bubble`, `Description`) VALUES
('_payment_tbar_item_caption', 'Shopping Cart', 'modules/boonex/payment/|tbar_item_cart.png', 'modules/?r=payment/cart/', '', '', 'return BxDolService::call(''payment'', ''get_cart_items'');', 0, '1', 0, 0, '', 'top_extra', 'link', 0, '$aRetEval = BxDolService::call(''payment'', ''get_cart_item_count'', array({ID}, {iOldCount}));', '_payment_tbar_item_description');

SET @iTMOrder = (SELECT MAX(`Order`) FROM `sys_menu_top` WHERE `Parent`=118);
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(118, 'Payments', '_payment_tmenu_payments', 'modules/?r=payment/orders/|modules/?r=payment/details/', @iTMOrder+1, 'memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(118, 'Cart', '_payment_tmenu_cart', 'modules/?r=payment/cart/|modules/?r=payment/history/', @iTMOrder+2, 'memb', '', '', '', 1, 1, 1, 'custom', '', 0, '');

SET @iOrder = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id`='2');
INSERT INTO `sys_menu_admin`(`parent_id`, `name`, `title`, `url`, `description`, `icon`, `icon_large`, `check`, `order`) VALUES
(2, 'bx_payment', '_payment_amenu_payment', '{siteUrl}modules/?r=payment/admin/', 'For managing payment module', 'modules/boonex/payment/|amenu_item.gif', '', '', @iOrder+1);


--
-- PayPal payment provider
--
INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `class_name`) VALUES('paypal', 'PayPal', 'PayPal payment provider', 'pp_', 'BxPmtPayPal');
SET @iProviderId = LAST_INSERT_ID();
INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'pp_active', 'checkbox', '_payment_pp_active_cpt', '_payment_pp_active_dsc', '', '', '', '', 1),
(@iProviderId, 'pp_mode', 'select', '_payment_pp_mode_cpt', '_payment_pp_mode_dsc', '1|_payment_pp_mode_live,2|_payment_pp_mode_test', '', '', '', 2),
(@iProviderId, 'pp_business', 'text', '_payment_pp_business_cpt', '_payment_pp_business_dsc', '', '', '', '', 3),
(@iProviderId, 'pp_prc_type', 'select', '_payment_pp_prc_type_cpt', '_payment_pp_prc_type_dsc', '1|_payment_pp_prc_type_direct,2|_payment_pp_prc_type_pdt,3|_payment_pp_prc_type_ipn', '', '', '', 4),
(@iProviderId, 'pp_cnt_type', 'select', '_payment_pp_cnt_type_cpt', '_payment_pp_cnt_type_dsc', '1|_payment_pp_cnt_type_ssl,2|_payment_pp_cnt_type_html', '', '', '', 5),
(@iProviderId, 'pp_token', 'text', '_payment_pp_token_cpt', '_payment_pp_token_dsc', '', '', '', '', 6),
(@iProviderId, 'pp_sandbox', 'text', '_payment_pp_sandbox_cpt', '_payment_pp_sandbox_dsc', '', '', '', '', 7);


--
-- 2Checkout payment provider
--
INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `class_name`) VALUES('2checkout', '2Checkout', '2Checkout payment provider', '2co_', 'BxPmt2Checkout');
SET @iProviderId = LAST_INSERT_ID();
INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, '2co_active', 'checkbox', '_payment_2co_active_cpt', '_payment_2co_active_dsc', '', '', '', '', 1),
(@iProviderId, '2co_mode', 'select', '_payment_2co_mode_cpt', '_payment_2co_mode_dsc', '1|_payment_2co_mode_live,2|_payment_2co_mode_test', '', '', '', 2),
(@iProviderId, '2co_account_id', 'text', '_payment_2co_account_id_cpt', '_payment_2co_account_id_dsc', '', '', '', '', 3),
(@iProviderId, '2co_payment_method', 'select', '_payment_2co_payment_method_cpt', '_payment_2co_payment_method_dsc', 'CC|_payment_2co_payment_method_cc,CK|_payment_2co_payment_method_ck,AL|_payment_2co_payment_method_al,PPI|_payment_2co_payment_method_ppi', '', '', '', 4),
(@iProviderId, '2co_secret_word', 'text', '_payment_2co_secret_word_cpt', '_payment_2co_secret_word_dsc', '', '', '', '', 5);