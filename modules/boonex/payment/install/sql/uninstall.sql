SET @sModuleName = 'Payment';

DROP TABLE IF EXISTS `[db_prefix]providers`;
DROP TABLE IF EXISTS `[db_prefix]providers_options`;
DROP TABLE IF EXISTS `[db_prefix]user_values`;
DROP TABLE IF EXISTS `[db_prefix]cart`;
DROP TABLE IF EXISTS `[db_prefix]transactions`;
DROP TABLE IF EXISTS `[db_prefix]transactions_pending`;
DROP TABLE IF EXISTS `[db_prefix]modules`;

SET @iCategoryId = (SELECT `ID` FROM `sys_options_cats` WHERE `name`=@sModuleName LIMIT 1);
DELETE FROM `sys_options_cats` WHERE `name`=@sModuleName LIMIT 1;
DELETE FROM `sys_options` WHERE `kateg`=@iCategoryId OR `Name`='permalinks_module_payment';

DELETE FROM `sys_permalinks` WHERE `check`='permalinks_module_payment';

DELETE FROM `sys_page_compose_pages` WHERE `Name` IN ('bx_pmt_cart', 'bx_pmt_history', 'bx_pmt_orders', 'bx_pmt_details');
DELETE FROM `sys_page_compose` WHERE `Page` IN ('bx_pmt_cart', 'bx_pmt_history', 'bx_pmt_orders', 'bx_pmt_details');

DELETE FROM `sys_menu_member` WHERE `Name`='Shopping Cart';

DELETE FROM `sys_menu_top` WHERE `Name` IN ('Payments', 'Cart');
DELETE FROM `sys_menu_admin` WHERE `name`='bx_payment';