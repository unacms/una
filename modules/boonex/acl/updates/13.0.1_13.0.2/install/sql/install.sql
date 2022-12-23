SET @sName = 'bx_acl';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_acl_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `price_id` int(11) unsigned NOT NULL default '0',
  `type` varchar(16) NOT NULL default 'single',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `price_id` (`price_id`, `profile_id`),
  KEY `license` (`license`)
);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_acl_price' AND `name`='immediate';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'immediate', 1, '', 1, 'switcher', '_bx_acl_form_price_input_sys_immediate', '_bx_acl_form_price_input_immediate', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_acl_price_add', 'bx_acl_price_edit') AND `input_name`='immediate';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_add', 'immediate', 2147483647, 1, 7),
('bx_acl_price_edit', 'immediate', 2147483647, 1, 7);
