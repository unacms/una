-- MENU
DELETE FROM `sys_objects_menu` WHERE `object`='bx_convos_view_popup';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_convos_view_popup', '_bx_cnv_menu_title_view_entry_popup', '', 'bx_convos', 16, 0, 1, 'BxCnvMenuViewActions', 'modules/boonex/convos/classes/BxCnvMenuViewActions.php');

UPDATE `sys_menu_items` SET `icon`='envelope', `visible_for_levels`='2147483646' WHERE `set_name`='trigger_profile_view_actions' AND `name`='convos-compose';
