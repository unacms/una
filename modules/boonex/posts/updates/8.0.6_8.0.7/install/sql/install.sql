ALTER TABLE `bx_posts_posts` ADD `cat` int(11) NOT NULL;


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='cat';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'cat', '', '#!bx_posts_cats', 0, 'select', '_bx_posts_form_entry_input_sys_cat', '_bx_posts_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_posts_form_entry_input_cat_err', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_add' AND `input_name`='cat';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_delete' AND `input_name` IN ('location', 'cat', 'do_publish');
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_edit' AND `input_name` IN ('cat', 'do_publish');
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_view' AND `input_name` IN ('cat', 'do_publish');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'cat', 2147483647, 1, 3),
('bx_posts_entry_delete', 'location', 2147483647, 0, 0),
('bx_posts_entry_delete', 'cat', 2147483647, 0, 0),
('bx_posts_entry_delete', 'do_publish', 2147483647, 0, 0),
('bx_posts_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_posts_entry_edit', 'cat', 2147483647, 1, 4),
('bx_posts_entry_view', 'cat', 2147483647, 0, 0),
('bx_posts_entry_view', 'do_publish', 2147483647, 0, 0);


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_posts_cats';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_posts_cats', '_bx_posts_pre_lists_cats', 'bx_posts', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_posts_cats';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_posts_cats', '', 0, '_sys_please_select', ''),
('bx_posts_cats', '1', 1, '_bx_posts_cat_Animals_Pets', ''),
('bx_posts_cats', '2', 2, '_bx_posts_cat_Architecture', ''),
('bx_posts_cats', '3', 3, '_bx_posts_cat_Art', ''),
('bx_posts_cats', '4', 4, '_bx_posts_cat_Cars_Motorcycles', ''),
('bx_posts_cats', '5', 5, '_bx_posts_cat_Celebrities', ''),
('bx_posts_cats', '6', 6, '_bx_posts_cat_Design', ''),
('bx_posts_cats', '7', 7, '_bx_posts_cat_DIY_Crafts', ''),
('bx_posts_cats', '8', 8, '_bx_posts_cat_Education', ''),
('bx_posts_cats', '9', 9, '_bx_posts_cat_Film_Music_Books', ''),
('bx_posts_cats', '10', 10, '_bx_posts_cat_Food_Drink', ''),
('bx_posts_cats', '11', 11, '_bx_posts_cat_Gardening', ''),
('bx_posts_cats', '12', 12, '_bx_posts_cat_Geek', ''),
('bx_posts_cats', '13', 13, '_bx_posts_cat_Hair_Beauty', ''),
('bx_posts_cats', '14', 14, '_bx_posts_cat_Health_Fitness', ''),
('bx_posts_cats', '15', 15, '_bx_posts_cat_History', ''),
('bx_posts_cats', '16', 16, '_bx_posts_cat_Holidays_Events', ''),
('bx_posts_cats', '17', 17, '_bx_posts_cat_Home_Decor', ''),
('bx_posts_cats', '18', 18, '_bx_posts_cat_Humor', ''),
('bx_posts_cats', '19', 19, '_bx_posts_cat_Illustrations_Posters', ''),
('bx_posts_cats', '20', 20, '_bx_posts_cat_Kids_Parenting', ''),
('bx_posts_cats', '21', 21, '_bx_posts_cat_Mens_Fashion', ''),
('bx_posts_cats', '22', 22, '_bx_posts_cat_Outdoors', ''),
('bx_posts_cats', '23', 23, '_bx_posts_cat_Photography', ''),
('bx_posts_cats', '24', 24, '_bx_posts_cat_Products', ''),
('bx_posts_cats', '25', 25, '_bx_posts_cat_Quotes', ''),
('bx_posts_cats', '26', 26, '_bx_posts_cat_Science_Nature', ''),
('bx_posts_cats', '27', 27, '_bx_posts_cat_Sports', ''),
('bx_posts_cats', '28', 28, '_bx_posts_cat_Tattoos', ''),
('bx_posts_cats', '29', 29, '_bx_posts_cat_Technology', ''),
('bx_posts_cats', '30', 30, '_bx_posts_cat_Travel', ''),
('bx_posts_cats', '31', 31, '_bx_posts_cat_Weddings', ''),
('bx_posts_cats', '32', 32, '_bx_posts_cat_Womens_Fashion', '');