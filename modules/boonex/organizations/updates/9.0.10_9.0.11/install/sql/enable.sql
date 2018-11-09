-- MENUS
UPDATE `sys_menu_items` SET `title_system`='_bx_orgs_menu_item_title_system_become_fan' WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='profile-fan-add';
UPDATE `sys_menu_items` SET `title_system`='_bx_orgs_menu_item_title_system_leave_organization' WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='profile-fan-remove';
UPDATE `sys_menu_items` SET `title_system`='_bx_orgs_menu_item_title_system_subscribe' WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='profile-subscribe-add';
UPDATE `sys_menu_items` SET `title_system`='_bx_orgs_menu_item_title_system_unsubscribe' WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='profile-subscribe-remove';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='vote';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 215);


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_organizations' WHERE `name`='bx_organizations';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_organizations';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_organizations', 'bx_organizations_votes', 'bx_organizations_votes_track', '604800', '1', '1', '0', '1', 'bx_organizations_data', 'id', 'author', 'rate', 'votes', '', '');
