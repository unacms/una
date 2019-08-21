

UPDATE `sys_objects_vote` SET `TriggerFieldAuthor` = 'author_id' WHERE `Name` = 'sys_cmts';


DELETE FROM `sys_pages_blocks` WHERE `object` = 'system' AND `title_system` = '_sys_page_block_title_sys_create_post';

SET @iBlockOrder = IFNULL((SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1), 0);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'system', '_sys_page_block_title_sys_create_post', '_sys_page_block_title_create_post', 3, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_create_post_form";s:6:"params";a:1:{i:0;i:0;}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, @iBlockOrder + 1);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.0.0-RC2' WHERE (`version` = '10.0.0.RC1' OR `version` = '10.0.0-RC1') AND `name` = 'system';

