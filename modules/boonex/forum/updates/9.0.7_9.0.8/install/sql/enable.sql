SET @sName = 'bx_forum';

-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=discussions-manage' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-discussions';
