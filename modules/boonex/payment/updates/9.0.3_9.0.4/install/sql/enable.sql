SET @sName = 'bx_payment';

-- PAGES
DELETE FROM `sys_objects_page` WHERE `object` IN ('bx_payment_sbs_list', 'bx_payment_sbs_list_my', 'bx_payment_sbs_list_all');
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_sbs_list_my', '_bx_payment_page_title_sys_sbs_list_my', '_bx_payment_page_title_sbs_list_my', @sName, 5, 2147483647, 1, 'payment-sbs-list-my', 'page.php?i=payment-sbs-list-my', '', '', '', 0, 1, 0, 'BxPaymentPageSubscriptions', 'modules/boonex/payment/classes/BxPaymentPageSubscriptions.php'),
('bx_payment_sbs_list_all', '_bx_payment_page_title_sys_sbs_list_all', '_bx_payment_page_title_sbs_list_all', @sName, 5, 192, 1, 'payment-sbs-list-all', 'page.php?i=payment-sbs-list-all', '', '', '', 0, 1, 0, 'BxPaymentPageSubscriptions', 'modules/boonex/payment/classes/BxPaymentPageSubscriptions.php');

DELETE FROM `sys_pages_blocks` WHERE `object` IN ('bx_payment_sbs_list', 'bx_payment_sbs_list_my', 'bx_payment_sbs_list_all');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_payment_sbs_list_my', 1, @sName, '_bx_payment_page_block_title_sbs_list_my', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:17:"get_block_list_my";s:6:"params";a:0:{}s:5:"class";s:13:"Subscriptions";}', 0, 0, 1),
('bx_payment_sbs_list_all', 1, @sName, '_bx_payment_page_block_title_sbs_list_all', 11, 192, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:18:"get_block_list_all";s:6:"params";a:0:{}s:5:"class";s:13:"Subscriptions";}', 0, 0, 1);


-- MENUS
UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_payment_menu_sbs_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_payment_menu_sbs_submenu' AND `name` IN ('sbs-list-all', 'sbs-list-my', 'sbs-list');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_sbs_submenu', 'bx_payment', 'sbs-list-all', '_bx_payment_menu_item_title_system_sbs_list_all', '_bx_payment_menu_item_title_sbs_list_all', 'page.php?i=payment-sbs-list-all', '', '_self', '', '', '', 192, 1, 0, 1, 1),
('bx_payment_menu_sbs_submenu', 'bx_payment', 'sbs-list-my', '_bx_payment_menu_item_title_system_sbs_list_my', '_bx_payment_menu_item_title_sbs_list_my', 'page.php?i=payment-sbs-list-my', '', '_self', '', '', '', 2147483646, 1, 0, 1, 2);

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_payment_menu_orders_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_payment_menu_orders_submenu' AND `name`='details';

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `name`='payment-details';
SET @iMoAccountSettings = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `order` < 9999 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_settings', 'bx_payment', 'payment-details', '_bx_payment_menu_item_title_system_details', '_bx_payment_menu_item_title_details', 'page.php?i=payment-details', '', '_self', '', '', '', 2147483646, 1, 0, 1, @iMoAccountSettings + 1);
