SET @sName = 'bx_quoteofday';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_quoteofday_adm_stg_cpt_type', 'bx_quoteofday@modules/boonex/quoteofday/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_quoteofday_general', '_bx_quoteofday_adm_stg_cpt_category_general', 0, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_quoteofday_source', '_bx_quoteofday_source', 'internal', 'list', 'a:2:{s:6:"module";s:13:"bx_quoteofday";s:6:"method";s:11:"get_sources";}', '', '', '', 1),
(@iCategoryId, 'bx_quoteofday_rss_url', '_bx_quoteofday_option_rss_url', '', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_quoteofday_rss_max_items', '_bx_quoteofday_rss_max_items', '5', 'digit', '', '', '', '', 3),
(@iCategoryId, 'bx_quoteofday_selection_mode', '_bx_quoteofday_selection_mode', 'byrandom', 'select', 'a:2:{s:6:"module";s:13:"bx_quoteofday";s:6:"method";s:18:"get_selection_mode";}', '', '', '', 4);

-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (object, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) 
VALUES('bx_quoteofday_internal', 'Sql', 'SELECT * FROM `bx_quoteofday_internal` WHERE 1 ', 'bx_quoteofday_internal', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'text', '', 'auto', '', '', 192, 'BxQuoteOfDayGridInternal', 'modules/boonex/quoteofday/classes/BxQuoteOfDayGridInternal.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_quoteofday_internal', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_quoteofday_internal', 'switcher', '_bx_quoteofday_grid_column_title_adm_active', '8%', 0, 0, '', 2),
('bx_quoteofday_internal', 'text', '_bx_quoteofday_grid_column_title_adm_text', '70%', 0, 35, '', 3),
('bx_quoteofday_internal', 'actions', '', '20%', 0, 0, '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_quoteofday_internal', 'single', 'edit', '_bx_quoteofday_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_quoteofday_internal', 'single', 'publish', '_bx_quoteofday_grid_action_title_adm_publish', 'quote-right', 1, 0, 3),
('bx_quoteofday_internal', 'single', 'delete', '_bx_quoteofday_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_quoteofday_internal', 'bulk', 'delete', '_bx_quoteofday_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_quoteofday_internal', 'independent', 'add', '_bx_quoteofday_grid_action_title_adm_add', '', 0, 0, 1);

-- PAGE: add block to homepage
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES('sys_home', 1, @sName, '', '_bx_quoteofday_page_block_title', 11, 2147483647, '0', 'service', 'a:2:{s:6:"module";s:13:"bx_quoteofday";s:6:"method";s:9:"get_quote";}', 1, 1, 1, IFNULL(@iBlockOrder, 0) + 1);

-- PAGE: create entry
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `cover_image`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES('bx_quoteofday_manage', 'quoteofday-manage', '_bx_quoteofday_page_title_manage', '_bx_quoteofday_page_title_manage', @sName, 1, 0, 5, 2147483647, 1, 'page.php?i=quoteofday-manage', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (object, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES('bx_quoteofday_manage', 1, @sName, '_bx_quoteofday_page_block_title_manage', '_bx_quoteofday_page_block_title_manage', 11, 2147483647, '0', 'service', 'a:2:{s:6:"module";s:13:"bx_quoteofday";s:6:"method";s:17:"get_quotes_manage";}', 0, 1, 1, 1);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES('sys_account_dashboard_manage_tools', @sName, 'quoteofday-manage', '_bx_quoteofday_menu_item_title_system_admt_manage', '_bx_quoteofday_menu_item_title_system_admt_manage', 'page.php?i=quoteofday-manage', '', '_self', '', 'a:2:{s:6:"module";s:13:"bx_quoteofday";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 0, 2147483647, 1, 0, 1, 6);

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxQuoteOfDayAlertsResponse', 'modules/boonex/quoteofday/classes/BxQuoteOfDayAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);

