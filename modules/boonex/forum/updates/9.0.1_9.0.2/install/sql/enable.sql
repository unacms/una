SET @sName = 'bx_forum';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_home' AND `title` IN ('_bx_forum_page_block_title_featured_entries_view_extended');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_forum_home', 1, @sName, '', '_bx_forum_page_block_title_featured_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_featured";s:6:"params";a:4:{i:0;s:5:"table";i:1;b:1;i:2;b:1;i:3;b:0;}}', 0, 1, 1, 0);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_forum_feature';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_feature', 'Sql', 'SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` AS `cmt_text` FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s', 'bx_forum_discussions', 'id', 'lr_timestamp', '', 10, NULL, 'start', '', 'title,text,cmt_text', 'auto', '', 2147483647, 'BxForumGrid', 'modules/boonex/forum/classes/BxForumGrid.php');


DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_feature';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_feature', 'author', '', '10%', '', 1),
('bx_forum_feature', 'lr_timestamp', '', '85%', '', 2),
('bx_forum_feature', 'comments', '', '5%', '', 3);


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`=@sName;


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`=@sName;
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
(@sName, '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'featured', '', '');