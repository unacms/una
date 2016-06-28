-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_timeline_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:24:"bx_timeline_simple_photo";}', `values`='a:1:{s:24:"bx_timeline_simple_photo";s:26:"_sys_uploader_simple_title";}' WHERE `object`='bx_timeline_post' AND `name`='photo';
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:24:"bx_timeline_simple_video";}', `values`='a:1:{s:24:"bx_timeline_simple_video";s:26:"_sys_uploader_simple_title";}' WHERE `object`='bx_timeline_post' AND `name`='video';