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

class BxTimelineGridMute extends BxDolGridConnections
{
    protected $_sModule;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sObjectConnections = $this->_oModule->_oConfig->getObject('grid_mute');
    }

    public function init()
    {
        if(!parent::init())
            return false;

        $aSQLParts = $this->_oConnection->getConnectedContentAsSQLParts('p', 'id', $this->_oProfile->id());

        $this->addMarkers(array(
            'join_connections' => $aSQLParts['join']
        ));

        return true;
    }

    protected function _getCellInfo ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($aRow['added'], BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _delete ($mixedId)
    {
        if(!$this->_bOwner)
            return false;
        
        if(!is_array($mixedId))
            $mixedId = array($mixedId);

        $iProfileId = $this->_oProfile->id();
        foreach($mixedId as $iId)
            if($this->_oConnection->isConnected($iProfileId, $iId))
                $this->_oConnection->removeConnection($iProfileId, $iId);

        return true;
    }
}

/** @} */
