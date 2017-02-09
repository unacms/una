-- PAGE
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_home' AND `title` IN ('_bx_orgs_page_block_title_featured_profiles');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_organizations_home', 1, 'bx_organizations', '_bx_orgs_page_block_title_featured_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 0);


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='author' WHERE `name`='bx_organizations';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_organizations', '1', '1', 'page.php?i=view-organization-profile&id={object_id}', 'bx_organizations_data', 'id', 'author', 'featured', '', '');


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`org_name` AS `fullname`, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_organizations_administration';
UPDATE `sys_objects_grid` SET `source`='SELECT `td`.*, `td`.`org_name` AS `fullname`, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ' WHERE `object`='bx_organizations_common';

UPDATE `sys_grid_fields` SET `name`='fullname', `title`='_bx_orgs_grid_column_title_adm_fullname' WHERE `object`='bx_organizations_administration' AND `name`='org_name';
UPDATE `sys_grid_fields` SET `name`='fullname', `title`='_bx_orgs_grid_column_title_adm_fullname' WHERE `object`='bx_organizations_common' AND `name`='org_name';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_organizations' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='sys_profiles_friends' AND `action`='connection_added' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_profiles_friends', 'connection_added', @iHandler);

UPDATE `sys_alerts` SET `action`='timeline_repost' WHERE `unit`='bx_organizations' AND `action`='timeline_share';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_organizations_friend_request';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_organizations', '_bx_organizations_email_friend_request', 'bx_organizations_friend_request', '_bx_organizations_email_friend_request_subject', '_bx_organizations_email_friend_request_body');