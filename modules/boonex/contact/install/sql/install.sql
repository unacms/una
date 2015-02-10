SET @sName = 'bx_contact';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_contact_entries` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `email` varchar(128) collate utf8_unicode_ci NOT NULL,
  `subject` varchar(128) collate utf8_unicode_ci NOT NULL,
  `body` text collate utf8_unicode_ci NOT NULL,
  `uri` varchar(255) collate utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_contact_contact', @sName, '_bx_contact_form_contact', '', '', 'do_submit', 'bx_contact_entries', 'id', 'uri', 'subject', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_contact_contact_send', @sName, 'bx_contact_contact', '_bx_contact_form_contact_display_send', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_contact_contact', @sName, 'name', '', '', 0, 'text', '_bx_contact_form_contact_input_sys_name', '_bx_contact_form_contact_input_name', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:150;}', '_bx_contact_form_contact_input_name_err', 'Xss', '', 1, 0),
('bx_contact_contact', @sName, 'email', '', '', 0, 'text', '_bx_contact_form_contact_input_sys_email', '_bx_contact_form_contact_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_bx_contact_form_contact_input_email_err', 'Xss', '', 1, 0),
('bx_contact_contact', @sName, 'subject', '', '', 0, 'text', '_bx_contact_form_contact_input_sys_subject', '_bx_contact_form_contact_input_subject', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:5;s:3:"max";i:250;}', '_bx_contact_form_contact_input_subject_err', 'Xss', '', 1, 0),
('bx_contact_contact', @sName, 'body', '', '', 0, 'textarea', '_bx_contact_form_contact_input_sys_body', '_bx_contact_form_contact_input_body', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_contact_form_contact_input_body_err', 'Xss', '', 1, 0),
('bx_contact_contact', @sName, 'captcha', '', '', 0, 'captcha', '_bx_contact_form_contact_input_sys_captcha', '_bx_contact_form_contact_input_captcha', '', 1, 0, 0, '', '', '', 'Captcha', '', '_bx_contact_form_contact_input_captcha_err', '', '', 1, 0),
('bx_contact_contact', @sName, 'do_submit', '_bx_contact_form_contact_input_do_submit', '', 0, 'submit', '_bx_contact_form_contact_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_contact_contact_send', 'name', 2147483647, 1, 1),
('bx_contact_contact_send', 'email', 2147483647, 1, 2),
('bx_contact_contact_send', 'subject', 2147483647, 1, 3),
('bx_contact_contact_send', 'body', 2147483647, 1, 4),
('bx_contact_contact_send', 'captcha', 2147483647, 1, 5),
('bx_contact_contact_send', 'do_submit', 2147483647, 1, 6);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_contact', '_bx_contact', 'bx_contact@modules/boonex/contact/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_contact', '', 'bx_contact@modules/boonex/contact/|std-wi.png', '_bx_contact', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
