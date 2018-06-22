<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Spaces profiles module.
 */

class BxCnlModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
       
        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_JOIN_CONFIRMATION'];
    }
   
    public function checkAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false)
    {
        return parent::_checkAllowedSubscribeAdd ($aDataEntry, $isPerformAction);
    }
    
    public function actionH($sName)
    {
        $iId = $this->_oDb->getChannelIdByName($sName);
        $_GET['id'] = $iId;
        $oPage = BxDolPage::getObjectInstance('bx_channels_view_profile');
        echo $oPage->getCode($sName);

    }
    
    function addChannel($sName)
    {
        //todo IdProfile
        $CNF = &$this->_oConfig->CNF;
        $this->serviceEntityAdd(6, array($CNF['FIELD_NAME'] => $sName));
    }
}

/** @} */
