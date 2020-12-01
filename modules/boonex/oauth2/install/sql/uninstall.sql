
DROP TABLE IF EXISTS `bx_oauth_access_tokens`, `bx_oauth_authorization_codes`, `bx_oauth_clients`, `bx_oauth_allowed_origins`, `bx_oauth_refresh_tokens`, `bx_oauth_scopes`;

-- Studio page and widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_oauth';
