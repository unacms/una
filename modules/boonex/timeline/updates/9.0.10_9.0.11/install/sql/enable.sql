SET @sName = 'bx_timeline';

-- MENUS
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''bx_timeline_menu_item_manage'', this, {''id'':''bx_timeline_menu_item_manage_{content_id}''}, {content_id:{content_id}, type:''{type}'', view:''{view}''});' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_item_meta';
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_menu_item_meta', '_sys_menu_title_snippet_meta', 'bx_timeline_menu_item_meta', 'bx_timeline', 15, 0, 1, 'BxTimelineMenuSnippetMeta', 'modules/boonex/timeline/classes/BxTimelineMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_item_meta';
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('bx_timeline_menu_item_meta', 'bx_timeline', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_meta', 'bx_timeline', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 1),
('bx_timeline_menu_item_meta', 'bx_timeline', 'membership', '_sys_menu_item_title_system_sm_membership', '_sys_menu_item_title_sm_membership', '', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 2);

UPDATE `sys_menu_items` SET `name`='create-item' WHERE `set_name`='sys_add_content_links' AND `module`='bx_timeline' AND `name`='create-post';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_preload_comments';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_preload_comments', '0', @iCategId, '_bx_timeline_option_preload_comments', 'digit', '', '', '', '', 51);
