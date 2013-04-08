SET @sName = 'bx_articles';

--
-- Studio page and widget.
--
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id`=`tw`.`page_id` AND `tw`.`id`=`tpw`.`widget_id` AND `tp`.`name`=@sName;

DROP TABLE IF EXISTS `bx_arl_entries`;
DROP TABLE IF EXISTS `bx_arl_comments`;
DROP TABLE IF EXISTS `bx_arl_comments_track`;
DROP TABLE IF EXISTS `bx_arl_voting`;
DROP TABLE IF EXISTS `bx_arl_voting_track`;
DROP TABLE IF EXISTS `bx_arl_views_track`;


DELETE FROM `sys_categories` WHERE `Type`=@sName;


DELETE FROM `sys_sbs_entries` USING `sys_sbs_types`, `sys_sbs_entries` WHERE `sys_sbs_types`.`id`=`sys_sbs_entries`.`subscription_id` AND `sys_sbs_types`.`unit`=@sName;
DELETE FROM `sys_sbs_types` WHERE `unit`=@sName;