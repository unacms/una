<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxRibbonsFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_redirectAndExit($CNF['URL_MANAGE']);
        return parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
    }

    public function onDataAddAfter($iAccountId, $iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_redirectAndExit($CNF['URL_MANAGE']);
    }
}

/** @} */