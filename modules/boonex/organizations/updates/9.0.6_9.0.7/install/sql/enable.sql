-- SETTINGS
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_organizations_num_connections_quick';
UPDATE `sys_options` SET `value`='24' WHERE `name`='bx_organizations_per_page_browse';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_organizations_per_page_browse_showcase';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_organizations_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 15);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_organizations' AND `title` IN ('_bx_orgs_page_block_title_featured_entries_view_showcase');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_organizations', '_bx_orgs_page_block_title_sys_featured_entries_view_showcase', '_bx_orgs_page_block_title_featured_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"browse_featured\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_organizations';


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_organizations_meta_mentions' WHERE `object`='bx_organizations';


-- EMAIL TEMPLATES
UPDATE `sys_email_templates` SET `NameSystem`='_bx_orgs_email_friend_request', `Subject`='_bx_orgs_email_friend_request_subject', `Body`='_bx_orgs_email_friend_request_body' WHERE `Name`='bx_organizations_friend_request';
