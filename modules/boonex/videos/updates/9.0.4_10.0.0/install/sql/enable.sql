-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_videos_view_actions' AND `name` IN ('reaction', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_videos_view_actions', 'bx_videos', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_videos_view_actions' AND `name`='vote';

UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='videos-administration' AND `icon`='';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_videos' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_videos_video_mp4' AND `action`='transcoded' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_videos_video_mp4', 'transcoded', @iHandler);
