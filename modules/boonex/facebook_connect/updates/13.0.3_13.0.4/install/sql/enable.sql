-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_facebook_error';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_facebook_error', 'facebook-error', '_bx_facebook_error', '_bx_facebook_error', 'bx_facebook', 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxFaceBookConnectPage', 'modules/boonex/facebook_connect/classes/BxFaceBookConnectPage.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_facebook_error';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_facebook_error', 1, 'bx_facebook', '_bx_facebook_error', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_facebook\";s:6:\"method\";s:10:\"last_error\";}', 0, 0, 1, 1);
