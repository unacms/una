SET @sName = 'bx_timeline';

DROP TABLE IF EXISTS `bx_timeline_events`;
DROP TABLE IF EXISTS `bx_timeline_handlers`;

DROP TABLE IF EXISTS `bx_timeline_photos`;
DROP TABLE IF EXISTS `bx_timeline_photos_processed`;
DROP TABLE IF EXISTS `bx_timeline_photos2events`;

DROP TABLE IF EXISTS `bx_timeline_videos`;
DROP TABLE IF EXISTS `bx_timeline_videos_processed`;
DROP TABLE IF EXISTS `bx_timeline_videos2events`;

DROP TABLE IF EXISTS `bx_timeline_links`;
DROP TABLE IF EXISTS `bx_timeline_links2events`;

DROP TABLE IF EXISTS `bx_timeline_comments`;

DROP TABLE IF EXISTS `bx_timeline_votes`;
DROP TABLE IF EXISTS `bx_timeline_votes_track`;

DROP TABLE IF EXISTS `bx_timeline_meta_keywords`;
DROP TABLE IF EXISTS `bx_timeline_meta_locations`;

DROP TABLE IF EXISTS `bx_timeline_reports`;
DROP TABLE IF EXISTS `bx_timeline_reports_track`;


-- STORAGES, TRANSCODERS, UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_timeline%';
DELETE FROM `sys_objects_storage` WHERE `object`  LIKE 'bx_timeline%';
DELETE FROM `sys_objects_transcoder` WHERE `object`  LIKE 'bx_timeline%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_timeline%';


-- Forms All
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module` = @sName);
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_objects_form` WHERE `module` = @sName;

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;
