
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_store' AND `Caption` = '{TitleDelete}';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_store' AND `Caption` = '{AddToFeatured}';
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
('{TitleDelete}', 'modules/boonex/store/|action_block.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxStoreModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''delete/{ID}'';', '1', 'bx_store'),
('{AddToFeatured}', 'modules/boonex/store/|star__plus.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxStoreModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''mark_featured/{ID}'';', 6, 'bx_store');

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'store' AND `version` = '1.0.0';

