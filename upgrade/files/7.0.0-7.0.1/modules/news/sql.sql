

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'news\', \'featured_block_index\', array(0, 0, false));' WHERE `Page` = 'index' AND `Desc` = 'Show list of featured news' AND `Caption` = '_news_bcaption_featured' AND `Content` = 'return BxDolService::call(\'news\', \'featured_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'news\', \'archive_block_index\', array(0, 0, false));' WHERE `Page` = 'index' AND `Desc` = 'Show list of latest news' AND `Caption` = '_news_bcaption_latest' AND `Content` = 'return BxDolService::call(\'news\', \'archive_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'news\', \'featured_block_member\', array(0, 0, false));' WHERE `Page` = 'member' AND `Desc` = 'Show list of featured news' AND `Caption` = '_news_bcaption_featured' AND `Content` = 'return BxDolService::call(\'news\', \'featured_block\', array(0, 0, false));';

UPDATE `sys_page_compose` SET `Content` = 'return BxDolService::call(\'news\', \'archive_block_member\', array(0, 0, false));' WHERE `Page` = 'member' AND `Desc` = 'Show list of latest news' AND `Caption` = '_news_bcaption_latest' AND `Content` = 'return BxDolService::call(\'news\', \'archive_block\', array(0, 0, false));';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'news' AND `version` = '1.0.0';

