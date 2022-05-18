SET @sName = 'bx_timeline';


-- MENUS
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_timeline_menu_item_counters' AND `name`='item-repost';
