
-- page compose pages
SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_gsearch', 'Google Search', @iMaxOrder);

-- page compose blocks
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
    ('bx_gsearch', '998px', 'Search Form', '_bx_gsearch_box_title_search_form', '1', '0', 'SearchForm', '', '1', '30', 'non,memb', '0'),
    ('bx_gsearch', '998px', 'Search Results', '_bx_gsearch_box_title_search_results', '2', '0', 'SearchResults', '', '1', '70', 'non,memb', '0'),
    ('search_home', '998px', 'Google Search', '_bx_gsearch_box_title', 0, 0, 'PHP', 'return BxDolService::call(''google_search'', ''get_search_control'', array());', 1, 66, 'non,memb', 0);

-- permalinks
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=google_search/', 'm/google_search/', 'bx_gsearch_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Google Search', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('bx_gsearch_permalinks', 'on', 26, 'Enable friendly permalinks in Google Site Search', 'checkbox', '', '', '0', ''),
('bx_gsearch_block_tabbed', 'on', @iCategId, 'Tabbed search results in block', 'checkbox', '', '', '0', ''),
('bx_gsearch_block_images', 'on', @iCategId, 'Images search in block', 'checkbox', '', '', '0', ''),
('bx_gsearch_separate_tabbed', 'on', @iCategId, 'Tabbed search results on separate page', 'checkbox', '', '', '0', ''),
('bx_gsearch_separate_images', 'on', @iCategId, 'Images search on separate page', 'checkbox', '', '', '0', '');


-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'bx_gsearch', '_bx_gsearch', '{siteUrl}modules/?r=google_search/administration/', 'Google Site Search module by BoonEx', 'modules/boonex/google_search/|google.png', @iMax+1);

-- top menu
SET @iCatOrder := (SELECT MAX(`Order`)+1 FROM `sys_menu_top` WHERE `Parent` = 138 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 138, 'Google Search', '_bx_gsearch_menu_title', 'modules/?r=google_search/', @iCatOrder, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'bx_n_search_comm.png', '', 0, '');