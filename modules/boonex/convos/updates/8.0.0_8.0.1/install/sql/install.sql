UPDATE `sys_pages_blocks` SET `designbox_id`='11', `content`='a:3:{s:6:"module";s:9:"bx_convos";s:6:"method";s:23:"conversations_in_folder";s:6:"params";a:1:{i:0;s:11:"{folder_id}";}}' WHERE `object`='bx_convos_home' AND `title`='_bx_cnv_page_block_title_folder' LIMIT 1;

UPDATE `sys_objects_cmts` SET `BaseUrl`='page.php?i=view-convo&id={object_id}' WHERE `Name`='bx_convos' LIMIT 1;