SET @sName = 'bx_invites';

-- TABLES
ALTER TABLE `bx_inv_requests` ADD `text` text collate utf8_unicode_ci NOT NULL AFTER `email`;

-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_invites_request' AND `name`='text';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_invites_request', @sName, 'text', '', '', 0, 'textarea', '_bx_invites_form_request_input_sys_text', '_bx_invites_form_request_input_text', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:10;s:3:"max";i:5000;}', '_bx_invites_form_request_input_text_err', 'Xss', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_invites_request_send' AND `input_name`='text';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_invites_request_send', 'text', 2147483647, 1, 3);

UPDATE `sys_form_display_inputs` SET `order`='4' WHERE `display_name`='bx_invites_request_send' AND `input_name`='do_submit';


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_invites_requests' AND `name`='name';
UPDATE `sys_grid_fields` SET `width`='23%' WHERE `object`='bx_invites_requests' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_invites_requests' AND `type`='single' AND `name`='info';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_invites_requests', 'single', 'info', '_bx_invites_grid_action_title_adm_info', 'exclamation-circle', 1, 0, 0);