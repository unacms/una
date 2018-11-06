SET @sName = 'bx_payment';


-- TABLES
UPDATE `bx_payment_providers_options` SET `type` = 'text' WHERE `type` IS NULL;
ALTER TABLE `bx_payment_providers_options` CHANGE `type` `type` VARCHAR( 64 ) NOT NULL DEFAULT 'text';

UPDATE `bx_payment_transactions_pending` SET `type` = 'single' WHERE `type` IS NULL;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `type` `type` VARCHAR( 16 ) NOT NULL DEFAULT 'single';

UPDATE `bx_payment_providers` SET `active`='1', `order`='1' WHERE `name`='paypal';
UPDATE `bx_payment_providers` SET `active`='0', `order`='2' WHERE `name`='2checkout';
UPDATE `bx_payment_providers` SET `active`='0', `order`='3' WHERE `name`='bitpay';
UPDATE `bx_payment_providers` SET `active`='1', `order`='4' WHERE `name`='chargebee';
UPDATE `bx_payment_providers` SET `active`='1', `order`='5' WHERE `name`='chargebee_v3';
UPDATE `bx_payment_providers` SET `active`='0', `order`='6' WHERE `name`='recurly';
UPDATE `bx_payment_providers` SET `active`='1', `order`='7' WHERE `name`='stripe';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_payment_grid_providers';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_grid_providers', 'Sql', 'SELECT * FROM `bx_payment_providers` WHERE 1 ', 'bx_payment_providers', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'name', 'caption,description', 'auto', '', '', 192, 'BxPaymentGridProviders', 'modules/boonex/payment/classes/BxPaymentGridProviders.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_providers';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_providers', 'order', '', '2%', 0, '', '', 1),
('bx_payment_grid_providers', 'switcher', '', '8%', 0, '', '', 2),
('bx_payment_grid_providers', 'caption', '_bx_payment_grid_column_title_pdrs_caption', '25%', 1, '16', '', 3),
('bx_payment_grid_providers', 'description', '_bx_payment_grid_column_title_pdrs_description', '35%', 1, '32', '', 4),
('bx_payment_grid_providers', 'for_visitor', '_bx_payment_grid_column_title_pdrs_for_visitor', '10%', 0, '8', '', 5),
('bx_payment_grid_providers', 'for_single', '_bx_payment_grid_column_title_pdrs_for_single', '10%', 0, '8', '', 6),
('bx_payment_grid_providers', 'for_recurring', '_bx_payment_grid_column_title_pdrs_for_recurring', '10%', 0, '8', '', 7);
