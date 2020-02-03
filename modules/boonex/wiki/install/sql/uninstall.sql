


-- Delete WIKI content

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_wiki';

DELETE FROM `wb`, `wc`, `wp`
USING `sys_pages_blocks` AS `wb`, `sys_pages_wiki_blocks` AS `wc`, `sys_objects_page` AS `wp`
WHERE `wb`.`id` = `wc`.`block_id` AND `wp`.`object` = `wb`.`object` AND `wp`.`module` = 'bx_wiki';

DELETE FROM `wb`, `wc`
USING `sys_pages_blocks` AS `wb`, `sys_pages_wiki_blocks` AS `wc`
WHERE `wb`.`id` = `wc`.`block_id` AND `wb`.`module` = 'bx_wiki';

-- Studio page and widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_wiki';

