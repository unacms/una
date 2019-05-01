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

        $iId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iAuthorId = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];

        $aParams = array('object_author_id' => $iAuthorId);
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
            $aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];
        if(!empty($CNF['OBJECT_METATAGS']))
            $aParams['timeline_group'] = array(
                'by' => $this->_oModule->_oConfig->getName() . '_' . $iAuthorId . '_' . $iId,
                'field' => 'owner_id'
            );

        bx_alert($this->_oModule->getName(), ($aContentInfo[$CNF['FIELD_STATUS']] == 'awaiting' ? 'scheduled' : 'added'), $iId, $iAuthorId, $aParams);
    }
}

/** @} */
