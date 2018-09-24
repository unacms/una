SET @sName = 'bx_forum';

-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_forum_labels';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_forum_labels', '', @iCategId, '_sys_option_labels', 'text', '', '', '', 50);


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='12' WHERE`object`='bx_forum_view_entry' AND `layout_id`='10';

UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_breadcrumb' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='1' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_author'  AND `cell_id`='2' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='2' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_sys_entry_context' AND `cell_id`='2' AND `order`='1';
UPDATE `sys_pages_blocks` SET `order`='3' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_participants' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_text' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='1', `order`='2'  WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_all_actions' AND `order`='1';
UPDATE `sys_pages_blocks` SET `cell_id`='2' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_attachments';
UPDATE `sys_pages_blocks` SET `cell_id`='2' WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_comments';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_info';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 3, @sName, '', '_bx_forum_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 0, 0);


-- MENUS
UPDATE `sys_menu_items` SET `icon`='stop-circle' WHERE `set_name`='bx_forum_view' AND `name`='hide-discussion' AND `icon`='eye-slash';
UPDATE `sys_menu_items` SET `icon`='play-circle' WHERE `set_name`='bx_forum_view_more' AND `name`='unhide-discussion' AND `icon`='eye';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_view_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_view_actions', '_sys_menu_title_view_actions', 'bx_forum_view_actions', @sName, 15, 0, 1, 'BxForumMenuViewActions', 'modules/boonex/forum/classes/BxForumMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_view_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_view_actions', @sName, '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_actions';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_actions', @sName, 'subscribe-discussion', '_bx_forum_menu_item_title_system_subscribe', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_forum_view_actions', @sName, 'unsubscribe-discussion', '_bx_forum_menu_item_title_system_unsubscribe', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_forum_view_actions', @sName, 'stick-discussion', '_bx_forum_menu_item_title_system_stick_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 30),
('bx_forum_view_actions', @sName, 'unstick-discussion', '_bx_forum_menu_item_title_system_unstick_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 40),
('bx_forum_view_actions', @sName, 'lock-discussion', '_bx_forum_menu_item_title_system_lock_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 50),
('bx_forum_view_actions', @sName, 'unlock-discussion', '_bx_forum_menu_item_title_system_unlock_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 60),
('bx_forum_view_actions', @sName, 'hide-discussion', '_bx_forum_menu_item_title_system_hide_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 70),
('bx_forum_view_actions', @sName, 'unhide-discussion', '_bx_forum_menu_item_title_system_unhide_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 80),
('bx_forum_view_actions', @sName, 'edit-discussion', '_bx_forum_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 90),
('bx_forum_view_actions', @sName, 'delete-discussion', '_bx_forum_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 100),
('bx_forum_view_actions', @sName, 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_forum_view_actions', @sName, 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_forum_view_actions', @sName, 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_forum_view_actions', @sName, 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_forum_view_actions', @sName, 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_forum_view_actions', @sName, 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_forum_view_actions', @sName, 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_forum_view_actions', @sName, 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 270),
('bx_forum_view_actions', @sName, 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_forum_view_actions', @sName, 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_forum_view_actions', @sName, 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_forum_view_actions', @sName, 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_forum_view_actions', @sName, 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);


-- GRIDS:
UPDATE `sys_objects_grid` SET `sorting_fields`='reports' WHERE `object`='bx_forum_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_administration' AND `name`='reports';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_forum_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_forum_administration' AND `name`='author';


-- CATEGORY
UPDATE `sys_objects_category` SET `join`='INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = `bx_forum_discussions`.`author` OR `sys_profiles`.`id` = -`bx_forum_discussions`.`author`)' WHERE `object`='bx_forum_cats'; 
