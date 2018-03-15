<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxPostsFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function _alertAfterAdd($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aParams = array('object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
        	$aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        bx_alert($this->_oModule->getName(), ($aContentInfo[$CNF['FIELD_STATUS']] == 'awaiting' ? 'scheduled' : 'added'), $aContentInfo[$CNF['FIELD_ID']], $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }
}

/** @} */
