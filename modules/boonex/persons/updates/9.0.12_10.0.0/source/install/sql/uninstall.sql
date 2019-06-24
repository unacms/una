
-- TABLES
DROP TABLE IF EXISTS `bx_persons_data`,`bx_persons_pictures`, `bx_persons_pictures_resized`, `bx_persons_cmts`, `bx_persons_views_track`, `bx_persons_votes`, `bx_persons_votes_track`, `bx_persons_favorites_track`, `bx_persons_reports`, `bx_persons_reports_track`, `bx_persons_meta_keywords`, `bx_persons_meta_locations`, `bx_persons_meta_mentions`, `bx_persons_scores`, `bx_persons_scores_track`;

-- PROFILES
DELETE FROM sys_profiles WHERE `type` = 'bx_persons';

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_persons_pictures', 'bx_persons_pictures_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_persons_pictures', 'bx_persons_pictures_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_avatar_big', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb', 'bx_persons_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_avatar_big', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb', 'bx_persons_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_avatar_big', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb', 'bx_persons_gallery');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_persons';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_persons';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_persons';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_person_add', 'bx_person_delete', 'bx_person_edit', 'bx_person_edit_cover', 'bx_person_view', 'bx_person_view_full');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_persons';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_persons';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_persons';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_persons';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_persons';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_persons';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_persons';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_persons', 'bx_persons_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_persons');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_persons';


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_persons';

