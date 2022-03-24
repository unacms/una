SET @sName = 'bx_forum';


-- TABLES: entries
DROP TABLE IF EXISTS `bx_forum_discussions`, `bx_forum_categories`, `bx_forum_covers`, `bx_forum_files`, `bx_forum_photos`, `bx_forum_photos_resized`, `bx_forum_videos`, `bx_forum_videos_resized`, `bx_forum_links`, `bx_forum_links2content`, `bx_forum_subscribers`, `bx_forum_cmts`, `bx_forum_cmts_notes`, `bx_forum_views_track`, `bx_forum_votes`, `bx_forum_votes_track`, `bx_forum_reactions`, `bx_forum_reactions_track`, `bx_forum_meta_keywords`, `bx_forum_meta_mentions`, `bx_forum_reports`, `bx_forum_reports_track`, `bx_forum_favorites_lists`, `bx_forum_favorites_track`, `bx_forum_scores`, `bx_forum_scores_track`, `bx_forum_polls`, `bx_forum_polls_answers`, `bx_forum_polls_answers_votes`, `bx_forum_polls_answers_votes_track`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_forum_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_forum_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_forum_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_forum_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_forum_%';


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_forum_%';


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module`=@sName;
DELETE FROM `sys_form_pre_values` WHERE `Key` IN ('bx_forum_cats');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = @sName;


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = @sName;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN (@sName, 'bx_forum_reactions', 'bx_forum_poll_answers');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = @sName;


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `Name` = @sName;


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = @sName;


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN (@sName, 'bx_forum_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN (@sName);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_forum';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_forum';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
