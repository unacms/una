-- TABLES
UPDATE `bx_market_products` SET `duration_recurring` = 'month' WHERE `duration_recurring` IS NULL;
ALTER TABLE `bx_market_products` CHANGE `duration_recurring` `duration_recurring` VARCHAR( 16 ) NOT NULL DEFAULT 'month';
