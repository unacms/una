-- OPTIONS
UPDATE `sys_options` SET `caption`='_bx_smtp_option_on', `order`='10' WHERE `name`='bx_smtp_on' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_auth', `order`='20' WHERE `name`='bx_smtp_auth' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_username', `order`='30' WHERE `name`='bx_smtp_username' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_password', `order`='40' WHERE `name`='bx_smtp_password' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_host', `order`='50' WHERE `name`='bx_smtp_host' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_port', `order`='60' WHERE `name`='bx_smtp_port' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_secure', `order`='70' WHERE `name`='bx_smtp_secure' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_from_name', `order`='80' WHERE `name`='bx_smtp_from_name' LIMIT 1;
UPDATE `sys_options` SET `caption`='_bx_smtp_option_from_email', `order`='90' WHERE `name`='bx_smtp_from_email' LIMIT 1;

DELETE FROM `sys_options` WHERE `name` IN ('bx_smtp_allow_selfsigned', 'bx_smtp_send_attachments');
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'bx_smtp_general');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_smtp_allow_selfsigned', '', @iCategId, '_bx_smtp_option_allow_selfsigned', 'checkbox', '', '', 74, '');