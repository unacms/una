-- COMMENTS
UPDATE `sys_objects_cmts` SET `ClassName`='BxAlbumsCmtsMedia', `ClassFile`='modules/boonex/albums/classes/BxAlbumsCmtsMedia.php' WHERE `Name`='bx_albums_media';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_albums', 'bx_albums_media', 'bx_albums_cmts', 'bx_albums_media_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_albums', '_bx_albums', 'bx_albums', 'added', 'edited', 'deleted', '', ''),
('bx_albums_media', '_bx_albums_media', 'bx_albums', 'media_added', '', 'media_deleted', 'BxAlbumsContentInfoMedia', 'modules/boonex/albums/classes/BxAlbumsContentInfoMedia.php'),
('bx_albums_cmts', '_bx_albums_cmts', 'bx_albums', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', ''),
('bx_albums_media_cmts', '_bx_albums_media_cmts', 'bx_albums_media', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_albums';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_albums', 'bx_albums_administration', 'id', '', ''),
('bx_albums', 'bx_albums_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_albums';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_albums', 'bx_albums', 'bx_albums', '_bx_albums_search_extended', 1, '', ''),
('bx_albums_media', 'bx_albums_media', 'bx_albums', '_bx_albums_search_extended_media', 1, '', ''),
('bx_albums_cmts', 'bx_albums_cmts', 'bx_albums', '_bx_albums_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', ''),
('bx_albums_media_cmts', 'bx_albums_media_cmts', 'bx_albums', '_bx_albums_search_extended_media_cmts', 1, 'BxTemplSearchExtendedCmts', '');
