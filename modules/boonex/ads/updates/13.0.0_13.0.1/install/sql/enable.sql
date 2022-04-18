-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ads' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_ads_offer_lifetime');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_ads_offer_lifetime', '72', @iCategId, '_bx_ads_option_offer_lifetime', 'digit', '', '', '', 41);



-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view_actions', 'bx_ads', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);


UPDATE `sys_menu_items` SET `name`='profile-stats-my-ads', `link`='page.php?i=ads-author&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-ads';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_ads' WHERE `object`='bx_ads';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_ads' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action` IN ('approve', 'activate', 'disapprove', 'suspend') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'approve', @iHandler),
('profile', 'activate', @iHandler),
('profile', 'disapprove', @iHandler),
('profile', 'suspend', @iHandler);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_ads_offers';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_ads_offers', '0 * * * *', 'BxAdsCronOffers', 'modules/boonex/ads/classes/BxAdsCronOffers.php', '');
