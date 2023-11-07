SET @sName = 'bx_invites';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_inv_invites` (
  `id` int(11) NOT NULL auto_increment,
  `account_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `key` varchar(128) NOT NULL,
  `redirect` varchar(255) NOT NULL default '',
  `email` varchar(128) NOT NULL,
  `date` int(11) NOT NULL default '0',
  `date_seen` int(11) DEFAULT NULL,
  `date_joined` int(11) DEFAULT NULL,
  `joined_account_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  INDEX `bx_inv_invites_request_id` (`request_id`)
);

CREATE TABLE IF NOT EXISTS `bx_inv_requests` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(128) NOT NULL,
  `text` text NOT NULL,
  `nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `status` TINYINT(4) DEFAULT '0',
  PRIMARY KEY  (`id`)
);

-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_invite', @sName, '_bx_invites_form_invite', '', '', 'ifi_do_submit', 'bx_inv_invites', 'id', '', '', 'a:1:{s:14:"checker_helper";s:22:"BxInvFormCheckerHelper";}', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_invites_invite_send', @sName, 'bx_invites_invite', '_bx_invites_form_invite_display_send', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_invites_invite', @sName, 'emails', '', '', 0, 'textarea', '_bx_invites_form_invite_input_sys_emails', '_bx_invites_form_invite_input_emails', '_bx_invites_form_invite_input_emails_inf', 1, 0, 0, '', '', '', 'Emails', '', '_bx_invites_form_invite_input_emails_err', '', '', 0, 0),
('bx_invites_invite', @sName, 'text', '', '', 0, 'textarea', '_bx_invites_form_invite_input_sys_text', '_bx_invites_form_invite_input_text', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_invites_form_invite_input_text_err', '', '', 0, 0),
('bx_invites_invite', @sName, 'ifi_do_submit', '_bx_invites_form_invite_input_do_submit', '', 0, 'submit', '_bx_invites_form_invite_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_invites_invite_send', 'emails', 2147483647, 1, 1),
('bx_invites_invite_send', 'text', 2147483647, 1, 2),
('bx_invites_invite_send', 'ifi_do_submit', 2147483647, 1, 3);


INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_request', @sName, '_bx_invites_form_request', '', '', 'ifr_do_submit', 'bx_inv_requests', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_invites_request_send', @sName, 'bx_invites_request', '_bx_invites_form_request_display_send', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_invites_request', @sName, 'name', '', '', 0, 'text', '_bx_invites_form_request_input_sys_name', '_bx_invites_form_request_input_name', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:150;}', '_bx_invites_form_request_input_name_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'email', '', '', 0, 'text', '_bx_invites_form_request_input_sys_email', '_bx_invites_form_request_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_bx_invites_form_request_input_email_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'text', '', '', 0, 'textarea', '_bx_invites_form_request_input_sys_text', '_bx_invites_form_request_input_text', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_invites_form_request_input_text_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'ifr_do_submit', '_bx_invites_form_request_input_do_submit', '', 0, 'submit', '_bx_invites_form_request_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_invites_request_send', 'name', 2147483647, 1, 1),
('bx_invites_request_send', 'email', 2147483647, 1, 2),
('bx_invites_request_send', 'text', 2147483647, 1, 3),
('bx_invites_request_send', 'ifr_do_submit', 2147483647, 1, 4);

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_invites', '_bx_invites', 'bx_invites@modules/boonex/invites/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, 'extensions', '{url_studio}module.php?name=bx_invites', '', 'bx_invites@modules/boonex/invites/|std-icon.svg', '_bx_invites', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
