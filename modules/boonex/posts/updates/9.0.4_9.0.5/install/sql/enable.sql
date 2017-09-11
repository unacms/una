-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_posts_simple', 'bx_posts_html5');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_simple', 1, 'BxPostsUploaderSimple', 'modules/boonex/posts/classes/BxPostsUploaderSimple.php'),
('bx_posts_html5', 1, 'BxPostsUploaderHTML5', 'modules/boonex/posts/classes/BxPostsUploaderHTML5.php');
