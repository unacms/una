

ALTER TABLE `sys_cmts_ids` ADD `rate` float NOT NULL default '0';
ALTER TABLE `sys_cmts_ids` ADD `votes` int(11) NOT NULL default '0';


ALTER TABLE `sys_objects_transcoder` CHANGE  `source_type`  `source_type` ENUM(  'Folder',  'Storage',  'Proxy') NOT NULL;


ALTER TABLE `sys_transcoder_images_files` ADD `data` text NOT NULL;


-- can be safely applied multiple times


DELETE FROM `sys_options` WHERE `name` IN('main_div_width', 'sys_site_logo_width', 'sys_site_logo_height', 'sys_template_page_width_min', 'sys_template_page_width_max');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_site_logo_width', '_adm_stg_cpt_option_sys_site_logo_width', '240', 'digit', '', '', '', 23),
(@iCategoryId, 'sys_site_logo_height', '_adm_stg_cpt_option_sys_site_logo_height', '48', 'digit', '', '', '', 24);



DELETE FROM `sys_objects_vote` WHERE `Name` = 'sys_cmts';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('sys_cmts', 'sys_cmts_votes', 'sys_cmts_votes_track', '604800', '1', '1', '0', '1', 'sys_cmts_ids', 'id', 'rate', 'votes', '', '');



CREATE TABLE IF NOT EXISTS `sys_images_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);



CREATE TABLE IF NOT EXISTS `sys_cmts_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_votes_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;



UPDATE `sys_cron_jobs` SET `time` = '30 2 * * *' WHERE `name` = 'sys_upgrade_modules';



INSERT IGNORE INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_images_custom', 'Local', '', 360, 2592000, 0, 'sys_images_custom', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);



UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuAccount' WHERE `object` = 'sys_account';
UPDATE `sys_objects_menu` SET `template_id` = 6 WHERE `object` = 'sys_add_profile';
UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplCmtsMenuManage',  `template_id` = 15 WHERE `object` = 'sys_cmts_item_manage';
UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplCmtsMenuActions' WHERE `object` = 'sys_cmts_item_actions';



UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:21:"profile_notifications";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'account';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_links' AND `name` = 'studio';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_links', 'system', 'studio', '_sys_menu_item_title_system_studio', '_sys_menu_item_title_studio', '{studio_url}', '', '', 'wrench', '', 2147483646, 1, 0, 5);

UPDATE `sys_menu_items` SET `order` = 6 WHERE `set_name` = 'sys_account_links' AND `name` = 'logout';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-vote';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_actions', 'system', 'item-vote', '_sys_menu_item_title_system_cmts_item_vote', '_sys_menu_item_title_cmts_item_vote', 'javascript:void(0)', '', '', '', '', '', 2147483647, 1, 0, 0, 1);

UPDATE `sys_menu_items` SET  `editable` = 1 WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-reply';



INSERT IGNORE INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('sys_custom_images', 'sys_images', 'Storage', 'a:1:{s:6:"object";s:17:"sys_images_custom";}', 'no', '1', '2592000', '0', '', '');



DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'sys_custom_images' AND `filter` = 'ResizeVar';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('sys_custom_images', 'ResizeVar', '', '0');



-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A10' WHERE `version` = '8.0.0-A9' AND `name` = 'system';

