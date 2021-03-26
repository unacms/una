<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxAdsPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';

        parent::__construct($aObject, $oTemplate);
    }

    public function getCode ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iEntryAuthor = (int)$this->_aContentInfo[$CNF['FIELD_AUTHOR']];
        $sEntryStatus = $this->_aContentInfo[$CNF['FIELD_STATUS_ADMIN']];
        if(($iEntryAuthor == bx_get_logged_profile_id() || $this->_oModule->_isModerator()) && $sEntryStatus != BX_BASE_MOD_TEXT_STATUS_ACTIVE)
            BxDolInformer::getInstance($this->_oModule->_oTemplate)->add('bx-ads-entry-' . $sEntryStatus, _t('_bx_ads_txt_msg_status_' . $sEntryStatus), BX_INFORMER_ALERT);

        $sResult = parent::getCode();
        if(!empty($sResult))
            $sResult .= $this->_oModule->_oTemplate->getJsCode('entry');

        $this->_oModule->_oTemplate->addCss(array('entry.css'));
        $this->_oModule->_oTemplate->addJs(array('entry.js'));
        return $sResult;
    }

    protected function _setSubmenu($aParams)
    {
        parent::_setSubmenu(array_merge($aParams, array(
            'title' => '',
            'icon' => ''
        )));
    }
}

/** @} */
