
-- settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_antispam', '_bx_antispam_adm_stg_cpt_type', 'bx_antispam@modules/boonex/antispam/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_antispam_general', '_bx_antispam_adm_stg_cpt_category_general', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_block', '_bx_antispam_option_block', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'bx_antispam_report', '_bx_antispam_option_report', 'on', 'checkbox', '', '', '', 20),
(@iCategoryId, 'bx_antispam_ip_list_type', '_bx_antispam_option_ip_list_type', '0', 'digit', '', '', '', 30),

(@iCategoryId, 'bx_antispam_dnsbl_enable', '_bx_antispam_option_dnsbl_enable', '', 'checkbox', '', '', '', 40),
(@iCategoryId, 'bx_antispam_dnsbl_behaviour', '_bx_antispam_option_dnsbl_behaviour', 'approval', 'select', 'block,approval', '', '', 41),
(@iCategoryId, 'bx_antispam_uridnsbl_enable', '_bx_antispam_option_uridnsbl_enable', '', 'checkbox', '', '', '', 42),

(@iCategoryId, 'bx_antispam_akismet_enable', '_bx_antispam_option_akismet_enable', '', 'checkbox', '', '', '', 50),
(@iCategoryId, 'bx_antispam_akismet_api_key', '_bx_antispam_option_akismet_api_key', '', 'digit', '', '', '', 51),

(@iCategoryId, 'bx_antispam_stopforumspam_enable', '_bx_antispam_option_stopforumspam_enable', 'on', 'checkbox', '', '', '', 60),
(@iCategoryId, 'bx_antispam_stopforumspam_api_key', '_bx_antispam_option_stopforumspam_api_key', '', 'digit', '', '', '', 61);

-- page: ip table

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_antispam_ip_table', 'antispam-ip-table', '_bx_antispam_page_title_sys_ip_table', '_bx_antispam_page_title_ip_table', 'bx_antispam', 5, 64, 1, 'page.php?i=antispam-ip-table', '', '', '', 0, 1, 0, 'BxAntispamPage', 'modules/boonex/antispam/classes/BxAntispamPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_antispam_ip_table', 1, 'bx_antispam', '_bx_antispam_page_block_title_ip_table', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_antispam\";s:6:\"method\";s:8:\"ip_table\";}', 0, 1, 1, 1);

-- grid: ip table

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_antispam_grid_ip_table', 'Sql', 'SELECT `ID`, `From`, `To`, `Type`, `LastDT`, `Desc` FROM `bx_antispam_ip_table`', 'bx_antispam_ip_table', 'ID', '', '', 10, NULL, 'start', '', 'From,To,Desc', 'auto', 'From,To,Type,LastDT,Desc', 64, 'BxAntispamGridIpTable', 'modules/boonex/antispam/classes/BxAntispamGridIpTable.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_antispam_grid_ip_table', 'checkbox', '_bx_antispam_grid_field_select', '2%', '', 1),
('bx_antispam_grid_ip_table', 'From', '_bx_antispam_grid_field_ip_from', '15%', '', 2),
('bx_antispam_grid_ip_table', 'To', '_bx_antispam_grid_field_ip_to', '15%', '', 3),
('bx_antispam_grid_ip_table', 'Type', '_bx_antispam_grid_title_action', '15%', '', 4),
('bx_antispam_grid_ip_table', 'LastDT', '_bx_antispam_grid_title_expiration', '15%', '', 5),
('bx_antispam_grid_ip_table', 'Desc', '_bx_antispam_grid_title_note', '26%', '', 6),
('bx_antispam_grid_ip_table', 'actions', '_bx_antispam_grid_title_actions', '12%', '', 7);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_antispam_grid_ip_table', 'bulk', 'delete', '_bx_antispam_grid_action_delete', '', 1, 1),
('bx_antispam_grid_ip_table', 'single', 'edit', '', 'pencil', 0, 1),
('bx_antispam_grid_ip_table', 'single', 'delete', '', 'remove',  1, 2),
('bx_antispam_grid_ip_table', 'independent', 'add', '_bx_antispam_grid_action_add', '', 0, 1);

