
-- TABLES

DROP TABLE IF EXISTS `bx_spaces_data`, `bx_spaces_pics`, `bx_spaces_pics_resized`, `bx_spaces_cmts`, `bx_spaces_cmts_notes`, `bx_spaces_views_track`, `bx_spaces_meta_keywords`, `bx_spaces_meta_locations`, `bx_spaces_meta_mentions`, `bx_spaces_fans`, `bx_spaces_admins`, `bx_spaces_votes`, `bx_spaces_votes_track`, `bx_spaces_reports`, `bx_spaces_reports_track`, `bx_spaces_favorites_track`, `bx_spaces_favorites_lists`, `bx_spaces_scores`, `bx_spaces_scores_track`, `bx_spaces_invites`, `bx_spaces_prices`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_spaces';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_spaces_pics', 'bx_spaces_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_spaces_pics', 'bx_spaces_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_spaces_icon', 'bx_spaces_thumb', 'bx_spaces_avatar', 'bx_spaces_avatar_big', 'bx_spaces_picture', 'bx_spaces_cover', 'bx_spaces_cover_thumb', 'bx_spaces_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_spaces_icon', 'bx_spaces_thumb', 'bx_spaces_avatar', 'bx_spaces_avatar_big', 'bx_spaces_picture', 'bx_spaces_cover', 'bx_spaces_cover_thumb', 'bx_spaces_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_spaces_icon', 'bx_spaces_thumb', 'bx_spaces_avatar', 'bx_spaces_avatar_big', 'bx_spaces_picture', 'bx_spaces_cover', 'bx_spaces_cover_thumb', 'bx_spaces_gallery');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_space_add', 'bx_space_delete', 'bx_space_edit', 'bx_space_edit_cover', 'bx_space_view', 'bx_space_view_full', 'bx_space_invite', 'bx_space_invite', 'bx_spaces_price_add', 'bx_spaces_price_edit');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_spaces';

DELETE FROM `sys_form_pre_values` WHERE `Key` LIKE 'bx_spaces%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_spaces%';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_spaces';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_spaces';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_spaces';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_spaces';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_spaces';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_spaces';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_spaces', 'bx_spaces_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_spaces');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_spaces';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_spaces';
