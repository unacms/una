-- TABLES
CREATE TABLE IF NOT EXISTS `bx_albums_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_favorites_media_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` IN ('bx_albums', 'bx_albums_media');
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_albums', 'bx_albums_favorites_track', '1', '1', '1', 'page.php?i=view-album&id={object_id}', 'bx_albums_albums', 'id', 'author', 'favorites', '', ''),
('bx_albums_media', 'bx_albums_favorites_media_track', '1', '1', '1', 'page.php?i=view-album-media&id={object_id}', 'bx_albums_files2albums', 'id', '', 'favorites', '', '');


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_albums@modules/boonex/albums/|std-icon.svg' WHERE `name`='bx_albums';
UPDATE `sys_std_widgets` SET `icon`='bx_albums@modules/boonex/albums/|std-icon.svg' WHERE `module`='bx_albums' AND `caption`='_bx_albums';