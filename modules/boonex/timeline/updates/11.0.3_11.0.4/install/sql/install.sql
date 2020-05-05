SET @sName = 'bx_timeline';


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_timeline_post_view';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_timeline_post_view', @sName, 'bx_timeline_post', '_bx_timeline_form_post_display_view', 1);
