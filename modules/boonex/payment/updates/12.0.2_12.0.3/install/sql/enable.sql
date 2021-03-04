SET @sName = 'bx_payment';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_payment_checkout_offline';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `type_id`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_payment_checkout_offline', 'payment-checkout-offline', '_bx_payment_page_title_sys_checkout_offline', '_bx_payment_page_title_checkout_offline', 'bx_payment', 2, 5, 2147483647, 1, 'page.php?i=payment-checkout-offline', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_payment_checkout_offline';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_payment_checkout_offline', 1, 'bx_payment', '_bx_payment_page_block_title_sys_checkout_offline', '_bx_payment_page_block_title_checkout_offline', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_payment";s:6:"method";s:26:"get_block_checkout_offline";}', 0, 0, 1, 0);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_payment_checkout_offline';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_checkout_offline', 'bx_payment_checkout_offline', '_bx_payment_et_txt_subject_checkout_offline', '_bx_payment_et_txt_body_checkout_offline');
