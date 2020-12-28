-- TABLES
CREATE TABLE IF NOT EXISTS `bx_files_bookmarks` (
  `object_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`object_id`, `profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_files_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_files' WHERE `name`='bx_files';


-- FAVORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_files_favorites_lists' WHERE `name`='bx_files';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_files' WHERE `name`='bx_files';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_files' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
