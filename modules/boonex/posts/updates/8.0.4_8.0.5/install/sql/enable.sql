UPDATE `sys_options` SET `value`='240' WHERE `name`='bx_posts_plain_summary_chars' LIMIT 1;


UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_location';
UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_author';
UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_actions';


UPDATE `sys_objects_page` SET `layout_id`='2' WHERE `object`='bx_posts_home' LIMIT 1;

UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_recent_entries' LIMIT 1;
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_popular_keywords' LIMIT 1;
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_home', 2, 'bx_posts', '_bx_posts_page_block_title_popular_keywords', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"keywords_cloud";s:6:"params";a:2:{i:0;s:8:"bx_posts";i:1;s:8:"bx_posts";}s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 1, 0);


DELETE FROM `tah`, `ta`
USING `sys_alerts_handlers` AS `tah` LEFT JOIN `sys_alerts` AS `ta` ON `tah`.`id`=`ta`.`handler_id`
WHERE `tah`.`name`='bx_posts';

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES ('bx_posts', 'BxPostsAlertsResponse', 'modules/boonex/posts/classes/BxPostsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'delete', @iHandler);