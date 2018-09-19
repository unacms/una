<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MassMailer Mass Mailer
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxMassMailerFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }
    
    public function editDataForm ($iContentId, $sDisplay = false, $sCheckFunction = false, $bErrorMsg = true)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $CNF['TABLE_ENTRIES'] = $CNF['TABLE_CAMPAIGNS'];
        return parent::editDataForm($iContentId, $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'], $sCheckFunction = false, $bErrorMsg = true);
    }
    
    public function onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_MANAGE_CAMPAIGNS']);
        return parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
    }
    
    public function onDataAddAfter($iAccountId, $iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_redirectAndExit('page.php?i=' . $CNF['URI_MANAGE_CAMPAIGNS']);
    }
}

/** @} */
