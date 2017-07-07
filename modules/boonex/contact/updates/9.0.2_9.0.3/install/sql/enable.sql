SET @sName = 'bx_contact';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='' WHERE `set_name`='sys_footer' AND `name`='contact';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_contact_send_from_senders_email');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_contact_send_from_senders_email', '', @iCategId, '_bx_contact_option_send_from_senders_email', 'checkbox', '', '', '', 20);
