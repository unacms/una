-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_groups' AND `title` IN ('_bx_groups_page_block_title_recom_fans');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_groups', '_bx_groups_page_block_title_sys_recom_fans', '_bx_groups_page_block_title_recom_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:27:\"browse_recommendations_fans\";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_snippet_meta' AND `name`='ignore-join';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `hidden_on_cxt`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_groups_snippet_meta', 'bx_groups', 'ignore-join', '_sys_menu_item_title_system_sm_ignore', '_sys_menu_item_title_sm_ignore', '', '', '', '', '', 2147483647, '', 'all!recom_groups_fans', 1, 0, 1, 70);

UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_groups_fans' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_groups_fans' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `hidden_on_cxt`='recom_groups_fans' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='unsubscribe';


-- RECOMMENDATIONS
SET @iRecFans = (SELECT `id` FROM `sys_objects_recommendation` WHERE `name`='bx_groups_fans' LIMIT 1);
DELETE FROM `sys_objects_recommendation` WHERE `id`=@iRecFans;
DELETE FROM `sys_recommendation_criteria` WHERE `object_id`=@iRecFans AND `name` IN ('by_friends', 'by_subscriptions', 'by_fans');

INSERT INTO `sys_objects_recommendation` (`name`, `module`, `connection`, `content_info`, `countable`, `active`, `class_name`, `class_file`) VALUES
('bx_groups_fans', 'system', 'bx_groups_fans', '', 1, 1, 'BxGroupsRecommendationFans', 'modules/boonex/groups/classes/BxGroupsRecommendationFans.php');
SET @iRecFans = LAST_INSERT_ID();

INSERT INTO `sys_recommendation_criteria` (`object_id`, `name`, `source_type`, `source`, `params`, `weight`, `active`) VALUES
(@iRecFans, 'by_friends', 'sql', 'SELECT `tgf`.`content` AS `id`, SUM({points}) AS `value` FROM `sys_profiles_conn_friends` AS `tf` INNER JOIN `bx_groups_fans` AS `tgf` ON `tf`.`content`=`tgf`.`initiator` AND `tgf`.`content` NOT IN (SELECT `content` FROM `bx_groups_fans` WHERE `initiator`={profile_id} AND `mutual`=''1'') AND `tgf`.`mutual`=''1'' WHERE `tf`.`initiator`={profile_id} AND `tf`.`mutual`=''1'' GROUP BY `id`', 'a:1:{s:6:"points";i:2;}', 0.5, 1),
(@iRecFans, 'by_subscriptions', 'sql', 'SELECT `tgf`.`content` AS `id`, SUM({points}) AS `value` FROM `sys_profiles_conn_subscriptions` AS `ts` INNER JOIN `sys_profiles` AS `tp` ON `ts`.`content`=`tp`.`id` AND `tp`.`type` IN ({profile_types}) AND `tp`.`status`=''active'' INNER JOIN `bx_groups_fans` AS `tgf` ON `ts`.`content`=`tgf`.`initiator` AND `tgf`.`content` NOT IN (SELECT `content` FROM `bx_groups_fans` WHERE `initiator`={profile_id} AND `mutual`=''1'') AND `tgf`.`mutual`=''1'' WHERE `ts`.`initiator`={profile_id} GROUP BY `id`', 'a:2:{s:6:"points";i:2;s:13:"profile_types";s:0:"";}', 0.25, 1),
(@iRecFans, 'by_fans', 'sql', 'SELECT `tg2`.`content` AS `id`, SUM({points}) AS `value` FROM `bx_groups_fans` AS `tg1` INNER JOIN `bx_groups_fans` AS `tm` ON `tg1`.`content`=`tm`.`content` AND `tm`.`initiator`<>{profile_id} AND `tm`.`mutual`=''1'' INNER JOIN `bx_groups_fans` AS `tg2` ON `tm`.`initiator`=`tg2`.`initiator` AND `tg2`.`mutual`=''1'' AND `tg2`.`content` NOT IN (SELECT `content` FROM `bx_groups_fans` WHERE `initiator`={profile_id} AND `mutual`=''1'')  WHERE `tg1`.`initiator`={profile_id} AND `tg1`.`mutual`=''1'' GROUP BY `id`', 'a:1:{s:6:"points";i:1;}', 0.25, 1);
