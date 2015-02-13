SET @sName = 'bx_invites';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_inv_invites` (
  `id` int(11) NOT NULL auto_increment,
  `account_id` int(11) collate utf8_unicode_ci NOT NULL,
  `profile_id` int(11) collate utf8_unicode_ci NOT NULL,
  `email` varchar(128) collate utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_inv_requests` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `email` varchar(128) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_invite', @sName, '_bx_invites_form_invite', '', '', 'do_submit', 'bx_inv_invites', 'id', '', '', 'a:1:{s:14:"checker_helper";s:22:"BxInvFormCheckerHelper";}', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_invites_invite_send', @sName, 'bx_invites_invite', '_bx_invites_form_invite_display_send', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_invites_invite', @sName, 'emails', '', '', 0, 'textarea', '_bx_invites_form_invite_input_sys_emails', '_bx_invites_form_invite_input_emails', '_bx_invites_form_invite_input_emails_inf', 1, 0, 0, '', '', '', 'Emails', '', '_bx_invites_form_invite_input_emails_err', '', '', 0, 0),
('bx_invites_invite', @sName, 'text', '', '', 0, 'textarea', '_bx_invites_form_invite_input_sys_text', '_bx_invites_form_invite_input_text', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_invites_form_invite_input_text_err', '', '', 0, 0),
('bx_invites_invite', @sName, 'do_submit', '_bx_invites_form_invite_input_do_submit', '', 0, 'submit', '_bx_invites_form_invite_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_invites_invite_send', 'emails', 2147483647, 1, 1),
('bx_invites_invite_send', 'text', 2147483647, 1, 2),
('bx_invites_invite_send', 'do_submit', 2147483647, 1, 3);


INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_request', @sName, '_bx_invites_form_request', '', '', 'do_submit', 'bx_inv_requests', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_invites_request_send', @sName, 'bx_invites_request', '_bx_invites_form_request_display_send', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_invites_request', @sName, 'name', '', '', 0, 'text', '_bx_invites_form_request_input_sys_name', '_bx_invites_form_request_input_name', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:150;}', '_bx_invites_form_request_input_name_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'email', '', '', 0, 'text', '_bx_invites_form_request_input_sys_email', '_bx_invites_form_request_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_bx_invites_form_request_input_email_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'text', '', '', 0, 'textarea', '_bx_invites_form_request_input_sys_text', '_bx_invites_form_request_input_text', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_invites_form_request_input_text_err', 'Xss', '', 0, 0),
('bx_invites_request', @sName, 'do_submit', '_bx_invites_form_request_input_do_submit', '', 0, 'submit', '_bx_invites_form_request_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_invites_request_send', 'name', 2147483647, 1, 1),
('bx_invites_request_send', 'email', 2147483647, 1, 2),
('bx_invites_request_send', 'text', 2147483647, 1, 3),
('bx_invites_request_send', 'do_submit', 2147483647, 1, 4);


-- GRIDS
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_invites_requests', 'Sql', 'SELECT * FROM `bx_inv_requests` WHERE 1 ', 'bx_inv_requests', 'id', '', '', '', 20, NULL, 'start', '', 'name,email', '', 'like', '', '', 'BxInvGridRequests', 'modules/boonex/invites/classes/BxInvGridRequests.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_invites_requests', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_invites_requests', 'name', '_bx_invites_grid_column_title_name', '20%', 0, '', '', 2),
('bx_invites_requests', 'email', '_bx_invites_grid_column_title_email', '20%', 1, '25', '', 3),
('bx_invites_requests', 'nip', '_bx_invites_grid_column_title_nip', '15%', 0, '15', '', 4),
('bx_invites_requests', 'date', '_bx_invites_grid_column_title_date', '20%', 0, '20', '', 5),
('bx_invites_requests', 'actions', '', '23%', 0, '', '', 6);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_invites_requests', 'bulk', 'invite', '_bx_invites_grid_action_title_adm_invite', '', 0, 0, 1),
('bx_invites_requests', 'bulk', 'delete', '_bx_invites_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_invites_requests', 'single', 'info', '_bx_invites_grid_action_title_adm_info', 'exclamation-circle', 1, 0, 1),
('bx_invites_requests', 'single', 'invite', '_bx_invites_grid_action_title_adm_invite', 'envelope', 1, 0, 2),
('bx_invites_requests', 'single', 'delete', '_bx_invites_grid_action_title_adm_delete', 'remove', 1, 1, 3);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_invites', '_bx_invites', 'bx_invites@modules/boonex/invites/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_invites', '', 'bx_invites@modules/boonex/invites/|std-wi.png', '_bx_invites', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
