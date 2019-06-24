<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/*
 * Outcoming connections. A profile connects another profiles.
 */
class BxDolGridConnectionOut extends BxDolGridConnectionIn
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sConnectionMethod = 'getConnectedContentAsSQLParts';
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!isLogged() || !$this->_bOwner)
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _delete ($mixedId)
    {
        list($iId, $iViewedId) = $this->_prepareIds();

        if(!$this->_oConnection->isConnected($iViewedId, $iId))
            return true;

        return $this->_oConnection->removeConnection($iViewedId, $iId);
    }
}

/** @} */
