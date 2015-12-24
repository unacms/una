
DROP TABLE IF EXISTS `bx_oauth_access_tokens`, `bx_oauth_authorization_codes`, `bx_oauth_clients`, `bx_oauth_refresh_tokens`, `bx_oauth_scopes`;

-- Studio page and widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_oauth';

