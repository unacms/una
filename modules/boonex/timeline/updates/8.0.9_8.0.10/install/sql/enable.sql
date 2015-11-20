-- MENUS
UPDATE `sys_menu_items` SET `submenu_popup`='0' WHERE `set_name`='bx_timeline_menu_item_actions';
UPDATE `sys_menu_items` SET `submenu_popup`='1' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_timeline_privacy_view' LIMIT 1;
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_privacy_view', 'bx_timeline', 'view', '_bx_timeline_privacy_view', '3', 'bx_timeline_events', 'id', 'owner_id', 'BxTimelinePrivacy', 'modules/boonex/timeline/classes/BxTimelinePrivacy.php');