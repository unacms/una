UPDATE `sys_options` SET `value`='3' WHERE `name`='bx_persons_default_acl_level' LIMIT 1;

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_recent_profiles\";s:6:"params";a:1:{i:0;b:1;}}' WHERE `object`='bx_persons_home' AND `title`='_bx_persons_page_block_title_latest_profiles';

UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='bx_persons_view_actions' AND `name`='profile-set-acl-level' LIMIT 1;