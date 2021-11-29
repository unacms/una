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

class BxTimelineMenuFeeds extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $this->_oModule->_oTemplate);
    }

    public function setBrowseParams($aParams = [])
    {
        $aMarkers = array();
        foreach($aParams as $sKey => $mixedValue)
            if(!is_array($mixedValue))
                $aMarkers[$sKey] = $mixedValue;

        $this->addMarkers($aMarkers);
        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObjectView($aParams)
        ));
    }

    protected function _getMenuItemItemDivider($aItem)
    {
        return $this->_oModule->_oTemplate->parseHtmlByName('menu_item_divider.html', []);
    }
}

/** @} */
