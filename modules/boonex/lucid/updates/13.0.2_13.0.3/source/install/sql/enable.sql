SET @sName = 'bx_lucid';


-- MENU: dropdown menu
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(ROUND(RAND()*(9999 - 1000) + 1000), 'menu_dropdown_site.html', '_bx_lucid_menu_template_title_dropdown_site', 1);
SET @iTemplId = (SELECT `id` FROM `sys_menu_templates` WHERE `template`='menu_dropdown_site.html' AND `title`='_bx_lucid_menu_template_title_dropdown_site' LIMIT 1);

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_lucid_dropdown_site', '_bx_lucid_menu_title_dropdown_site', 'sys_site', @sName, @iTemplId, 0, 1, 'BxTemplMenuDropdownSite', '');

-- MENU: member toolbar
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_toolbar_member' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `hidden_on`, `active`, `copyable`, `order`) VALUES
('sys_toolbar_member', 'system', 'bx_lucid_search', '_bx_lucid_menu_item_title_system_search', '', 'javascript:void(0);', 'bx_menu_slide_inline(''#bx-sliding-menu-search'', this, ''site'');', '', 'search', '', '', 0, 2147483647, 7, 1, 0, 0);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxLucidAlertsResponse', 'modules/boonex/lucid/classes/BxLucidAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('system', 'change_logo', @iHandler);
