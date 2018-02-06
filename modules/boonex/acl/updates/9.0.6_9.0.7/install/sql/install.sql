-- TABLE: bx_acl_level_prices

ALTER TABLE `bx_acl_level_prices` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_acl_level_prices` CHANGE `name` `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_acl_level_prices` CHANGE `period_unit` `period_unit` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_acl_level_prices`;
OPTIMIZE TABLE `bx_acl_level_prices`;


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_acl_price_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_edit', 'id', 2147483647, 1, 1),
('bx_acl_price_edit', 'level_id', 2147483647, 1, 2),
('bx_acl_price_edit', 'price', 2147483647, 1, 3),
('bx_acl_price_edit', 'period', 2147483647, 1, 4),
('bx_acl_price_edit', 'period_unit', 2147483647, 1, 5),
('bx_acl_price_edit', 'trial', 2147483647, 1, 6),
('bx_acl_price_edit', 'controls', 2147483647, 1, 7),
('bx_acl_price_edit', 'do_submit', 2147483647, 1, 8),
('bx_acl_price_edit', 'do_cancel', 2147483647, 1, 9);
