-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_groups' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_groups_per_page_browse');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_groups_per_page_browse', '20', @iCategoryId, '_bx_groups_option_per_page_browse', 'digit', '', '', '', 11);


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_groups_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_groups_common';