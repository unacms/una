SET @sName = 'bx_payment';

-- SETTINGS
UPDATE `sys_options_categories` SET `name`='bx_payment_general', `caption`='_bx_payment_options_category_general' WHERE `name`=@sName;

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_payment_commissions';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_payment_commissions', '_bx_payment_options_category_commissions', 10);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN ('bx_payment_inv_issue_day', 'bx_payment_inv_lifetime', 'bx_payment_inv_expiraction_notify');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_inv_issue_day', '1', @iCategoryId, '_bx_payment_option_inv_issue_day', 'digit', '', '', '', '', 1),
('bx_payment_inv_lifetime', '4', @iCategoryId, '_bx_payment_option_inv_lifetime', 'digit', '', '', '', '', 2),
('bx_payment_inv_expiraction_notify', '1', @iCategoryId, '_bx_payment_option_inv_expiraction_notify', 'digit', '', '', '', '', 3);
