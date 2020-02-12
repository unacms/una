
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_wiki', '_bx_wiki', 'bx_wiki@modules/boonex/wiki/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_wiki', '_bx_wiki', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_wiki_design_box', '0', @iCategId, '_bx_wiki_option_design_box', 'select', '', '', '', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_design_boxes";s:5:"class";s:16:"TemplServiceWiki";}', 10);

-- Wiki object

INSERT INTO `sys_objects_wiki` (`object`, `uri`, `title`, `module`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki', 'wiki', '_bx_wiki_object_title', 'bx_wiki', '', '');

-- Permalinks

INSERT INTO `sys_permalinks` (`standard`, `permalink`, `check`, `compare_by_prefix`) VALUES
('r.php?_q=wiki/', 'wiki/', 'permalinks_pages', 1);

-- Rewrite rules

INSERT INTO `sys_rewrite_rules` (`preg`, `service`, `active`) VALUES
('^wiki/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:9:"wiki_page";s:6:"params";a:2:{i:0;s:4:"wiki";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', 1),
('^wiki-action/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"wiki_action";s:6:"params";a:2:{i:0;s:4:"wiki";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', '1');

-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_wiki', 'wiki-home', '', '_bx_wiki_menu_item_title_system_home', 'r.php?_q=wiki/wiki-home', '', '', 'far file-word', '', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu

SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_wiki', 'wiki-home', '', '_bx_wiki_menu_item_title_system_home', 'r.php?_q=wiki/wiki-home', '', '', 'far file-word', '', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'add page', NULL, '_bx_wiki_acl_action_add_page', '', 0, 1);
SET @iIdActionAddPage = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'add block', NULL, '_bx_wiki_acl_action_add_block', '', 0, 1);
SET @iIdActionAddBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'edit block', NULL, '_bx_wiki_acl_action_edit_block', '', 0, 0);
SET @iIdActionEditBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'translate block', NULL, '_bx_wiki_acl_action_translate_block', '', 0, 0);
SET @iIdActionTranslateBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'delete version', NULL, '_bx_wiki_acl_action_delete_version', '', 0, 1);
SET @iIdActionDeleteVersion = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'delete block', NULL, '_bx_wiki_acl_action_delete_block', '', 0, 1);
SET @iIdActionDeleteBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'history', NULL, '_bx_wiki_acl_action_history', '', 0, 0);
SET @iIdActionHistory = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_wiki', 'unsafe', NULL, '_bx_wiki_acl_action_unsafe', '', 0, 0);
SET @iIdActionUnsafe = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- add page
(@iModerator, @iIdActionAddPage),
(@iAdministrator, @iIdActionAddPage),

-- add block
(@iModerator, @iIdActionAddBlock),
(@iAdministrator, @iIdActionAddBlock),

-- edit block
(@iModerator, @iIdActionEditBlock),
(@iAdministrator, @iIdActionEditBlock),

-- translate block
(@iModerator, @iIdActionTranslateBlock),
(@iAdministrator, @iIdActionTranslateBlock),

-- delete version
(@iModerator, @iIdActionDeleteVersion),
(@iAdministrator, @iIdActionDeleteVersion),

-- delete block
(@iModerator, @iIdActionDeleteBlock),
(@iAdministrator, @iIdActionDeleteBlock),

-- history
(@iModerator, @iIdActionHistory),
(@iAdministrator, @iIdActionHistory);

