SET @sName = 'bx_charts';;

DROP TABLE IF EXISTS `bx_charts_top_by_likes`, `bx_charts_most_active_profiles`, `bx_charts_most_followed_profiles`;

-- STUDIO WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;


