-- PAGES
UPDATE `sys_pages_blocks` SET `tabs`='0' WHERE `object`='bx_donations_list' AND `title`='_bx_donations_page_block_title_list';

UPDATE `sys_pages_blocks` SET `tabs`='0' WHERE `object`='bx_donations_list_all' AND `title`='_bx_donations_page_block_title_list_all';

UPDATE `sys_objects_menu` SET `template_id`='26' WHERE `object`='bx_donations_list_submenu';
