UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_organizations' LIMIT 1;


-- TABLES
ALTER TABLE `bx_organizations_data` CHANGE `allow_view_to` `allow_view_to` VARCHAR( 16 ) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS `bx_organizations_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_organizations_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_profile_id` int(10) unsigned NOT NULL,
  `fan_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin` (`group_profile_id`,`fan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- FORMS
DELETE FROM `sys_form_displays` WHERE `object`='bx_organization' AND `display_name`='bx_organization_invite';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_organization', 'bx_organization_invite', 'bx_organizations', 0, '_bx_orgs_form_profile_display_invite');

UPDATE `sys_form_inputs` SET `info`='_bx_orgs_form_profile_input_allow_view_to_info' WHERE `object`='bx_organization' AND `name`='allow_view_to';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name` IN ('initial_members', 'join_confirmation');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'initial_members', '', '', 0, 'custom', '_bx_orgs_form_profile_input_sys_initial_members', '_bx_orgs_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_organization', 'bx_organizations', 'join_confirmation', 1, '', 1, 'switcher', '_bx_orgs_form_profile_input_sys_join_confirm', '_bx_orgs_form_profile_input_join_confirm', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_organization_add', 'bx_organization_invite', 'bx_organization_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_organization_add', 'initial_members', 2147483647, 1, 1),
('bx_organization_add', 'picture', 2147483647, 1, 2),
('bx_organization_add', 'org_name', 2147483647, 1, 3),
('bx_organization_add', 'org_cat', 2147483647, 1, 4),
('bx_organization_add', 'org_desc', 2147483647, 1, 5),
('bx_organization_add', 'location', 2147483647, 1, 6),
('bx_organization_add', 'join_confirmation', 2147483647, 1, 7),
('bx_organization_add', 'allow_view_to', 2147483647, 1, 8),
('bx_organization_add', 'do_submit', 2147483647, 1, 9),

('bx_organization_invite', 'initial_members', 2147483647, 1, 1),
('bx_organization_invite', 'do_submit', 2147483647, 1, 2),

('bx_organization_edit', 'picture', 2147483647, 1, 1),
('bx_organization_edit', 'org_name', 2147483647, 1, 2),
('bx_organization_edit', 'org_cat', 2147483647, 1, 3),
('bx_organization_edit', 'org_desc', 2147483647, 1, 4),
('bx_organization_edit', 'location', 2147483647, 1, 5),
('bx_organization_edit', 'join_confirmation', 2147483647, 1, 6),
('bx_organization_edit', 'allow_view_to', 2147483647, 1, 7),
('bx_organization_edit', 'do_submit', 2147483647, 1, 8);
