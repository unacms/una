SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_notifications_owner_name_chars', 'bx_notifications_content_chars', 'bx_notifications_push_message_chars');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_owner_name_chars', '21', @iCategId, '_bx_ntfs_option_owner_name_chars', 'digit', '', '', '', '', 50),
('bx_notifications_content_chars', '32', @iCategId, '_bx_ntfs_option_content_chars', 'digit', '', '', '', '', 51),
('bx_notifications_push_message_chars', '190', @iCategId, '_bx_ntfs_option_push_message_chars', 'digit', '', '', '', '', 53);
