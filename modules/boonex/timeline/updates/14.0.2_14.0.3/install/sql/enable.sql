SET @sName = 'bx_timeline';


-- MENUS
UPDATE `sys_objects_menu` SET `persistent`='1' WHERE `object`='bx_timeline_menu_item_actions_all';


-- SETTINGS
UPDATE `sys_options` SET `value`='feed,channels,hot,recom_friends,recom_subscriptions' WHERE `name`='bx_timeline_for_you_sources';


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`='bx_timeline_administration' AND `name`='status';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_timeline_administration', 'status', '_bx_timeline_grid_column_title_adm_status', '5%', 1, 0, '', 4);

UPDATE `sys_grid_fields` SET `width`='15%', `chars_limit`='0' WHERE `object`='bx_timeline_administration' AND `name`='date';
UPDATE `sys_grid_fields` SET `chars_limit`='0' WHERE `object`='bx_timeline_administration' AND `name`='owner_id';
