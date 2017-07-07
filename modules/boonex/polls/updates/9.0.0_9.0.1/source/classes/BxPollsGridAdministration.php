<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxPollsGridAdministration extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_polls';
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getCellText($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oConfig->getTitle($aRow);
        $mixedValue = $this->_getEntryLink(strmaxtextlen($mixedValue, $aField['chars_limit']), $aRow);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
}

/** @} */
