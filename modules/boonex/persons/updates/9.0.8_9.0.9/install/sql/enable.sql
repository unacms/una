-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_persons' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_persons_enable_profile_activation_letter';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_persons_enable_profile_activation_letter', 'on', @iCategId, '_bx_persons_option_enable_profile_activation_letter', 'checkbox', '', '', '', 32);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_active_profiles\";s:6:"params";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:1;s:13:\"ajax_paginate\";b:1;}}' WHERE `object`='bx_persons_active' AND `title`='_bx_persons_page_block_title_active_profiles';

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_persons' AND `title`='_bx_persons_page_block_title_active_entries_view_showcase';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_persons', '_bx_persons_page_block_title_sys_active_entries_view_showcase', '_bx_persons_page_block_title_active_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_active_profiles\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:16:\"showcase_wo_info\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- GRIDS
UPDATE `sys_objects_grid` SET `sorting_fields`='reports' WHERE `object`='bx_persons_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_persons_administration' AND `name`='reports';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_persons_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_persons_administration' AND `name`='account';
