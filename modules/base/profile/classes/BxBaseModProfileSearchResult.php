<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModProfileSearchResult extends BxBaseModGeneralSearchResult
{
    public function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
        $this->sCenterContentUnitSelector = '.bx-base-pofile-unit';
    }

    protected function _setConnectionsConditions ($aParams)
    {
        $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
        if (!$oConnection || !isset($aParams['profile']) || !(int)$aParams['profile'])
            return false;

        $sContentType = isset($aParams['type']) ? $aParams['type'] : BX_CONNECTIONS_CONTENT_TYPE_CONTENT;
        $isMutual = isset($aParams['mutual']) ? $aParams['mutual'] : false;
        $a = $oConnection->getConnectionsAsCondition ($sContentType, 'id', (int)$aParams['profile'], (int)$aParams['profile2'], $isMutual);

        $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
        $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

        return true;
    }
}

/** @} */
