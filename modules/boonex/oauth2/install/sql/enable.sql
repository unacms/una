
-- GRID

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `override_class_name`, `override_class_file`) VALUES
('bx_oauth', 'Sql', 'SELECT * FROM `bx_oauth_clients`', 'bx_oauth_clients', 'id', '', '', 10, NULL, 'start', '', 'title,client_id,client_secret', 'auto', 'title,client_id,client_secret', 'BxOAuthGrid', 'modules/boonex/oauth2/classes/BxOAuthGrid.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_oauth', 'checkbox', 'Select', '2%', '', 10),
('bx_oauth', 'title', '_Title', '28%', '', 20),
('bx_oauth', 'client_id', '_bx_oauth_client_id', '35%', '', 30),
('bx_oauth', 'client_secret', '_bx_oauth_client_secret', '35%', '', 40);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `confirm`, `order`) VALUES
('bx_oauth', 'bulk', 'delete', '_Delete', 1, 1),
('bx_oauth', 'independent', 'add', '_bx_oauth_add', 0, 1);

-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_oauth', '_bx_oauth_adm_stg_cpt_type', 'bx_oauth@modules/boonex/oauth2/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_oauth_general', '_bx_oauth_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

-- INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
-- ('bx_oauth2_on', '', @iCategId, 'Enable OAuth2 Server', 'checkbox', '', '', '10', '');

-- Pages

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_oauth_authorization', 'oauth-authorization', '_bx_oauth_authorization', '_bx_oauth_authorization', 'bx_oauth', 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxOAuthPage', 'modules/boonex/oauth2/classes/BxOAuthPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_oauth_authorization', 1, 'bx_oauth', '_bx_oauth_authorization', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_oauth\";s:6:\"method\";s:13:\"authorization\";}', 0, 0, 1, 1);

