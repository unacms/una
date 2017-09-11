SET @sName = 'bx_forum';


-- PAGES
UPDATE `sys_pages_blocks` SET `title`='_bx_forum_page_block_title_featured_entries' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_featured_entries_view_extended';

UPDATE `sys_pages_blocks` SET `title`='_bx_forum_page_block_title_new_entries' WHERE `object`='sys_home' AND `title`='_bx_forum_page_block_title_latest_entries';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_forum_simple', 'bx_forum_html5');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_simple', 1, 'BxForumUploaderSimple', 'modules/boonex/forum/classes/BxForumUploaderSimple.php'),
('bx_forum_html5', 1, 'BxForumUploaderHTML5', 'modules/boonex/forum/classes/BxForumUploaderHTML5.php');
