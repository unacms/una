SET @sName = 'bx_notifications';

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_notifications_privacy_view' LIMIT 1;
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_notifications_privacy_view', @sName, 'view', '_bx_notifications_privacy_view', '3', 'bx_notifications_events', 'id', 'owner_id', 'BxNtfsPrivacy', 'modules/boonex/notifications/classes/BxNtfsPrivacy.php');