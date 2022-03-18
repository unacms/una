
-- TABLES

DROP TABLE IF EXISTS `bx_cnl_data`, `bx_cnl_content`, `bx_cnl_pics`, `bx_cnl_pics_resized`, `bx_cnl_cmts`, `bx_cnl_cmts_note`, `bx_cnl_views_track`, `bx_cnl_meta_keywords`, `bx_cnl_meta_mentions`, `bx_cnl_votes`, `bx_cnl_votes_track`, `bx_cnl_reports`, `bx_cnl_reports_track`, `bx_cnl_favorites_track`, `bx_cnl_scores`, `bx_cnl_scores_track`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_channels';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_channels_pics', 'bx_channels_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_channels_pics', 'bx_channels_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_channels_icon', 'bx_channels_thumb', 'bx_channels_avatar', 'bx_channels_avatar_big', 'bx_channels_picture', 'bx_channels_cover', 'bx_channels_cover_thumb', 'bx_channels_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_channels_icon', 'bx_channels_thumb', 'bx_channels_avatar', 'bx_channels_avatar_big', 'bx_channels_picture', 'bx_channels_cover', 'bx_channels_cover_thumb', 'bx_channels_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_channels_icon', 'bx_channels_thumb', 'bx_channels_avatar', 'bx_channels_avatar_big', 'bx_channels_picture', 'bx_channels_cover', 'bx_channels_cover_thumb', 'bx_channels_gallery');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_channels';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_channels';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_channels';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_channel_add', 'bx_channel_delete', 'bx_channel_edit', 'bx_channel_edit_cover', 'bx_channel_view', 'bx_channel_view_full');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_channels';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_channels';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_channels';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_channels';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_channels';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_channels';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_channels';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_channels', 'bx_channels_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_channels');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_channels';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_channels';
