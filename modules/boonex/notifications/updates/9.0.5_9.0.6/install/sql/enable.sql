SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_notifications_events_hide', 'bx_notifications_events_hide_site', 'bx_notifications_events_hide_email', 'bx_notifications_events_hide_push');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_events_hide_site', '', @iCategId, '_bx_ntfs_option_events_hide_site', 'rlist', '', '', '', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:21:"get_actions_checklist";}', 10),
('bx_notifications_events_hide_email', '', @iCategId, '_bx_ntfs_option_events_hide_email', 'rlist', '', '', '', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:21:"get_actions_checklist";}', 11),
('bx_notifications_events_hide_push', '', @iCategId, '_bx_ntfs_option_events_hide_push', 'rlist', '', '', '', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:21:"get_actions_checklist";}', 12);


-- PAGES
UPDATE `sys_pages_blocks` SET `visible_for_levels`='2147483644' WHERE `object`='sys_dashboard' AND `title`='_bx_ntfs_page_block_title_view';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module`=@sName AND `Name`='bx_notifications_new_event';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
(@sName, '_bx_ntfs_email_new_event', 'bx_notifications_new_event', '_bx_ntfs_email_new_event_subject', '_bx_ntfs_email_new_event_body');
