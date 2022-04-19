
-- TABLES
DROP TABLE IF EXISTS `bx_posts_posts`, `bx_posts_covers`, `bx_posts_files`, `bx_posts_photos`, `bx_posts_photos_resized`, `bx_posts_links`, `bx_posts_links2content`, `bx_posts_videos`, `bx_posts_videos_resized`, `bx_posts_sounds`, `bx_posts_sounds_resized`, `bx_posts_cmts`, `bx_posts_cmts_notes`, `bx_posts_votes`, `bx_posts_votes_track`, `bx_posts_reactions`, `bx_posts_reactions_track`, `bx_posts_views_track`, `bx_posts_meta_keywords`, `bx_posts_meta_locations`, `bx_posts_meta_mentions`, `bx_posts_reports`, `bx_posts_reports_track`, `bx_posts_favorites_track`, `bx_posts_favorites_lists`, `bx_posts_scores`, `bx_posts_scores_track`, `bx_posts_polls`, `bx_posts_polls_answers`, `bx_posts_polls_answers_votes`, `bx_posts_polls_answers_votes_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_posts_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_posts_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_posts_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_posts_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_posts_%';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_posts_%';

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_posts';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_posts_cats');

-- CATEGORIES
DELETE FROM `sys_categories` WHERE `module` = 'bx_posts';
DELETE FROM `sys_categories2objects` WHERE `module` = 'bx_posts';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_posts%';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_posts', 'bx_posts_reactions', 'bx_posts_poll_answers');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_posts';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_posts';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_posts';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_posts';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_posts';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_posts', 'bx_posts_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_posts');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_posts';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_posts';
