<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Group profile forms functions
 */
class BxGroupsFormsEntryHelper extends BxBaseModProfileFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function _processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile)
    {
        $sMsg = parent::_processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile);
        if ($sMsg && 'c' == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']])
            return '';

        return $sMsg;
    }
}

/** @} */
