<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Moderation Notes for any content
 */
class BxDolCmtsNotes extends BxTemplCmts
{
    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
    }

    public function getObjectPrivacyView ($iObjectId = 0)
    {
        return BX_DOL_PG_HIDDEN;
    }

    /**
     * TODO: Remove this method when 'TriggerFieldComments' fields will be cleaned or updated 
     * in all modules which have Notes.
     */
    protected function _triggerComment()
    {
        return false;
    }
}

/** @} */
