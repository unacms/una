

DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Joined Groups' AND `Caption` = '_bx_groups_block_my_groups_joined';
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
('profile', '998px', 'Joined Groups', '_bx_groups_block_my_groups_joined', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''groups'', ''profile_block_joined'', array($this->oProfileGen->_iProfileID));', 1, 34, 'non,memb', 0);


DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_groups' AND `Caption` = '{TitleDelete}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_groups' AND `Caption` = '{TitleJoin}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_groups' AND `Caption` = '{AddToFeatured}';
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{TitleDelete}', 'modules/boonex/groups/|action_block.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''delete/{ID}'';', '1', 'bx_groups'),
    ('{TitleJoin}', 'modules/boonex/groups/|user_add.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''join/{ID}/{iViewer}'';', '4', 'bx_groups'),
    ('{AddToFeatured}', 'modules/boonex/groups/|star__plus.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''mark_featured/{ID}'';', '6', 'bx_groups');


UPDATE `sys_menu_top` SET `Link` = 'modules/?r=groups/browse/user/{profileNick}|modules/?r=groups/browse/joined/{profileNick}' WHERE `Parent` = 9 AND `Name` = 'Groups' AND `Caption` = '_bx_groups_menu_my_groups_profile' AND `Link` = 'modules/?r=groups/browse/user/{profileNick}';


UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'groups' AND `version` = '1.0.0';

