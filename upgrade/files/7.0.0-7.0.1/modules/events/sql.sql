
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Joined Events' AND `Caption` = '_bx_events_block_joined_events';
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
('profile', '998px', 'Joined Events', '_bx_events_block_joined_events', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''events'', ''profile_block_joined'', array($this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0);


DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_events' AND `Caption` = '{TitleDelete}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_events' AND `Caption` = '{TitleJoin}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_events' AND `Caption` = '{AddToFeatured}';
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{TitleDelete}', 'modules/boonex/events/|action_block.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''delete/{ID}'';', '1', 'bx_events'),
    ('{TitleJoin}', 'modules/boonex/events/|user_add.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''join/{ID}/{iViewer}'';', '2', 'bx_events'),
    ('{AddToFeatured}', 'modules/boonex/events/|star__plus.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''mark_featured/{ID}'';', 6, 'bx_events');


UPDATE `sys_menu_top` SET `Link` = 'modules/?r=events/browse/user/{profileNick}|modules/?r=events/browse/joined/{profileNick}' WHERE `Name` = 'Events' AND `Parent` = 9 AND `Link` = 'modules/?r=events/browse/user/{profileNick}';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'events' AND `version` = '1.0.0';

