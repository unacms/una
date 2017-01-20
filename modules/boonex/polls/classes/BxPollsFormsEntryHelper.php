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

/**
 * Entry forms helper functions
 */
class BxPollsFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function onDataDeleteAfter($iContentId, $aContentInfo, $oProfile)
    {
        $sResult = parent::onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile);
        if(!empty($sResult))
            return $sResult;

        $this->_oModule->_oDb->deleteSubentry(array(
            'entry_id' => $iContentId
        ));

        return '';
    }
}

/** @} */
