SET @sName = 'bx_contact';


-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_contact_contact', '_bx_contact_page_title_sys_contact', '_bx_contact_page_title_contact', @sName, 5, 2147483647, 1, 'contact', 'page.php?i=contact', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_contact_contact', 1, @sName, '_bx_contact_page_block_title_form', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_contact";s:6:"method";s:14:"get_block_form";}', 0, 1, 1);


-- MENU: FOOTER
SET @iMIOrder = (SELECT MAX(`order`) FROM `sys_menu_items` WHERE `set_name`='sys_footer' AND `order` < 9999 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_footer', @sName, 'contact', '_bx_contact_menu_item_title_system_contact', '_bx_contact_menu_item_title_contact', 'page.php?i=contact', '', '', '', '', 2147483647, 1, 1, @iMIOrder + 1);


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_contact', 'bx_contact@modules/boonex/contact/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_contact', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_contact_email', '', @iCategId, '_bx_contact_option_email', 'digit', '', '', '', 1),
('bx_contact_add_reply_to', '', @iCategId, '_bx_contact_option_add_reply_to', 'checkbox', '', '', '', 20);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'contact', NULL, '_bx_contact_acl_action_contact', '', 1, 0);
SET @iIdActionContact = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- contact
(@iUnauthenticated, @iIdActionContact),
(@iAccount, @iIdActionContact),
(@iStandard, @iIdActionContact),
(@iUnconfirmed, @iIdActionContact),
(@iPending, @iIdActionContact),
(@iSuspended, @iIdActionContact),
(@iModerator, @iIdActionContact),
(@iAdministrator, @iIdActionContact),
(@iPremium, @iIdActionContact);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_contact_et_contact_form_message', 'bx_contact_contact_form_message', '_bx_contact_et_contact_form_message_subject', '_bx_contact_et_contact_form_message_body');
