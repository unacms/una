SET @sName = 'bx_forum';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_forum_simple', 'bx_forum_photos_simple', 'bx_forum_videos_simple', 'bx_forum_files_simple');
