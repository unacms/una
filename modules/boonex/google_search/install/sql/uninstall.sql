
DELETE FROM `sys_page_compose_pages` WHERE `Name` IN('bx_gsearch');
DELETE FROM `sys_page_compose` WHERE `Page` IN('bx_gsearch');
DELETE FROM `sys_page_compose` WHERE `Page` = 'search_home' AND `Desc` = 'Google Search';

DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=google_search/';

SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Google Search' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'bx_gsearch_permalinks';

DELETE FROM `sys_menu_admin` WHERE `name` = 'bx_gsearch';

DELETE FROM `sys_menu_top` WHERE `Parent` = 138 AND `Name` = 'Google Search';


