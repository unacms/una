-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_market_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_market_allow_view_favorite_list', 'bx_market', 'view_favorite_list', '_bx_market_form_entry_input_allow_view_favorite_list', '3', 'all', 'bx_market_favorites_lists', 'id', 'author_id', '', '');
