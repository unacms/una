
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_account_remember_me', '_adm_stg_cpt_option_sys_account_remember_me', 'on', 'checkbox', '', '', '', 70);

-- Pages

DELETE FROM `sys_pages_blocks` WHERE `module` = 'system' AND `title_system` = '_sys_page_block_title_system_cmts_view_content';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_cmts_view', 1, 'system', '_sys_page_block_title_system_cmts_view_content', '_cmt_page_view_title_content', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_block_content";s:6:"params";a:3:{i:0;s:8:"{system}";i:1;s:11:"{object_id}";i:2;s:12:"{comment_id}";}s:5:"class";s:17:"TemplCmtsServices";}', 0, 0, 1, 1, 0);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-RC1' WHERE (`version` = '14.0.0.B2' OR `version` = '14.0.0-B2') AND `name` = 'system';

