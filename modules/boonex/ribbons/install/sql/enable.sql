SET @sName = 'bx_ribbons';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_ribbons_adm_stg_cpt_type', 'bx_ribbons@modules/boonex/ribbons/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_ribbons_general', '_bx_ribbons_adm_stg_cpt_category_general', 0, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_ribbons_modules_list', '_bx_ribbons_modules_list', '', 'list', 'a:2:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:11:"get_modules";}', '', '', '', 0);

-- MENU: add menu item to profiles modules actions menu (trigger* menu sets are processed separately upon modules enable/disable)
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visibility_custom`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_actions', @sName, 'ribbons', '_bx_ribbons_menu_item_title_system_follow', '_bx_ribbons_menu_item_title_follow', 'javascript:void(0)', 'oBxRibbons.init({profile_id})', '', 'ribbon', '', '', 192, 1, 0, 0),
('trigger_profile_snippet_meta', @sName, 'ribbons', '_bx_ribbons_menu_item_title_system_follow', '_bx_ribbons_menu_item_title_follow', '', '', '', '', '', '', 2147483646, 1, 0, 0);


-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (object, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) 
VALUES('bx_ribbons_data', 'Sql', 'SELECT * FROM `bx_ribbons_data` WHERE 1 ', 'bx_ribbons_data', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'text', '', 'auto', '', '', 192, 'BxRibbonsGrid', 'modules/boonex/ribbons/classes/BxRibbonsGrid.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_ribbons_data', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_ribbons_data', 'switcher', '_bx_ribbons_grid_column_title_adm_active', '8%', 0, 0, '', 2),
('bx_ribbons_data', 'title', '_bx_ribbons_grid_column_title_adm_title', '35%', 0, 35, '', 3),
('bx_ribbons_data', 'text', '_bx_ribbons_grid_column_title_adm_text', '35%', 0, 35, '', 4),
('bx_ribbons_data', 'actions', '', '20%', 0, 0, '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_ribbons_data', 'single', 'edit', '_bx_ribbons_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_ribbons_data', 'single', 'delete', '_bx_ribbons_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_ribbons_data', 'bulk', 'delete', '_bx_ribbons_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_ribbons_data', 'independent', 'add', '_bx_ribbons_grid_action_title_adm_add', '', 0, 0, 1);


-- PAGE: module manage
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `cover_image`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) 
VALUES('bx_ribbons_manage', 'ribbons-manage', '_bx_ribbons_page_title_manage', '_bx_ribbons_page_title_manage', @sName, 1, 0, 5, 192, 1, 'page.php?i=ribbons-manage', '', '', '', 0, 1, 0, 'BxRibbonsPageBrowse', 'modules/boonex/ribbons/classes/BxRibbonsPageBrowse.php');

INSERT INTO `sys_pages_blocks` (object, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) 
VALUES('bx_ribbons_manage', 1, @sName, '_bx_ribbons_page_block_title_manage', '_bx_ribbons_page_block_title_manage', 11, 192, '0', 'service', 'a:2:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:18:"get_ribbons_manage";}', 0, 0, 1, 1);

-- PAGE: create entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ribbons_create_entry', '_bx_ribbons_page_title_sys_create_entry', '_bx_ribbons_page_title_create_entry', 'bx_ribbons', 5, 192, 1, 'create-ribbon', 'page.php?i=create-ribbon', '', '', '', 0, 1, 0, 'BxRibbonsPageBrowse', 'modules/boonex/ribbons/classes/BxRibbonsPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_ribbons_create_entry', 1, 'bx_ribbons', '_bx_ribbons_page_block_title_create_entry', 11, 192, 'service', 'a:2:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:13:"entity_create";}', 0, 0, 1);


-- PAGE: edit entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ribbons_edit_entry', '_bx_ribbons_page_title_sys_edit_entry', '_bx_ribbons_page_title_edit_entry', 'bx_ribbons', 5, 192, 1, 'edit-ribbon', '', '', '', '', 0, 1, 0, 'BxRibbonsPageEntry', 'modules/boonex/ribbons/classes/BxRibbonsPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_ribbons_edit_entry', 1, 'bx_ribbons', '_bx_ribbons_page_block_title_edit_entry', 11, 192, 'service', 'a:2:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:11:"entity_edit";}', 0, 0, 1);

-- PAGE: service blocks
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 2, @sName, '_bx_ribbons_page_block_title_sys_ribbons', '_bx_ribbons_page_block_title_ribbons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:11:"get_ribbons";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 0);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_ribbons', 'use ribbons', NULL, '_bx_ribbons_acl_action_use_ribbons', '', 1, 3);
SET @iIdActionUseRibbons = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionUseRibbons),
(@iAdministrator, @iIdActionUseRibbons);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxRibbonsAlertsResponse', 'modules/boonex/ribbons/classes/BxRibbonsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'menu_custom_item', @iHandler);

-- INJECTION
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_header', 'service', 'a:2:{s:6:"module";s:10:"bx_ribbons";s:6:"method";s:10:"include_js";}', '0', '1');