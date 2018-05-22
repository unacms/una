-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_persons' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_persons_per_page_browse_recommended';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_persons_per_page_browse_recommended', '10', @iCategId, '_sys_option_per_page_browse_recommended', 'digit', '', '', '', 16);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `module`='bx_persons' AND `title`='_bx_persons_page_block_title_recommended_entries_view_showcase';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 1, 'bx_persons', '_bx_persons_page_block_title_sys_recommended_entries_view_showcase', '_bx_persons_page_block_title_recommended_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:18:\"browse_recommended\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);


DELETE FROM `sys_pages_blocks` WHERE `object`<>'sys_home' AND `module`='bx_persons' AND `title`='_bx_persons_page_block_title_recommended_entries_view_showcase';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_persons', '_bx_persons_page_block_title_sys_recommended_entries_view_showcase', '_bx_persons_page_block_title_recommended_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:18:\"browse_recommended\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_persons';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons', 'bx_persons_scores', 'bx_persons_scores_track', '604800', '0', 'bx_persons_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `spaces`='' WHERE `object` IN ('bx_persons_allow_view_to');
