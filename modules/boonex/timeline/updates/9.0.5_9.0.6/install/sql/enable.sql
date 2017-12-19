SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_pages_blocks` SET `visible_for_levels`='2147483644' WHERE `object`='sys_dashboard' AND `title` IN ('_bx_timeline_page_block_title_post_account', '_bx_timeline_page_block_title_view_account', '_bx_timeline_page_block_title_view_account_outline');


-- MENUS
UPDATE `sys_menu_items` SET `title`='_bx_timeline_menu_item_title_item_share' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-share';
UPDATE `sys_menu_items` SET `title`='_bx_timeline_menu_item_title_item_more' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';
