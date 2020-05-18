SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_objects_page` SET `override_class_name`='BxTimelinePageViewItem', `override_class_file`='modules/boonex/timeline/classes/BxTimelinePageViewItem.php' WHERE `object`='bx_timeline_item';
UPDATE `sys_objects_page` SET `override_class_name`='BxTimelinePageViewItem', `override_class_file`='modules/boonex/timeline/classes/BxTimelinePageViewItem.php' WHERE `object`='bx_timeline_item_brief';
