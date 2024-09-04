SET @sName = 'bx_massmailer';


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`=5 WHERE `object` IN ('bx_massmailer_create_campaign', 'bx_massmailer_edit_campaign');

UPDATE `sys_pages_blocks` SET `cell_id`=0 WHERE `object`='bx_massmailer_create_campaign' AND `title`='_bx_massmailer_page_block_title_attributes';
UPDATE `sys_pages_blocks` SET `cell_id`=1 WHERE `object`='bx_massmailer_create_campaign' AND `title`='_bx_massmailer_page_block_title_create_campaign';

UPDATE `sys_pages_blocks` SET `cell_id`=0 WHERE `object`='bx_massmailer_edit_campaign' AND `title`='_bx_massmailer_page_block_title_attributes';
UPDATE `sys_pages_blocks` SET `cell_id`=1 WHERE `object`='bx_massmailer_edit_campaign' AND `title`='_bx_massmailer_page_block_title_edit_campaign';
