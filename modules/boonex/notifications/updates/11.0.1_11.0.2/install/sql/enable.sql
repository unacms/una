SET @sName = 'bx_notifications';


-- PAGES
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_dashboard' AND `title`='_bx_ntfs_page_block_title_view';
