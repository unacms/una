-- MENUS
UPDATE `sys_menu_items` SET `title_system`='_bx_market_menu_item_title_system_view_entries_in_context' WHERE `module`='bx_market' AND `title_system`='_bx__market_menu_item_title_system_view_entries_in_context';


-- PRIVACY
UPDATE `sys_objects_privacy` SET `spaces`='all' WHERE `object`='bx_market_allow_view_to';
UPDATE `sys_objects_privacy` SET `spaces`='' WHERE `object` IN ('bx_market_allow_purchase_to', 'bx_market_allow_comment_to', 'bx_market_allow_vote_to');
