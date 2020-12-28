
DROP TABLE IF EXISTS `bx_antispam_ip_table`, `bx_antispam_dnsbl_rules`, `bx_antispam_dnsbluri_zones`, `bx_antispam_disposable_email_domains`, `bx_antispam_block_log`;

-- Studio page and widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_antispam';
