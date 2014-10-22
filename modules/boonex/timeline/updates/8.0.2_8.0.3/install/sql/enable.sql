UPDATE `sys_objects_menu` SET `override_class_name`='BxTimelineMenuItemManage', `override_class_file`='modules/boonex/timeline/classes/BxTimelineMenuItemManage.php' WHERE `object`='bx_timeline_menu_item_manage' LIMIT 1;
UPDATE `sys_objects_menu` SET `override_class_name`='BxTimelineMenuItemActions', `override_class_file`='modules/boonex/timeline/classes/BxTimelineMenuItemActions.php' WHERE `object`='bx_timeline_menu_item_actions' LIMIT 1;

UPDATE `sys_menu_items` SET `editable`=1 WHERE `set_name`='bx_timeline_menu_item_actions' AND `module`='bx_timeline' AND `name`='item-comment' LIMIT 1;
UPDATE `sys_menu_items` SET `title`='', `onclick`='', `target`='', `icon`='', `addon`='', `editable`=0 WHERE `set_name`='bx_timeline_menu_item_actions' AND `module`='bx_timeline' AND `name`='item-vote' LIMIT 1;
UPDATE `sys_menu_items` SET `editable`=1 WHERE `set_name`='bx_timeline_menu_item_actions' AND `module`='bx_timeline' AND `name`='item-share' LIMIT 1;

UPDATE `sys_objects_vote` SET `IsUndo`=0 WHERE `Name`='bx_timeline';