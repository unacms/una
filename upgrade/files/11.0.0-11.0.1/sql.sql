
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
UPDATE `sys_options` SET `category_id` = @iCategoryId WHERE `name` = 'sys_account_activation_letter';

DELETE FROM `sys_options` WHERE `name` = 'sys_account_hide_unconfirmed_accounts';
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_account_hide_unconfirmed_accounts', '_adm_stg_cpt_option_sys_account_hide_unconfirmed_accounts', 'on', 'checkbox', '', '', '', 17);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_membership_stats';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard', 2, 'system', '', '_sys_page_block_title_membership_stats', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:24:"profile_membership_stats";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1, 0);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.1' WHERE (`version` = '11.0.0') AND `name` = 'system';
