

UPDATE `sys_options` SET `AvailableValues` = 'last,popular' WHERE `Name` = '[db_prefix]_mode_index';
UPDATE `sys_options` SET `VALUE` = 'popular' WHERE `Name` = '[db_prefix]_mode_index' AND `VALUE` = 'top';


SET @iKatID := (SELECT `kateg` FROM  `sys_options` WHERE `Name` = '[db_prefix]_mode_index');
INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`)  VALUES
('[db_prefix]_uploader_switcher', 'flash,regular', @iKatID, 'Available uploaders', 'list', '', '', 12, 'flash,regular');


UPDATE `sys_page_compose` SET `Caption` = '_[db_prefix]_public' WHERE `Page` = '[db_prefix]_home' AND `Caption` = '_[db_prefix]_all' AND `Func` = 'All';


UPDATE `sys_page_compose` SET `Content` = '$aVisible[] = BX_DOL_PG_ALL;\r\n if ($this->iMemberID > 0) \r\n$aVisible[] = BX_DOL_PG_MEMBERS;\r\n return BxDolService::call(''files'', ''get_files_block'', array(array(''allow_view''=>$aVisible), array(''menu_top''=>true, ''sorting''=>getParam(''[db_prefix]_mode_index''), ''per_page''=>getParam(''[db_prefix]_number_index''))), ''Search'');' WHERE `Page` = 'index' AND `Desc` = 'Public Files' AND `Caption` = '_[db_prefix]_public' AND `Content` = '$aVisible[] = BX_DOL_PG_ALL;\r\n if ($this->iMemberID > 0) \r\n$aVisible[] = BX_DOL_PG_MEMBERS;\r\n return BxDolService::call(''files'', ''get_files_block'', array(array(''allow_view''=>$aVisible)), ''Search'');';


UPDATE `sys_email_templates` SET `Subject` = 'Someone from <SiteName> shared file with you' WHERE `Name` = 't_[db_prefix]_share' AND `Subject` = 'Someone from <SiteName> file with you';


UPDATE `[db_prefix]_types` SET `Icon` = '006.png' WHERE `Type` = 'application/octet-stream';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'files' AND `version` = '1.0.0';

