
UPDATE `sys_menu_member` SET `Name` = 'Shopping Cart' WHERE `Name` = 'bx_pmt_cart' AND `Icon` = 'modules/boonex/payment/|tbar_item_cart.png';

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'payment' AND `version` = '1.0.3';

