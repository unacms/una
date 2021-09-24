
-- TABLES
DROP TABLE IF EXISTS `bx_videos_entries`, `bx_videos_photos`, `bx_videos_videos`, `bx_videos_media_resized`, `bx_videos_cmts`, `bx_videos_cmts_notes`, `bx_videos_votes`, `bx_videos_votes_track`, `bx_videos_svotes`, `bx_videos_svotes_track`, `bx_videos_reactions`, `bx_videos_reactions_track`, `bx_videos_views_track`, `bx_videos_meta_keywords`, `bx_videos_meta_locations`, `bx_videos_meta_mentions`, `bx_videos_reports`, `bx_videos_reports_track`, `bx_videos_favorites_track`, `bx_videos_favorites_lists`, `bx_videos_scores`, `bx_videos_scores_track`, `bx_videos_embeds_providers`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_videos_photos', 'bx_videos_videos', 'bx_videos_media_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN ('bx_videos_photos', 'bx_videos_videos', 'bx_videos_media_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_videos_preview', 'bx_videos_gallery', 'bx_videos_cover', 'bx_videos_poster', 'bx_videos_video_poster_preview', 'bx_videos_video_poster_gallery', 'bx_videos_video_poster_cover', 'bx_videos_video_mp4', 'bx_videos_video_mp4_hd');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_videos_preview', 'bx_videos_gallery', 'bx_videos_cover', 'bx_videos_poster', 'bx_videos_video_poster_preview', 'bx_videos_video_poster_gallery', 'bx_videos_video_poster_cover', 'bx_videos_video_mp4', 'bx_videos_video_mp4_hd');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_videos_preview', 'bx_videos_gallery', 'bx_videos_cover', 'bx_videos_poster', 'bx_videos_video_poster_preview', 'bx_videos_video_poster_gallery', 'bx_videos_video_poster_cover');
DELETE FROM `sys_transcoder_videos_files` WHERE `transcoder_object` IN('bx_videos_video_mp4', 'bx_videos_video_mp4_hd');


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_videos';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_videos';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_videos';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_videos_entry_add', 'bx_videos_entry_edit', 'bx_videos_entry_view', 'bx_videos_entry_delete');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_videos';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_videos_cats', 'bx_videos_source');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_videos';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_videos', 'bx_videos_stars', 'bx_videos_reactions');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_videos';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_videos';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_videos';


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_videos';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_videos';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_videos', 'bx_videos_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_videos');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_videos';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_videos';
