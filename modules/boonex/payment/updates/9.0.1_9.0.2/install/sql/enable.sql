SET @sName = 'bx_payment';


-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_payment@modules/boonex/payment/|std-icon.svg' WHERE `name`=@sName;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object` IN ('bx_payment_cart_thank_you', 'bx_payment_sbs_list', 'bx_payment_sbs_history');
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_cart_thank_you', '_bx_payment_page_title_sys_cart_thank_you', '_bx_payment_page_title_cart_thank_you', @sName, 5, 2147483647, 1, 'payment-cart-thank-you', 'page.php?i=payment-cart-thank-you', '', '', '', 0, 1, 0, 'BxPaymentPageCart', 'modules/boonex/payment/classes/BxPaymentPageCart.php'),
('bx_payment_sbs_list', '_bx_payment_page_title_sys_sbs_list', '_bx_payment_page_title_sbs_list', @sName, 5, 2147483647, 1, 'payment-sbs-list', 'page.php?i=payment-sbs-list', '', '', '', 0, 1, 0, 'BxPaymentPageSubscriptions', 'modules/boonex/payment/classes/BxPaymentPageSubscriptions.php'),
('bx_payment_sbs_history', '_bx_payment_page_title_sys_sbs_history', '_bx_payment_page_title_sbs_history', @sName, 5, 2147483647, 1, 'payment-sbs-history', 'page.php?i=payment-sbs-history', '', '', '', 0, 1, 0, 'BxPaymentPageSubscriptions', 'modules/boonex/payment/classes/BxPaymentPageSubscriptions.php');

DELETE FROM `sys_pages_blocks` WHERE `object` IN ('bx_payment_cart_thank_you', 'bx_payment_sbs_list', 'bx_payment_sbs_history');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_payment_cart_thank_you', 1, @sName, '_bx_payment_page_block_title_cart_thank_you', 11, 2147483647, 'lang', '_bx_payment_page_block_content_cart_thank_you', 0, 0, 1),
('bx_payment_sbs_list', 1, @sName, '_bx_payment_page_block_title_sbs_list', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:14:"get_block_list";s:6:"params";a:0:{}s:5:"class";s:13:"Subscriptions";}', 0, 0, 1),
('bx_payment_sbs_history', 1, @sName, '_bx_payment_page_block_title_sbs_history', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:17:"get_block_history";s:6:"params";a:0:{}s:5:"class";s:13:"Subscriptions";}', 0, 0, 1);


-- MENUS
UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_payment_menu_cart_submenu';
UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_payment_menu_orders_submenu';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_payment_menu_sbs_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_payment_menu_sbs_submenu', '_bx_payment_menu_title_sbs_submenu', 'bx_payment_menu_sbs_submenu', 'bx_payment', 8, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_payment_menu_sbs_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_payment_menu_sbs_submenu', 'bx_payment', '_bx_payment_menu_set_title_sbs_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_payment_menu_sbs_submenu' AND `name` IN ('sbs-list', 'sbs-history');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_sbs_submenu', 'bx_payment', 'sbs-list', '_bx_payment_menu_item_title_system_sbs_list', '_bx_payment_menu_item_title_sbs_list', 'page.php?i=payment-sbs-list', '', '_self', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_payment_menu_sbs_submenu', 'bx_payment', 'sbs-history', '_bx_payment_menu_item_title_system_sbs_history', '_bx_payment_menu_item_title_sbs_history', 'page.php?i=payment-sbs-history', '', '_self', '', '', '', 2147483647, 1, 0, 1, 2);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_payment_menu_sbs_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_payment_menu_sbs_actions', '_bx_payment_menu_title_sbs_actions', 'bx_payment_menu_sbs_actions', 'bx_payment', 6, 0, 1, 'BxPaymentMenuSbsActions', 'modules/boonex/payment/classes/BxPaymentMenuSbsActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_payment_menu_sbs_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_payment_menu_sbs_actions', 'bx_payment', '_bx_payment_menu_set_title_sbs_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_payment_menu_sbs_actions' AND `name` IN ('sbs-cancel');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_sbs_actions', 'bx_payment', 'sbs-cancel', '_bx_payment_menu_item_title_system_sbs_cancel', '_bx_payment_menu_item_title_sbs_cancel', 'javascript:void(0)', '{js_object}.requestCancelation(this, {id})', '_self', '', '', '', 2147483647, 1, 0, 1, 1);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module`=@sName AND `Name`='bx_payment_cancelation_request';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_cancelation_request', 'bx_payment_cancelation_request', '_bx_payment_et_txt_subject_cancelation_request', '_bx_payment_et_txt_body_cancelation_request');