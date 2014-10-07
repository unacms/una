UPDATE `sys_options` SET `value`='3' WHERE `name`='bx_organizations_default_acl_level' LIMIT 1;

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:22:\"browse_recent_profiles\";s:6:"params";a:1:{i:0;b:1;}}' WHERE `object`='bx_organizations_home' AND `title`='_bx_orgs_page_block_title_latest_profiles';

UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-set-acl-level' LIMIT 1;