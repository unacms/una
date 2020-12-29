
DELETE FROM `sys_std_pages` WHERE `name` = 'builder_roles';
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'builder_roles', '_adm_page_cpt_builder_roles', '_adm_page_cpt_builder_roles', 'wi-bld-roles.svg');
SET @iIdBuilderRoles = LAST_INSERT_ID();

SET @iIdSettings = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'settings' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'configuration', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdSettings;

SET @iIdStore = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'store' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'extensions', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdStore;

SET @iIdDashboard = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'dashboard' LIMIT 1);
UPDATE `sys_std_widgets` SET `featured` = 1, `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdDashboard;

SET @iIdDesigner = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'designer' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'appearance', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdDesigner;

SET @iIdPolyglot = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'polyglot' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'appearance', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdPolyglot;

SET @iIdBuilderPages = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'builder_pages' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'structure', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdBuilderPages;

SET @iIdBuilderMenus = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'builder_menus' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'structure', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdBuilderMenus;

SET @iIdBuilderForms = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'builder_forms' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'structure', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdBuilderForms;

SET @iIdBuilderPermissions = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'builder_permissions' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'configuration', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdBuilderPermissions;

SET @iIdHome = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home' LIMIT 1);
SET @iIdBuilderRolesWidget = (SELECT `id` FROM `sys_std_widgets` WHERE `module` = 'system' AND `url` = '{url_studio}builder_roles.php' LIMIT 1);
DELETE FROM `sys_std_widgets` WHERE `id` = @iIdBuilderRolesWidget;
DELETE FROM `sys_std_pages_widgets` WHERE `widget_id` = @iIdBuilderRolesWidget;
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBuilderRoles, 'system', 'configuration', '{url_studio}builder_roles.php', '', 'wi-bld-roles.svg', '_adm_wgt_cpt_builder_roles', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 11);

SET @iIdManagerStorages = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'storages' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'content', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdManagerStorages;

SET @iIdAudit = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'audit' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'extensions', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdAudit;

SET @iIdBadges = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'badges' LIMIT 1);
UPDATE `sys_std_widgets` SET `type` = 'structure', `cnt_actions` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}' WHERE `page_id` = @iIdBadges;

