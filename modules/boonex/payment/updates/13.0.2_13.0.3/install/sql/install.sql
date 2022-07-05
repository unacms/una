SET @sName = 'bx_payment';


-- TABLES
DELETE FROM `tp`, `tpo`, `tuv` USING `bx_payment_providers` AS `tp` LEFT JOIN `bx_payment_providers_options` AS `tpo` ON `tp`.`id`=`tpo`.`provider_id` LEFT JOIN `bx_payment_user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id` WHERE `tp`.`name`='2checkout';

DELETE FROM `tp`, `tpo`, `tuv` USING `bx_payment_providers` AS `tp` LEFT JOIN `bx_payment_providers_options` AS `tpo` ON `tp`.`id`=`tpo`.`provider_id` LEFT JOIN `bx_payment_user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id` WHERE `tp`.`name`='bitpay';

DELETE FROM `tp`, `tpo`, `tuv` USING `bx_payment_providers` AS `tp` LEFT JOIN `bx_payment_providers_options` AS `tpo` ON `tp`.`id`=`tpo`.`provider_id` LEFT JOIN `bx_payment_user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id` WHERE `tp`.`name`='recurly';
