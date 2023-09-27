
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Settings

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

DELETE FROM `sys_options_categories` WHERE `name` = 'pwa_manifest';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'pwa_manifest', '_adm_stg_cpt_category_pwa_manifest', 1, 2);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN('sys_pwa_manifest_name', 'sys_pwa_manifest_short_name', 'sys_pwa_manifest_description', 'sys_pwa_manifest_background_color', 'sys_pwa_manifest_theme_color');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_pwa_manifest_name', '_adm_stg_cpt_option_sys_pwa_manifest_name', '', 'digit', '', '', '', 1),
(@iCategoryId, 'sys_pwa_manifest_short_name', '_adm_stg_cpt_option_sys_pwa_manifest_short_name', '', 'digit', '', '', '', 2),
(@iCategoryId, 'sys_pwa_manifest_description', '_adm_stg_cpt_option_sys_pwa_manifest_description', '', 'text', '', '', '', 3), 
(@iCategoryId, 'sys_pwa_manifest_background_color', '_adm_stg_cpt_option_sys_pwa_manifest_background_color', '', 'digit', '', '', '', 4),
(@iCategoryId, 'sys_pwa_manifest_theme_color', '_adm_stg_cpt_option_sys_pwa_manifest_theme_color', '', 'digit', '', '', '', 5);

DELETE FROM `sys_options_categories` WHERE `name` = 'pwa_sw';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'pwa_sw', '_adm_stg_cpt_category_pwa_sw', 1, 2);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN('sys_pwa_sw_enable', 'sys_pwa_sw_cache', 'sys_pwa_sw_offline');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_pwa_sw_enable', '_adm_stg_cpt_option_sys_pwa_sw_enable', '', 'checkbox', '', '', '', 1),
(@iCategoryId, 'sys_pwa_sw_cache', '_adm_stg_cpt_option_sys_pwa_sw_cache', '', 'text', '', '', '', 2), 
(@iCategoryId, 'sys_pwa_sw_offline', '_adm_stg_cpt_option_sys_pwa_sw_offline', '', 'digit', '', '', '', 3);

-- Vote and Score objects

UPDATE `sys_objects_vote` SET `ClassName` = 'BxTemplCmtsVoteLikes' WHERE `Name` = 'sys_cmts';
UPDATE `sys_objects_vote` SET `ClassName` = 'BxTemplCmtsVoteReactions' WHERE `Name` = 'sys_cmts_reactions';

UPDATE `sys_objects_score` SET `class_name` = 'BxTemplCmtsScore' WHERE `name` = 'sys_cmts';

-- Recomendations

SET @iRecFriends = (SELECT `id` FROM `sys_objects_recommendation` WHERE `name` = 'sys_friends');

DELETE FROM `sys_recommendation_criteria` WHERE `name` IN('shared_context', 'shared_location', 'last_active') AND `object_id` = @iRecFriends;
INSERT INTO `sys_recommendation_criteria` (`object_id`, `name`, `source_type`, `source`, `params`, `weight`, `active`) VALUES
(@iRecFriends, 'shared_context', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:44:"get_friend_recommendations_by_shared_context";s:6:"params";a:3:{i:0;s:12:"{profile_id}";i:1;s:12:"{connection}";i:2;s:8:"{points}";}s:5:"class";s:27:"TemplServiceRecommendations";}', 'a:2:{s:6:"points";i:1;s:10:"connection";s:14:"bx_groups_fans";}', 0.20, 1),
(@iRecFriends, 'shared_location', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:45:"get_friend_recommendations_by_shared_location";s:6:"params";a:3:{i:0;s:12:"{profile_id}";i:1;s:8:"{radius}";i:2;s:8:"{points}";}s:5:"class";s:27:"TemplServiceRecommendations";}', 'a:2:{s:6:"radius";i:10;s:6:"points";i:1;}', 0.20, 1),
(@iRecFriends, 'last_active', 'sql', 'SELECT `tp`.`id` AS `id`, {points} AS `value` FROM `sys_profiles` AS `tp` INNER JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` LEFT JOIN `sys_sessions` AS `ts` ON `tp`.`account_id`=`ts`.`user_id` WHERE `tp`.`id`<>{profile_id} AND `tp`.`id` NOT IN (SELECT `content` FROM `sys_profiles_conn_friends` WHERE `initiator`={profile_id} AND `mutual`=''1'') AND `tp`.`type` IN (''bx_persons'', ''bx_organizations'') ORDER BY `ts`.`date` DESC, `ta`.`logged` DESC, {order_by}', 'a:1:{s:6:"points";i:0;}', 0.1, 1);


SET @iRecSubscriptions = (SELECT `id` FROM `sys_objects_recommendation` WHERE `name` = 'sys_subscriptions');

DELETE FROM `sys_recommendation_criteria` WHERE `name` = 'shared_context' AND `object_id` = @iRecSubscriptions;
INSERT INTO `sys_recommendation_criteria` (`object_id`, `name`, `source_type`, `source`, `params`, `weight`, `active`) VALUES
(@iRecSubscriptions, 'shared_context', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:50:"get_subscription_recommendations_by_shared_context";s:6:"params";a:3:{i:0;s:12:"{profile_id}";i:1;s:12:"{connection}";i:2;s:8:"{points}";}s:5:"class";s:27:"TemplServiceRecommendations";}', 'a:2:{s:6:"points";i:1;s:10:"connection";s:14:"bx_groups_fans";}', 0.5, 1);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.1.0-B2' WHERE (`version` = '13.1.0.B1' OR `version` = '13.1.0-B1') AND `name` = 'system';

