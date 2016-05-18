SET @sName = 'bx_payment';


-- OPTIONS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_payment', 'bx_payment@modules/boonex/payment/|std-mi.png', @iTypeOrder + 1);
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_payment', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_default_currency_code', 'USD', @iCategoryId, '_bx_payment_option_default_currency_code', 'select', 'Avail', '', '_bx_payment_option_err_default_currency_code', 'AUD,CAD,EUR,GBP,USD,YEN', 0),
('bx_payment_site_admin', '', @iCategoryId, '_bx_payment_option_site_admin', 'select', '', '', '', 'a:2:{s:6:"module";s:10:"bx_payment";s:6:"method";s:22:"get_options_site_admin";}', 1);


-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_join', '_bx_payment_page_title_sys_join', '_bx_payment_page_title_join', @sName, 5, 2147483647, 1, 'payment-join', 'page.php?i=payment-join', '', '', '', 0, 1, 0, 'BxPaymentPageJoin', 'modules/boonex/payment/classes/BxPaymentPageJoin.php'),
('bx_payment_carts', '_bx_payment_page_title_sys_carts', '_bx_payment_page_title_carts', @sName, 5, 2147483647, 1, 'payment-carts', 'page.php?i=payment-carts', '', '', '', 0, 1, 0, 'BxPaymentPageCart', 'modules/boonex/payment/classes/BxPaymentPageCart.php'),
('bx_payment_cart', '_bx_payment_page_title_sys_cart', '_bx_payment_page_title_cart', @sName, 5, 2147483647, 1, 'payment-cart', 'page.php?i=payment-cart', '', '', '', 0, 1, 0, 'BxPaymentPageCart', 'modules/boonex/payment/classes/BxPaymentPageCart.php'),
('bx_payment_history', '_bx_payment_page_title_sys_history', '_bx_payment_page_title_history', @sName, 5, 2147483647, 1, 'payment-history', 'page.php?i=payment-history', '', '', '', 0, 1, 0, 'BxPaymentPageHistory', 'modules/boonex/payment/classes/BxPaymentPageHistory.php'),
('bx_payment_orders', '_bx_payment_page_title_sys_orders', '_bx_payment_page_title_orders', @sName, 5, 2147483647, 1, 'payment-orders', 'page.php?i=payment-orders', '', '', '', 0, 1, 0, 'BxPaymentPageOrders', 'modules/boonex/payment/classes/BxPaymentPageOrders.php'),
('bx_payment_details', '_bx_payment_page_title_sys_details', '_bx_payment_page_title_details', @sName, 5, 2147483647, 1, 'payment-details', 'page.php?i=payment-details', '', '', '', 0, 1, 0, 'BxPaymentPageDetails', 'modules/boonex/payment/classes/BxPaymentPageDetails.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_payment_join', 1, @sName, '_bx_payment_page_block_title_join', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:14:"get_block_join";s:6:"params";a:0:{}s:5:"class";s:4:"Join";}', 0, 0, 1),

('bx_payment_carts', 1, @sName, '_bx_payment_page_block_title_carts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:15:"get_block_carts";s:6:"params";a:0:{}s:5:"class";s:4:"Cart";}', 0, 0, 1),

('bx_payment_cart', 1, @sName, '_bx_payment_page_block_title_cart', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:14:"get_block_cart";s:6:"params";a:0:{}s:5:"class";s:4:"Cart";}', 0, 0, 1),

('bx_payment_history', 1, @sName, '_bx_payment_page_block_title_history', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:22:"get_block_cart_history";s:6:"params";a:0:{}s:5:"class";s:4:"Cart";}', 0, 0, 1),

('bx_payment_orders', 1, @sName, '_bx_payment_page_block_title_orders', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:16:"get_block_orders";s:6:"params";a:0:{}s:5:"class";s:6:"Orders";}', 0, 0, 1),

('bx_payment_details', 1, @sName, '_bx_payment_page_block_title_details', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:17:"get_block_details";s:6:"params";a:0:{}s:5:"class";s:7:"Details";}', 0, 0, 1);


-- MENUS
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_payment_menu_cart_submenu', '_bx_payment_menu_title_cart_submenu', 'bx_payment_menu_cart_submenu', 'bx_payment', 6, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_payment_menu_cart_submenu', 'bx_payment', '_bx_payment_menu_set_title_cart_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_cart_submenu', 'bx_payment', 'cart', '_bx_payment_menu_item_title_system_cart', '_bx_payment_menu_item_title_cart', 'page.php?i=payment-carts', '', '_self', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_payment_menu_cart_submenu', 'bx_payment', 'cart-history', '_bx_payment_menu_item_title_system_cart_history', '_bx_payment_menu_item_title_cart_history', 'page.php?i=payment-history', '', '_self', '', '', '', 2147483647, 1, 0, 1, 2);


INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_payment_menu_orders_submenu', '_bx_payment_menu_title_orders_submenu', 'bx_payment_menu_orders_submenu', 'bx_payment', 6, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_payment_menu_orders_submenu', 'bx_payment', '_bx_payment_menu_set_title_orders_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_orders_submenu', 'bx_payment', 'orders-processed', '_bx_payment_menu_item_title_system_orders_processed', '_bx_payment_menu_item_title_orders_processed', 'page.php?i=payment-orders&type=processed', '', '_self', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_payment_menu_orders_submenu', 'bx_payment', 'orders-pending', '_bx_payment_menu_item_title_system_orders_pending', '_bx_payment_menu_item_title_orders_pending', 'page.php?i=payment-orders&type=pending', '', '_self', '', '', '', 2147483647, 1, 0, 1, 2),
('bx_payment_menu_orders_submenu', 'bx_payment', 'details', '_bx_payment_menu_item_title_system_details', '_bx_payment_menu_item_title_details', 'page.php?i=payment-details', '', '_self', '', '', '', 2147483647, 1, 0, 1, 3);


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxPaymentResponse', 'modules/boonex/payment/classes/BxPaymentResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'join', @iHandler),
('profile', 'delete', @iHandler);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'purchase', NULL, '_bx_payment_acl_action_purchase', '', 1, 3);
SET @iIdActionPurchase = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'sell', NULL, '_bx_payment_acl_action_sell', '', 1, 3);
SET @iIdActionSell = LAST_INSERT_ID();

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

-- purchase
(@iStandard, @iIdActionPurchase),
(@iModerator, @iIdActionPurchase),
(@iAdministrator, @iIdActionPurchase),
(@iPremium, @iIdActionPurchase),

-- sell
(@iStandard, @iIdActionSell),
(@iModerator, @iIdActionSell),
(@iAdministrator, @iIdActionSell),
(@iPremium, @iIdActionSell);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_paid_need_join', 'bx_payment_paid_need_join', '_bx_payment_et_txt_subject_paid_need_join', '_bx_payment_et_txt_body_paid_need_join');


-- PAYMENTS
INSERT INTO `sys_objects_payments` (`object`, `title`, `uri`) VALUES
(@sName, '_bx_payment', 'payment');