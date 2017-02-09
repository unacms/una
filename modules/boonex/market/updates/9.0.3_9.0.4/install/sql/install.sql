SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- TABLE
ALTER TABLE `bx_market_files` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_market_photos` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_market_photos_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_market_files';
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_market_photos';
UPDATE `sys_objects_storage` SET `engine`=@sStorageEngine WHERE `object`='bx_market_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_market_icon', 'bx_market_thumb');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_market_icon', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0'),
('bx_market_thumb', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_market_icon', 'bx_market_thumb');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_market_icon', 'Resize', 'a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}', '0'),
('bx_market_thumb', 'Resize', 'a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}', '0');


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name` IN ('trial_recurring');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'trial_recurring', '', '', 0, 'text', '_bx_market_form_entry_input_sys_trial_recurring', '_bx_market_form_entry_input_trial_recurring', '_bx_market_form_entry_input_trial_recurring_inf', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_market_entry_add', 'title', 2147483647, 1, 2),
('bx_market_entry_add', 'name', 2147483647, 1, 3),
('bx_market_entry_add', 'cat', 2147483647, 1, 4),
('bx_market_entry_add', 'text', 2147483647, 1, 5),
('bx_market_entry_add', 'pictures', 2147483647, 1, 6),
('bx_market_entry_add', 'files', 2147483647, 1, 7),
('bx_market_entry_add', 'header_beg_single', 2147483647, 1, 8),
('bx_market_entry_add', 'warning_single', 2147483647, 1, 9),
('bx_market_entry_add', 'price_single', 2147483647, 1, 10),
('bx_market_entry_add', 'header_end_single', 2147483647, 1, 11),
('bx_market_entry_add', 'header_beg_recurring', 2147483647, 1, 12),
('bx_market_entry_add', 'warning_recurring', 2147483647, 1, 13),
('bx_market_entry_add', 'duration_recurring', 2147483647, 1, 14),
('bx_market_entry_add', 'price_recurring', 2147483647, 1, 15),
('bx_market_entry_add', 'trial_recurring', 2147483647, 1, 16),
('bx_market_entry_add', 'header_end_recurring', 2147483647, 1, 17),
('bx_market_entry_add', 'header_beg_privacy', 2147483647, 1, 18),
('bx_market_entry_add', 'allow_view_to', 2147483647, 1, 19),
('bx_market_entry_add', 'allow_purchase_to', 2147483647, 1, 20),
('bx_market_entry_add', 'allow_comment_to', 2147483647, 1, 21),
('bx_market_entry_add', 'allow_vote_to', 2147483647, 1, 22),
('bx_market_entry_add', 'header_end_privacy', 2147483647, 1, 23),
('bx_market_entry_add', 'notes', 2147483647, 1, 24),
('bx_market_entry_add', 'location', 2147483647, 1, 25),
('bx_market_entry_add', 'do_submit', 2147483647, 0, 26),
('bx_market_entry_add', 'do_publish', 2147483647, 1, 27),

('bx_market_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_market_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_market_entry_edit', 'title', 2147483647, 1, 3),
('bx_market_entry_edit', 'name', 2147483647, 1, 4),
('bx_market_entry_edit', 'cat', 2147483647, 1, 5),
('bx_market_entry_edit', 'text', 2147483647, 1, 6),
('bx_market_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_market_entry_edit', 'files', 2147483647, 1, 8),
('bx_market_entry_edit', 'header_beg_single', 2147483647, 1, 9),
('bx_market_entry_edit', 'warning_single', 2147483647, 1, 10),
('bx_market_entry_edit', 'price_single', 2147483647, 1, 11),
('bx_market_entry_edit', 'header_end_single', 2147483647, 1, 12),
('bx_market_entry_edit', 'header_beg_recurring', 2147483647, 1, 13),
('bx_market_entry_edit', 'warning_recurring', 2147483647, 1, 14),
('bx_market_entry_edit', 'duration_recurring', 2147483647, 1, 15),
('bx_market_entry_edit', 'price_recurring', 2147483647, 1, 16),
('bx_market_entry_edit', 'trial_recurring', 2147483647, 1, 17),
('bx_market_entry_edit', 'header_end_recurring', 2147483647, 1, 18),
('bx_market_entry_edit', 'header_beg_privacy', 2147483647, 1, 19),
('bx_market_entry_edit', 'allow_view_to', 2147483647, 1, 20),
('bx_market_entry_edit', 'allow_purchase_to', 2147483647, 1, 21),
('bx_market_entry_edit', 'allow_comment_to', 2147483647, 1, 22),
('bx_market_entry_edit', 'allow_vote_to', 2147483647, 1, 23),
('bx_market_entry_edit', 'header_end_privacy', 2147483647, 1, 24),
('bx_market_entry_edit', 'notes', 2147483647, 1, 25),
('bx_market_entry_edit', 'location', 2147483647, 1, 26),
('bx_market_entry_edit', 'do_submit', 2147483647, 1, 27);


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`='bx_market';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_market';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_market', '1', '1', 'page.php?i=view-product&id={object_id}', 'bx_market_products', 'id', 'author', 'featured', '', '');