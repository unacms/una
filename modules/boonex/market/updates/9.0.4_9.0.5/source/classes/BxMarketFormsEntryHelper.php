<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxMarketFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function onDataAddAfter($iAccountId, $iContentId)
    {
        $s = parent::onDataAddAfter($iAccountId, $iContentId);
        if(!empty($s))
            return $s;

        $this->associateSubentries($iContentId, bx_get('subentries'));

        return '';
    }

    public function onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
        if(!empty($s))
            return $s;

        $this->associateSubentries($iContentId, bx_get('subentries'));

        return '';
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBENTRIES']);
        if(!$oConnection)
            return '';

        $oConnection->onDeleteInitiatorAndContent($iContentId);

        return '';
    }

    protected function associateSubentries($iContentId, $aSubentries)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($aSubentries) || !is_array($aSubentries))
            return false;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBENTRIES']);
        if(!$oConnection)
            return false;

        $oConnection->onDeleteInitiator((int)$iContentId);

        $iProcessed = 0;
        foreach($aSubentries as $iSubentry) {
            if(!$oConnection->addConnection((int)$iContentId, (int)$iSubentry))
                continue;

            $iProcessed += 1;
        }

        return count($aSubentries) == $iProcessed;
    }
}

/** @} */
