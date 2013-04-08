

INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`)  VALUES
('[db_prefix]_uploader_switcher', 'flash,regular,record,embed', @iKatID, 'Available uploaders', 'list', '', '', 41, 'flash,regular,record,embed');

UPDATE `sys_options` SET `desc` = 'Width of video player (in pixels)' WHERE `Name` = '[db_prefix]_file_width' AND `desc` = 'Max width of video player (in pixels)';
UPDATE `sys_options` SET `desc` = 'Height of video player (in pixels)' WHERE `Name` = '[db_prefix]_file_height' AND `desc` = 'Max height of video player (in pixels)';
UPDATE `sys_options` SET `desc` = 'Width of video browse unit (in pixels)' WHERE `Name` = '[db_prefix]_browse_width' AND `desc` = 'Max width of video thumb (in pixels)';
UPDATE `sys_options` SET `desc` = 'Height of video browse unit (in pixels)' WHERE `Name` = '[db_prefix]_browse_height' AND `desc` = 'Max height of video thumb (in pixels)';


UPDATE `sys_page_compose` SET `Caption` = '_[db_prefix]_public' WHERE `Caption` = '_[db_prefix]_all' AND `Page` = '[db_prefix]_home' AND `Func` = 'All';

UPDATE `sys_page_compose` SET `Content` = 'require_once(BX_DIRECTORY_PATH_MODULES . ''boonex/videos/classes/BxVideosSearch.php'');\r\n $oMedia = new BxVideosSearch();\r\n $aVisible[] = BX_DOL_PG_ALL;\r\n if ($this->iMemberID > 0)\r\n $aVisible[] = BX_DOL_PG_MEMBERS;\r\n $aCode = $oMedia->getBrowseBlock(array(''allow_view''=>$aVisible), array(''menu_top''=>true, ''sorting'' => getParam(''[db_prefix]_mode_index''), ''per_page''=>(int)getParam(''[db_prefix]_number_index'')));\r\n return array($aCode[''code''], $aCode[''menu_top''], $aCode[''menu_bottom''], $aCode[''wrapper'']);' WHERE `Page` = 'index' AND `Desc` = 'Public Videos' AND `Content` = 'require_once(BX_DIRECTORY_PATH_MODULES . ''boonex/videos/classes/BxVideosSearch.php'');\r\n $oMedia = new BxVideosSearch();\r\n $aVisible[] = BX_DOL_PG_ALL;\r\n if ($this->iMemberID > 0)\r\n $aVisible[] = BX_DOL_PG_MEMBERS;\r\n $aCode = $oMedia->getBrowseBlock(array(''allow_view''=>$aVisible), array(''menu_top''=>true, ''per_page''=>(int)getParam(''[db_prefix]_number_index'')));\r\n return array($aCode[''code''], $aCode[''menu_top''], $aCode[''menu_bottom''], $aCode[''wrapper'']);';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'videos' AND `version` = '1.0.0';

