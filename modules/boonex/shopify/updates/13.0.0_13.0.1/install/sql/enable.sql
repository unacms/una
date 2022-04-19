-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_shopify_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_shopify_view_actions', 'bx_shopify', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-shopify', `link`='page.php?i=shopify-author&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-shopify';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_shopify' WHERE `object`='bx_shopify';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_shopify' WHERE `object`='bx_shopify_cats';
