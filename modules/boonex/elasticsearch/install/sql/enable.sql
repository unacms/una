
-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_elasticsearch', '_bx_elasticsearch', 'bx_elasticsearch@modules/boonex/elasticsearch/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_elasticsearch', '_bx_elasticsearch', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_elasticsearch_api_url', 'http://45.56.80.210:9200', @iCategId, '_bx_elasticsearch_option_api_url', 'digit', '', '', '', 1);


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
('bx_elasticsearch', 'BxElsResponse', 'modules/boonex/elasticsearch/classes/BxElsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('grid', 'get_data_by_filter', @iHandler),
('grid', 'get_data_by_conditions', @iHandler);
