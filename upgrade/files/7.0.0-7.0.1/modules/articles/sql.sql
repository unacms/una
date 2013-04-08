
UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'articles\', \'featured_block_index\', array(0, 0, false));' WHERE `Page` = 'index' AND `Content` = 'return BxDolService::call(\'articles\', \'featured_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'articles\', \'archive_block_index\', array(0, 0, false));' WHERE `Page` = 'index' AND `Content` = 'return BxDolService::call(\'articles\', \'archive_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'articles\', \'featured_block_member\', array(0, 0, false));' WHERE `Page` = 'member' AND `Content` = 'return BxDolService::call(\'articles\', \'featured_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'articles\', \'archive_block_member\', array(0, 0, false));' WHERE `Page` = 'member' AND `Content` = 'return BxDolService::call(\'articles\', \'archive_block\', array(0, 0, false));';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'articles' AND `version` = '1.0.0';

