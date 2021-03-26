<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry all actions menu
 */
class BxBaseModTextMenuViewActions extends BxBaseModGeneralMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemApprove($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($CNF['FIELD_STATUS_ADMIN']))
            return false;

        $iContentId = $this->_iContentId;
        $aContentInfo = $this->_aContentInfo;
        if(!empty($aParams['id'])) {
            $iContentId = (int)$aParams['id'];
            $aContentInfo = $this->_oModule->_oDb->getContentInforById($iContentId);
        }

        if($aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != BX_BASE_MOD_TEXT_STATUS_PENDING)
            return false;

        if(!$this->_oModule->_isModerator())
            return false;

        $this->addMarkers(array(
            'content_id' => $iContentId
        ));

        return true;
    }
}

/** @} */
