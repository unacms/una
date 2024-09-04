-- PAGES
UPDATE `sys_objects_page` SET `url`='page.php?i=group-manage' WHERE `object`='bx_groups_manage_item';

UPDATE `sys_pages_blocks` SET `title`='_bx_groups_page_block_title_entries_of_author', `content`='a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"browse_created_entries";}' WHERE `object`='bx_groups_joined' AND `title_system`='_bx_groups_page_block_title_sys_entries_of_author';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_groups_joined' AND `title_system`='_bx_groups_page_block_title_sys_favorites_of_author';
