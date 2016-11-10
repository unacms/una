-- TABLES
CREATE TABLE IF NOT EXISTS `bx_posts_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_posts';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_posts', 'bx_posts_favorites_track', '1', '1', '1', 'page.php?i=view-post&id={object_id}', 'bx_posts_posts', 'id', 'author', 'favorites', '', '');


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_posts@modules/boonex/posts/|std-icon.svg' WHERE `name`='bx_posts';
UPDATE `sys_std_widgets` SET `icon`='bx_posts@modules/boonex/posts/|std-icon.svg' WHERE `module`='bx_posts' AND `caption`='_bx_posts';