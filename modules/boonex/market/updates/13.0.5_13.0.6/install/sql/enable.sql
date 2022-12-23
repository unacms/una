-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`product_id` AS `product_id`, `tp`.`title` AS `product`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`type` AS `type`, `tl`.`domain` AS `domain`, `tl`.`added` AS `added`, `tl`.`expired` AS `expired` FROM `bx_market_licenses` AS `tl` LEFT JOIN `bx_market_products` AS `tp` ON `tl`.`product_id`=`tp`.`id` LEFT JOIN `sys_profiles` AS `tup` ON `tl`.`profile_id`=`tup`.`id` LEFT JOIN `sys_accounts` AS `tua` ON `tup`.`account_id`=`tua`.`id` WHERE 1 ', `filter_fields`='tp`.`title,tl`.`order,tl`.`license,tl`.`type,tl`.`domain,tua`.`name,tua`.`email' WHERE `object`='bx_market_licenses_administration';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object`='bx_market_simple';
