<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTimelineMenuItemActions.php');

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemManage extends BxTimelineMenuItemActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->setDynamicMode(true);

        $iContentId = 0;
        if(bx_get('content_id') !== false)
            $iContentId = bx_process_input(bx_get('content_id'), BX_DATA_INT);

        $aBrowseParams = array('name' => '', 'view' => '', 'type' => '');
        foreach($aBrowseParams as $sKey => $sValue)
            if(bx_get($sKey) !== false)
                $aBrowseParams[$sKey] = $this->_oModule->_oConfig->prepareParam($sKey);

        $this->setEventById($iContentId, $aBrowseParams);

        $this->_bShowTitles = true;
        $this->_bShowCounters = true;
        $this->_sTmplNameItem = 'menu_custom_item_ver.html';
        
        $this->addMarkers(array(
            'module' => $this->_oModule->_oConfig->getName(),
            'module_uri' => $this->_oModule->_oConfig->getUri(),
        ));
    }
}

/** @} */
