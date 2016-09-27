-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_market' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_market_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_market_searchable_fields', 'title,text', @iCategoryId, '_bx_market_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:21:"get_searchable_fields";}', 30);


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='5' WHERE `object`='bx_market_categories';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_categories' AND `title`='_bx_market_page_block_title_categories_entries';


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192', `field_active`='status_admin' WHERE `object`='bx_market_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_market_common';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_market_licenses';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_market' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);