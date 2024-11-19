SET @sName = 'bx_reputation';


-- TABLE: handlers
CREATE TABLE IF NOT EXISTS `bx_reputation_handlers` (
  `id` int(11) NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `type` enum('insert','update','delete') NOT NULL DEFAULT 'insert',
  `alert_unit` varchar(64) NOT NULL default '',
  `alert_action` varchar(64) NOT NULL default '',
  `points_active` int(11) NOT NULL default '0',
  `points_passive` int(11) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY (`id`),
  UNIQUE `alert` (`alert_unit`, `alert_action`)
);

-- TABLE: profiles
CREATE TABLE IF NOT EXISTS `bx_reputation_profiles` (
  `id` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);


-- FORMS: handler
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_reputation_handler', @sName, '_bx_reputation_form_handler', '', '', 'do_submit', 'bx_reputation_handlers', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_reputation_handler_edit', @sName, 'bx_reputation_handler', '_bx_reputation_form_handler_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_reputation_handler', @sName, 'points_active', '', '', 0, 'text', '_bx_reputation_form_handler_input_sys_points_active', '_bx_reputation_form_handler_input_points_active', '_bx_reputation_form_handler_input_points_active_info', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_reputation_handler', @sName, 'points_passive', '', '', 0, 'text', '_bx_reputation_form_handler_input_sys_points_passive', '_bx_reputation_form_handler_input_points_passive', '_bx_reputation_form_handler_input_points_passive_info', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_reputation_handler', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '_bx_reputation_form_handler_input_sys_controls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_reputation_handler', @sName, 'do_submit', '_bx_reputation_form_handler_input_do_submit', '', 0, 'submit', '_bx_reputation_form_handler_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_reputation_handler', @sName, 'do_cancel', '_bx_reputation_form_handler_input_do_cancel', '', 0, 'button', '_bx_reputation_form_handler_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_reputation_handler_edit', 'points_active', 2147483647, 1, 1),
('bx_reputation_handler_edit', 'points_passive', 2147483647, 1, 2),
('bx_reputation_handler_edit', 'controls', 2147483647, 1, 3),
('bx_reputation_handler_edit', 'do_submit', 2147483647, 1, 4),
('bx_reputation_handler_edit', 'do_cancel', 2147483647, 1, 5);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_reputation', '_bx_reputation', 'bx_reputation@modules/boonex/reputation/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_reputation', '', 'bx_reputation@modules/boonex/reputation/|std-icon.svg', '_bx_reputation', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
