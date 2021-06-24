-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_view_profile' AND `title`='_bx_channels_page_block_title_profile_subscribed_me';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_channels_view_profile', 4, 'bx_channels', '', '_bx_channels_page_block_title_profile_subscribed_me', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:21:\"profile_subscribed_me\";}', '', 0, 0, 1, 1, 0);

-- ACL
UPDATE `sys_acl_actions` SET `Desc`='_bx_channels_acl_action_create_channel_auto_info' WHERE `Module`='bx_channels' AND `Name`='create channel auto';
