SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_view_channels";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_channels';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_feed_and_hot";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_feed_and_hot';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_contexts";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_contexts_groups';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:26:"get_block_view_media_files";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_media_files';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_media_images";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_media_images';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_media_videos";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_media_videos';
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:20:"get_block_view_media";}' WHERE `module`='bx_timeline' AND `title_system`='_bx_timeline_page_block_title_system_view_media_any';
