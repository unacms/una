SET @sName = 'bx_timeline';

DROP TABLE IF EXISTS `bx_timeline_events`, `bx_timeline_events2users`, `bx_timeline_handlers`;

DROP TABLE IF EXISTS `bx_timeline_mute`;

DROP TABLE IF EXISTS `bx_timeline_photos`, `bx_timeline_photos_processed`, `bx_timeline_photos2events`;

DROP TABLE IF EXISTS `bx_timeline_videos`, `bx_timeline_videos_processed`, `bx_timeline_videos2events`;

DROP TABLE IF EXISTS `bx_timeline_files`, `bx_timeline_files2events`;

DROP TABLE IF EXISTS `bx_timeline_links`, `bx_timeline_links2events`;

DROP TABLE IF EXISTS `bx_timeline_reposts_track`;

DROP TABLE IF EXISTS `bx_timeline_comments`;

DROP TABLE IF EXISTS `bx_timeline_views_track`;

DROP TABLE IF EXISTS `bx_timeline_votes`, `bx_timeline_votes_track`, `bx_timeline_reactions`, `bx_timeline_reactions_track`;

DROP TABLE IF EXISTS `bx_timeline_meta_keywords`, `bx_timeline_meta_locations`, `bx_timeline_meta_mentions`;

DROP TABLE IF EXISTS `bx_timeline_reports`, `bx_timeline_reports_track`;

DROP TABLE IF EXISTS `bx_timeline_hot_track`;

DROP TABLE IF EXISTS `bx_timeline_scores`, `bx_timeline_scores_track`;

DROP TABLE IF EXISTS `bx_timeline_cmts_notes`;


-- STORAGES, TRANSCODERS, UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_timeline%';
DELETE FROM `sys_objects_storage` WHERE `object`  LIKE 'bx_timeline%';
DELETE FROM `sys_objects_transcoder` WHERE `object`  LIKE 'bx_timeline%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_timeline%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_timeline%';
DELETE FROM `sys_transcoder_videos_files` WHERE `transcoder_object` LIKE 'bx_timeline%';


-- Forms All
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module` = @sName);
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_objects_form` WHERE `module` = @sName;


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` IN ('bx_timeline', 'bx_timeline_notes');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_timeline' LIMIT 1;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_timeline', 'bx_timeline_reactions');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_timeline';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `Name` = 'bx_timeline' LIMIT 1;


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_timeline', 'bx_timeline_cmts');


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
