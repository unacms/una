<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxDolGridSubscriptions extends BxDolGridSubscribedMe
{
    protected $_sConnectionMethod = 'getConnectedContentAsSQLParts';

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _delete ($mixedId)
    {
        list($iId, $iViewedId) = $this->_prepareIds();

        if(!$this->_oConnection->isConnected($iViewedId, $iId))
            return true;

        return $this->_oConnection->removeConnection($iViewedId, $iId);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!isLogged() || !$this->_bOwner)
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
