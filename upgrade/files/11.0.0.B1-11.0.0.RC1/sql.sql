
-- Structure

ALTER TABLE  `sys_pages_blocks` CHANGE  `type`  `type` ENUM('raw',  'html',  'lang',  'image',  'rss',  'menu',  'custom',  'service',  'wiki') NOT NULL DEFAULT  'raw';

-- Settings

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_player_default_quality' WHERE `name` = 'sys_player_default_format';

UPDATE `sys_options` SET `extra` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_memberships";s:6:"params";a:0:{}s:5:"class";s:18:"TemplAuditServices";}' WHERE `name` = 'sys_audit_acl_levels';

-- ACL

UPDATE `sys_acl_actions` SET `Desc` = '_sys_acl_action_switch_to_any_profile_desc' WHERE `Module` = 'system' AND `Name` = 'switch to any profile';

-- Pages blocks

DELETE FROM `sys_pages_blocks` WHERE `type` = 'custom';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'skeletons', '_sys_block_type_custom', 11, 2147483647, 'custom', '', 0, 1, 1, 0);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.0-RC1' WHERE (`version` = '11.0.0.B1' OR `version` = '11.0.0-B1') AND `name` = 'system';
