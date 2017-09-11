-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_albums_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_albums_searchable_fields', 'title,text', @iCategoryId, '_bx_albums_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:9:"bx_albums";s:6:"method";s:21:"get_searchable_fields";}', 30);
