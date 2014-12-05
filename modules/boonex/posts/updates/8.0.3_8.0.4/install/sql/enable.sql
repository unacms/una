UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_posts_popular' AND `title`='_bx_posts_page_block_title_popular_entries';
UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_posts_author' AND `title`='_bx_posts_page_block_title_entries_of_author';
UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='sys_home' AND `title`='_bx_posts_page_block_title_recent_entries';


UPDATE `sys_menu_items` SET `visible_for_levels`='2147483646' WHERE `set_name`='bx_posts_submenu' AND `name`='posts-manage';


DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_posts_administration', 'bx_posts_moderation', 'bx_posts_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_posts_administration', 'bulk', 'delete', '_bx_posts_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_posts_administration', 'single', 'edit', '_bx_posts_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_posts_administration', 'single', 'delete', '_bx_posts_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_posts_administration', 'single', 'settings', '_bx_posts_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_posts_moderation', 'single', 'edit', '_bx_posts_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_posts_moderation', 'single', 'settings', '_bx_posts_grid_action_title_adm_more_actions', 'cog', 1, 0, 2),
('bx_posts_common', 'bulk', 'delete', '_bx_posts_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_posts_common', 'single', 'edit', '_bx_posts_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_posts_common', 'single', 'delete', '_bx_posts_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_posts_common', 'single', 'settings', '_bx_posts_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);