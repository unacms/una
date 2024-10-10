
-- TABLES

DROP TABLE IF EXISTS `bx_courses_data`, `bx_courses_content_structure`, `bx_courses_content_nodes`, `bx_courses_content_nodes2users`, `bx_courses_content_data`, `bx_courses_content_data2users`, `bx_courses_pics`, `bx_courses_pics_resized`, `bx_courses_cmts`, `bx_courses_cmts_notes`, `bx_courses_views_track`, `bx_courses_meta_keywords`, `bx_courses_meta_locations`, `bx_courses_meta_mentions`, `bx_courses_fans`, `bx_courses_admins`, `bx_courses_votes`, `bx_courses_votes_track`, `bx_courses_reports`, `bx_courses_reports_track`, `bx_courses_favorites_track`, `bx_courses_favorites_lists`, `bx_courses_scores`, `bx_courses_scores_track`, `bx_courses_invites`, `bx_courses_prices`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_courses';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_courses_pics', 'bx_courses_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_courses_pics', 'bx_courses_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_courses_icon', 'bx_courses_thumb', 'bx_courses_avatar', 'bx_courses_avatar_big', 'bx_courses_picture', 'bx_courses_cover', 'bx_courses_cover_thumb', 'bx_courses_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_courses_icon', 'bx_courses_thumb', 'bx_courses_avatar', 'bx_courses_avatar_big', 'bx_courses_picture', 'bx_courses_cover', 'bx_courses_cover_thumb', 'bx_courses_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_courses_icon', 'bx_courses_thumb', 'bx_courses_avatar', 'bx_courses_avatar_big', 'bx_courses_picture', 'bx_courses_cover', 'bx_courses_cover_thumb', 'bx_courses_gallery');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_courses';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_courses';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_courses';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_course_add', 'bx_course_delete', 'bx_course_edit', 'bx_course_edit_cover', 'bx_course_view', 'bx_course_view_full', 'bx_course_invite', 'bx_courses_price_add', 'bx_courses_price_edit');

-- PRE-VALUES

DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_courses';

DELETE FROM `sys_form_pre_values` WHERE `Key` LIKE 'bx_courses%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_courses';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_courses';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_courses';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_courses';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_courses';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_courses';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_courses';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_courses', 'bx_courses_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_courses');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_courses';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_courses';
