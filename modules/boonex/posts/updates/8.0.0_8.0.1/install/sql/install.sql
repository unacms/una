CREATE TABLE IF NOT EXISTS `bx_posts_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'location', '', '', 0, 'custom', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_display_inputs` SET `order`='5' WHERE `display_name`='bx_posts_entry_add' AND `input_name`='allow_view_to' LIMIT 1;
INSERT IGNORE INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'location', 2147483647, 1, 6);

UPDATE `sys_form_display_inputs` SET `order`='5' WHERE `display_name`='bx_posts_entry_edit' AND `input_name`='allow_view_to' LIMIT 1;
INSERT IGNORE INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_edit', 'location', 2147483647, 1, 6);

UPDATE `sys_objects_cmts` SET `BaseUrl`='page.php?i=view-post&id={object_id}' WHERE `Name`='bx_posts' LIMIT 1;

UPDATE `sys_pages_blocks` SET `designbox_id`='3' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_author' LIMIT 1;
UPDATE `sys_pages_blocks` SET `designbox_id`='3' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_actions' LIMIT 1;
UPDATE `sys_pages_blocks` SET `designbox_id`='3' WHERE `object`='bx_posts_author' AND `title`='_bx_posts_page_block_title_entries_actions' LIMIT 1;

DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_posts_cmts';
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES 
('bx_posts_cmts', '_bx_posts_cmts', 'BxPostsCmtsSearchResult', 'modules/boonex/posts/classes/BxPostsCmtsSearchResult.php');

UPDATE `sys_objects_metatags` SET `table_locations`='bx_posts_meta_locations' WHERE `object`='bx_posts' LIMIT 1;
