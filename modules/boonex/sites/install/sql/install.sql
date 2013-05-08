-- TABLES
CREATE TABLE IF NOT EXISTS `bx_sites_owners` (
  `id` int(11) NOT NULL default '0',
  `trials` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_sites_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `status` enum('unconfirmed','pending','trial','active','canceled','suspended') NOT NULL default 'unconfirmed',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
);

CREATE TABLE IF NOT EXISTS `bx_sites_payment_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `type` enum('paypal') NOT NULL,
  `token` varchar(32) NOT NULL,
  `profile_id` varchar(255) NOT NULL,
  `profile_sid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `bx_sites_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `type` enum('init','trial','regular') NOT NULL default 'regular',
  `transaction` varchar(64) NOT NULL,
  `amount` float NOT NULL default '0',
  `when` int(11) NOT NULL,
  `when_next` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_sites', 'bx_sites', '_bx_sites_form_site', '', '', 'bx_sites_accounts', 'id', '', '', 'do_submit', '', 0, 1, '', '');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_sites', 'bx_sites_site_add', 'bx_sites', 0, '_bx_sites_form_site_display_add'),
('bx_sites', 'bx_sites_site_edit', 'bx_sites', 0, '_bx_sites_form_site_display_edit'),
('bx_sites', 'bx_sites_site_confirm', 'bx_sites', 0, '_bx_sites_form_site_display_confirm'),
('bx_sites', 'bx_sites_site_pending', 'bx_sites', 0, '_bx_sites_form_site_display_pending'),
('bx_sites', 'bx_sites_site_cancel', 'bx_sites', 0, '_bx_sites_form_site_display_cancel'),
('bx_sites', 'bx_sites_site_reactivate', 'bx_sites', 0, '_bx_sites_form_site_display_reactivate'),
('bx_sites', 'bx_sites_site_suspended', 'bx_sites', 0, '_bx_sites_form_site_display_suspended'),
('bx_sites', 'bx_sites_site_delete', 'bx_sites', 0, '_bx_sites_form_site_display_delete'),
('bx_sites', 'bx_sites_site_view', 'bx_sites', 1, '_bx_sites_form_site_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_sites', 'bx_sites', 'id', '', '', 0, 'hidden', '_bx_sites_form_site_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_sites', 'bx_sites', 'domain', '', '', 0, 'text', '_bx_sites_form_site_input_sys_domain', '_bx_sites_form_site_input_domain', '_bx_sites_form_site_input_domain_info', 1, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:17:"/^[a-z0-9-]{3,}$/";}', '_bx_sites_form_site_input_domain_err', 'Xss', '', 1, 0),
('bx_sites', 'bx_sites', 'email', '', '', 0, 'text', '_bx_sites_form_site_input_sys_email', '_bx_sites_form_site_input_email', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_sites_form_site_input_email_err', 'Xss', '', 1, 0),
('bx_sites', 'bx_sites', 'title', '', '', 0, 'text', '_bx_sites_form_site_input_sys_title', '_bx_sites_form_site_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_sites_form_site_input_title_err', 'Xss', '', 1, 0),
('bx_sites', 'bx_sites', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_sites_form_site_input_sys_delete_confirm', '_bx_sites_form_site_input_delete_confirm', '_bx_sites_form_site_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_sites_form_site_input_delete_confirm_error', '', '', 1, 0),
('bx_sites', 'bx_sites', 'info', '', '', 0, 'custom', '_bx_sites_form_site_input_sys_info', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'submit_block', '', 'do_submit,do_close', 0, 'input_set', '_bx_sites_form_site_input_submit_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_sites', 'bx_sites', 'do_submit', '_bx_sites_form_site_input_do_submit', '', 0, 'submit', '_bx_sites_form_site_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'confirm_block', '', 'do_confirm,do_delete,do_close', 0, 'input_set', '_bx_sites_form_site_input_confirm_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_sites', 'bx_sites', 'do_confirm', '_bx_sites_form_site_input_do_confirm', '', 0, 'submit', '_bx_sites_form_site_input_sys_do_confirm', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'do_delete', '_bx_sites_form_site_input_do_delete', '', 0, 'submit', '_bx_sites_form_site_input_sys_do_delete', '', '', 0, 0, 0, 'a:1:{s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'cancel_block', '', 'do_cancel,do_close', 0, 'input_set', '_bx_sites_form_site_input_cancel_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_sites', 'bx_sites', 'do_cancel', '_bx_sites_form_site_input_do_cancel', '', 0, 'submit', '_bx_sites_form_site_input_sys_do_cancel', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'reactivate_block', '', 'do_reactivate,do_close', 0, 'input_set', '_bx_sites_form_site_input_reactivate_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_sites', 'bx_sites', 'do_reactivate', '_bx_sites_form_site_input_do_reactivate', '', 0, 'submit', '_bx_sites_form_site_input_sys_do_reactivate', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_sites', 'bx_sites', 'do_close', '_bx_sites_form_site_input_do_close', '', 0, 'button', '_bx_sites_form_site_input_sys_do_close', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_sites_site_add', 'domain', 2147483647, 1, 1),
('bx_sites_site_add', 'submit_block', 2147483647, 1, 2),
('bx_sites_site_add', 'do_submit', 2147483647, 1, 3),
('bx_sites_site_add', 'do_close', 2147483647, 1, 4),

('bx_sites_site_edit', 'domain', 2147483647, 1, 1),
('bx_sites_site_edit', 'email', 2147483647, 1, 2),
('bx_sites_site_edit', 'title', 2147483647, 1, 3),
('bx_sites_site_edit', 'do_submit', 2147483647, 1, 4),

('bx_sites_site_confirm', 'id', 2147483647, 1, 1),
('bx_sites_site_confirm', 'info', 2147483647, 1, 2),
('bx_sites_site_confirm', 'confirm_block', 2147483647, 1, 3),
('bx_sites_site_confirm', 'do_confirm', 2147483647, 1, 4),
('bx_sites_site_confirm', 'do_delete', 2147483647, 1, 5),
('bx_sites_site_confirm', 'do_close', 2147483647, 1, 6),

('bx_sites_site_pending', 'id', 2147483647, 1, 1),
('bx_sites_site_pending', 'info', 2147483647, 1, 2),
('bx_sites_site_pending', 'do_close', 2147483647, 1, 3),

('bx_sites_site_cancel', 'id', 2147483647, 1, 1),
('bx_sites_site_cancel', 'info', 2147483647, 1, 2),
('bx_sites_site_cancel', 'cancel_block', 2147483647, 1, 3),
('bx_sites_site_cancel', 'do_cancel', 2147483647, 1, 4),
('bx_sites_site_cancel', 'do_close', 2147483647, 1, 5),

('bx_sites_site_reactivate', 'id', 2147483647, 1, 1),
('bx_sites_site_reactivate', 'info', 2147483647, 1, 2),
('bx_sites_site_reactivate', 'reactivate_block', 2147483647, 1, 3),
('bx_sites_site_reactivate', 'do_reactivate', 2147483647, 1, 4),
('bx_sites_site_reactivate', 'do_close', 2147483647, 1, 5),

('bx_sites_site_suspended', 'id', 2147483647, 1, 1),
('bx_sites_site_suspended', 'info', 2147483647, 1, 2),
('bx_sites_site_suspended', 'do_close', 2147483647, 1, 3),

('bx_sites_site_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_sites_site_delete', 'do_submit', 2147483647, 1, 2);




-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_sites', '_bx_sites', '_bx_sites', 'bx_sites@modules/boonex/sites/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_sites', '{url_studio}module.php?name=bx_sites', '', 'bx_sites@modules/boonex/sites/|std-wi.png', '_bx_sites', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

