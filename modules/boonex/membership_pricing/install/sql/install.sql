INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_acl_prices', 'Sql', 'SELECT * FROM `sys_acl_level_prices` WHERE 1 ', 'sys_acl_level_prices', 'id', 'Order', '', '', 100, NULL, 'start', '', 'Days,Price', '', 'like', '', '', 'BxTemplStudioPermissionsPrices', '');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_acl_prices', 'Order', '', '1%', 0, '', '', 1),
('sys_studio_acl_prices', 'Days', '_adm_prm_txt_term', '40%', 0, '', '', 2),
('sys_studio_acl_prices', 'Price', '_adm_prm_txt_price', '40%', 0, '', '', 3),
('sys_studio_acl_prices', 'actions', '', '19%', 0, '', '', 4);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_acl_prices', 'independent', 'add', '_adm_prm_btn_add_price', '', 0, 1),
('sys_studio_acl_prices', 'single', 'edit', '', 'pencil', 0, 1),
('sys_studio_acl_prices', 'single', 'delete', '', 'remove', 1, 2);