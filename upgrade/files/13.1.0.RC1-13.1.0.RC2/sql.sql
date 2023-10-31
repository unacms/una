
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options

UPDATE `sys_options` SET `order` = 141 WHERE `name` = 'sys_default_curl_timeout';

SET @iCategoryIdHid = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHid, 'sys_default_socket_timeout', '_adm_stg_cpt_option_sys_default_socket_timeout', '30', 'digit', '', '', '', '', 140),
(@iCategoryIdHid, 'sys_form_lpc_enable', '_adm_stg_cpt_option_sys_form_lpc_enable', 'on', 'checkbox', '', '', '', '', 260);


SET @iCategoryIdApi = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_layout');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdApi, 'sys_api_conn_in_prof_units', '_adm_stg_cpt_option_sys_api_conn_in_prof_units', '', 'checkbox', '', '', '', 31);

-- Pages

UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_con_friends' AND `title` = '_sys_page_block_title_con_friends';
UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_con_friend_requests' AND `title` = '_sys_page_block_title_con_friend_requests';
UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_con_friend_requested' AND `title` = '_sys_page_block_title_con_friend_requested';


UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_con_following' AND `title` = '_sys_page_block_title_con_following';
UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_con_followers' AND `title` = '_sys_page_block_title_con_followers';

UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_recom_friends' AND `title` = '_sys_page_block_title_recom_friends';
UPDATE `sys_pages_blocks` SET `copyable` = 0 WHERE `object` = 'sys_recom_subscriptions' AND `title` = '_sys_page_block_title_recom_subscriptions';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.1.0-RC2' WHERE (`version` = '13.1.0.RC1' OR `version` = '13.1.0-RC1') AND `name` = 'system';

