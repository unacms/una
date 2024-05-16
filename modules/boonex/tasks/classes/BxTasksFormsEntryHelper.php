<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxTasksFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function redirectAfterDelete($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT;
        if((int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] < 0)
            $sUrl = 'page.php?i=' . $CNF['URI_ENTRIES_BY_CONTEXT'] . '&profile_id=' . abs($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);

        $this->_redirectAndExit($sUrl, true, array(
            'account_id' => getLoggedId(),
            'profile_id' => bx_get_logged_profile_id(),
        ));
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $s = parent::onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile);
        if(!empty($s))
            return $s;

        $CNF = &$this->_oModule->_oConfig->CNF;
        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        $oConnection->onDeleteContent($iContentId);
        return '';
    }
}

/** @} */
