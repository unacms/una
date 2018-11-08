-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions_all' AND `name`='vote';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_actions_all', 'bx_persons', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 215);


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_persons' WHERE `name`='bx_persons';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_persons';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_persons', 'bx_persons_votes', 'bx_persons_votes_track', '604800', '1', '1', '0', '1', 'bx_persons_data', 'id', 'author', 'rate', 'votes', '', '');
