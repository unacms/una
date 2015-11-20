
-- settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_smtp', '_bx_smtp_adm_stg_cpt_type', 'bx_smtpmailer@modules/boonex/smtpmailer/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_smtp_general', '_bx_smtp_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_smtp_on', '', @iCategId, 'Enable SMTP mailer', 'checkbox', '', '', 1, ''),
('bx_smtp_auth', '', @iCategId, 'SMTP authentication (Is your SMTP server requires username and password?)', 'checkbox', '', '', 2, ''),
('bx_smtp_username', '', @iCategId, 'SMTP username (only if SMTP authentication is enabled)', 'digit', '', '', 3, ''),
('bx_smtp_password', '', @iCategId, 'SMTP password (only if SMTP authentication is enabled)', 'digit', '', '', 4, ''),
('bx_smtp_host', '', @iCategId, 'SMTP server name or IP address', 'digit', '', '', 5, ''),
('bx_smtp_port', '25', @iCategId, 'SMTP server port number (25 - default, 465 - for secure ssl connection, 587 - for secure tls connection)', 'digit', '', '', 6, ''),
('bx_smtp_secure', 'Not Secure', @iCategId, 'Is your SMTP server requires secure connection', 'select', '', '', 7, 'Not Secure,SSL,TLS'), 
('bx_smtp_from_name', '', @iCategId, '''From'' name of the message', 'digit', '', '', 8, ''),
('bx_smtp_from_email', '', @iCategId, 'Override default sender email address', 'digit', '', '', 9, ''),
('bx_smtp_send_attachments', '', @iCategId, 'Attach every outgoing email all files from ''modules/boonex/smtpmailer/data/attach/'' folder', 'checkbox', '', '', 10, '');

-- alerts

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_smtp', 'BxSMTPAlertsResponse', 'modules/boonex/smtpmailer/classes/BxSMTPAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'before_send_mail', @iHandler);

