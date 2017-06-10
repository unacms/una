
DROP TABLE IF EXISTS `bx_antispam_ip_table`, `bx_antispam_dnsbl_rules`, `bx_antispam_dnsbluri_zones`, `bx_antispam_disposable_email_domains`, `bx_antispam_block_log`;

-- Studio page and widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_antispam';

