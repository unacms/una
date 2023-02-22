SET @sName = 'bx_channels';


-- MENUS
UPDATE `sys_menu_items` SET `title`='_bx_channels_menu_item_title_entries_manage' WHERE `set_name`='bx_channels_submenu' AND `name`='channels-administration';
