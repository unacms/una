-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:24:\"browse_familiar_profiles\";s:6:\"params\";a:4:{s:10:\"connection\";s:20:\"sys_profiles_friends\";s:9:\"unit_view\";s:4:\"unit\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`='bx_persons' AND `title_system`='_bx_persons_page_block_title_sys_familiar_profiles';


-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=account-settings-delete&id={account_id}&content=0' WHERE `set_name`='bx_persons_view_actions_more' AND `name`='delete-persons-account';
