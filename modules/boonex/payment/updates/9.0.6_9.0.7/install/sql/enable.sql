SET @sName = 'bx_payment';


-- MENUS
UPDATE `sys_menu_items` SET `name`='sbs-request-cancelation', `title_system`='_bx_payment_menu_item_title_system_sbs_request_cancelation', `title`='_bx_payment_menu_item_title_sbs_request_cancelation' WHERE `set_name`='bx_payment_menu_sbs_actions' AND `name`='sbs-cancel';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_payment_menu_sbs_actions' AND `name`='sbs-cancel';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_payment_menu_sbs_actions', 'bx_payment', 'sbs-cancel', '_bx_payment_menu_item_title_system_sbs_cancel', '_bx_payment_menu_item_title_sbs_cancel', 'javascript:void(0)', '{js_object}.cancel(this, {id}, \'{grid}\')', '_self', '', '', '', 2147483647, 0, 0, 1, 2);


-- ACL
SET @iIdActionManageAnyPurchase = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='manage any purchase' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionManageAnyPurchase;
DELETE FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='manage any purchase';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'manage any purchase', NULL, '_bx_payment_acl_action_manage_any_purchase', '', 1, 3);
SET @iIdActionManageAnyPurchase = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionManageAnyPurchase),
(@iAdministrator, @iIdActionManageAnyPurchase);
