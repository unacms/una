
-- TABLES

DROP TABLE IF EXISTS `bx_groups_data`, `bx_groups_pics`, `bx_groups_pics_resized`, `bx_groups_cmts`, `bx_groups_cmts_notes`, `bx_groups_views_track`, `bx_groups_meta_keywords`, `bx_groups_meta_locations`, `bx_groups_meta_mentions`, `bx_groups_fans`, `bx_groups_admins`, `bx_groups_votes`, `bx_groups_votes_track`, `bx_groups_reports`, `bx_groups_reports_track`, `bx_groups_favorites_track`, `bx_groups_favorites_lists`, `bx_groups_scores`, `bx_groups_scores_track`, `bx_groups_invites`, `bx_groups_prices`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_groups';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_groups_pics', 'bx_groups_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_groups_pics', 'bx_groups_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_avatar_big', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb', 'bx_groups_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_avatar_big', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb', 'bx_groups_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_avatar_big', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb', 'bx_groups_gallery');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_group_add', 'bx_group_delete', 'bx_group_edit', 'bx_group_edit_cover', 'bx_group_view', 'bx_group_view_full', 'bx_group_invite', 'bx_groups_price_add', 'bx_groups_price_edit');

-- PRE-VALUES

DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_groups';

DELETE FROM `sys_form_pre_values` WHERE `Key` LIKE 'bx_groups%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_groups%';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_groups';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_groups';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_groups';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_groups';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_groups';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_groups';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_groups', 'bx_groups_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_groups');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_groups';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_groups';
