SET @sName = 'bx_organizations';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_site' AND `name`='organizations-home' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_homepage' AND `name`='organizations-home' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_add_profile_links' AND `name`='create-organization-profile' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='view-organization-profile' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-friend-requests' AND `icon`='briefcase col-red2';
UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-organizations' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='sys_profile_followings' AND `name`='organizations' AND `icon`='briefcase col-red2';

UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='organizations-administration' AND `icon`='briefcase';

UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-organizations' AND `icon`='briefcase col-red2';
UPDATE `sys_menu_items` SET `icon`='building col-red2' WHERE `module`='bx_organizations' AND `name`='joined-organizations' AND `icon`='briefcase col-red2';

-- STATS
UPDATE `sys_statistics` SET `icon`='building col-red2' WHERE `module`='bx_organizations' AND `name`='bx_organizations' AND `icon`='briefcase col-red2';
