-- MENUS
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `set_name`='sys_site' AND `name`='albums-home';
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `set_name`='sys_homepage' AND `name`='albums-home';
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `set_name`='sys_add_content_links' AND `name`='create-album';
UPDATE `sys_menu_items` SET `icon`='pencil-alt' WHERE `set_name`='bx_albums_view' AND `name`='edit-album';
UPDATE `sys_menu_items` SET `icon`='pencil-alt' WHERE `set_name`='bx_albums_view_media' AND `name`='edit-album';
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-albums';
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `module`='bx_albums' AND `name`='albums-author';
UPDATE `sys_menu_items` SET `icon`='far image col-blue1' WHERE `module`='bx_albums' AND `name`='albums-context';


-- STATS
UPDATE `sys_statistics` SET `icon`='far image col-blue1' WHERE `module`='bx_albums' AND `name`='bx_albums';
UPDATE `sys_statistics` SET `icon`='far image col-blue1' WHERE `module`='bx_albums' AND `name`='bx_albums_media';


-- GRIDS
UPDATE `sys_objects_grid` SET `sorting_fields`='reports' WHERE `object`='bx_albums_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_albums_administration' AND `name`='reports';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_albums_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_albums_administration' AND `name`='author';

UPDATE `sys_grid_actions` SET `icon`='pencil-alt' WHERE `object`='bx_albums_administration' AND `name`='edit';
UPDATE `sys_grid_actions` SET `icon`='pencil-alt' WHERE `object`='bx_albums_common' AND `name`='edit';
