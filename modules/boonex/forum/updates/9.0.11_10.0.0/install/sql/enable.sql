SET @sName = 'bx_forum';


-- SETTINGS
UPDATE `sys_options` SET `value`='title,text,text_comments' WHERE `name`='bx_forum_searchable_fields';

DELETE FROM `sys_options` WHERE `name`='bx_forum_labels';


-- MENUS
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_forum_view_actions' AND `name`='vote';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_actions' AND `name` IN ('reaction', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_actions', @sName, 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_snippet_meta_main';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_snippet_meta_main', '_bx_forum_menu_title_snippet_meta_main', 'bx_forum_snippet_meta_main', 'bx_forum', 15, 0, 1, 'BxForumMenuSnippetMeta', 'modules/boonex/forum/classes/BxForumMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_snippet_meta_main';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_snippet_meta_main', 'bx_forum', '_bx_forum_menu_set_title_snippet_meta_main', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_main';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_forum_snippet_meta_main', 'bx_forum', 'author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 1),
('bx_forum_snippet_meta_main', 'bx_forum', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_forum_snippet_meta_main', 'bx_forum', 'category', '_sys_menu_item_title_system_sm_category', '_sys_menu_item_title_sm_category', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 3);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_snippet_meta_counters';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_snippet_meta_counters', '_bx_forum_menu_title_snippet_meta_counters', 'bx_forum_snippet_meta_counters', 'bx_forum', 15, 0, 1, 'BxForumMenuSnippetMeta', 'modules/boonex/forum/classes/BxForumMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_snippet_meta_counters';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_snippet_meta_counters', 'bx_forum', '_bx_forum_menu_set_title_snippet_meta_counters', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_counters';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_forum_snippet_meta_counters', 'bx_forum', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 1),
('bx_forum_snippet_meta_counters', 'bx_forum', 'votes', '_sys_menu_item_title_system_sm_votes', '_sys_menu_item_title_sm_votes', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_forum_snippet_meta_counters', 'bx_forum', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 3);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_snippet_meta_reply';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_snippet_meta_reply', '_bx_forum_menu_title_snippet_meta_reply', 'bx_forum_snippet_meta_reply', 'bx_forum', 15, 0, 1, 'BxForumMenuSnippetMeta', 'modules/boonex/forum/classes/BxForumMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_snippet_meta_reply';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_snippet_meta_reply', 'bx_forum', '_bx_forum_menu_set_title_snippet_meta_reply', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_reply';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_forum_snippet_meta_reply', 'bx_forum', 'reply-author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 1),
('bx_forum_snippet_meta_reply', 'bx_forum', 'reply-date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_forum_snippet_meta_reply', 'bx_forum', 'reply-text', '_bx_forum_menu_item_title_system_sm_reply_text', '_bx_forum_menu_item_title_sm_reply_text', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 3);

UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='discussions-administration' AND `icon`='';


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`=@sName AND `name` IN ('lr_timestamp', 'comments', 'text');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
(@sName, 'text', '', '90%', '', 2);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_favorite' AND `name` IN ('lr_timestamp', 'comments', 'text');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_favorite', 'text', '', '90%', '', 2);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_feature' AND `name` IN ('lr_timestamp', 'comments', 'text');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_feature', 'text', '', '90%', '', 2);
