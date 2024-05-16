SET @sName = 'bx_forum';


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:14:\"browse_popular\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`=@sName AND `title_system`='_bx_forum_page_block_title_popular_entries_view_gallery';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:15:\"browse_featured\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`=@sName AND `title_system`='_bx_forum_page_block_title_featured_entries_view_gallery';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:13:\"browse_latest\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`=@sName AND `title_system`='_bx_forum_page_block_title_latest_entries_view_gallery';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:10:\"browse_new\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`=@sName AND `title_system`='_bx_forum_page_block_title_new_entries_view_gallery';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:10:\"browse_top\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:1;}}' WHERE `module`=@sName AND `title_system`='_bx_forum_page_block_title_top_entries_view_gallery';
