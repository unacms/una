SET @sName = 'bx_payment';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''single'' ' WHERE `object`='bx_payment_grid_orders_history';

UPDATE `sys_grid_fields` SET `name`='item', `title`='_bx_payment_grid_column_title_ods_item' WHERE `object`='bx_payment_grid_orders_history' AND `name`='license';
UPDATE `sys_grid_fields` SET `name`='item', `title`='_bx_payment_grid_column_title_ods_item' WHERE `object`='bx_payment_grid_orders_processed' AND `name`='license';
