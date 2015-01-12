UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_persons_view_profile' AND `title`='_bx_persons_page_block_title_profile_actions';


DELETE FROM `sys_objects_metatags` WHERE `object`='bx_persons' LIMIT 1;
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_persons', 'bx_persons_meta_keywords', '', '', '', '');