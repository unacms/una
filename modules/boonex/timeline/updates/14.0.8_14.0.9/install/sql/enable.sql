SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_view_contexts";}' WHERE `module`=@sName AND `title_system`='_bx_timeline_page_block_title_system_view_contexts_groups';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_filters_contexts_hide';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_filters_contexts_hide', '', @iCategId, '_bx_timeline_option_filters_contexts_hide', 'rlist', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:28:"get_options_filters_contexts";}', 70);
