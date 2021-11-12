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

class BxAdsFormOffer extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;
	
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_ads';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_AUTHOR']]))
            $aValsToAdd[$CNF['FIELD_OFR_AUTHOR']] = bx_get_logged_profile_id();

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_ADDED']]))
            $aValsToAdd[$CNF['FIELD_OFR_ADDED']] = time();

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_CHANGED']]))
            $aValsToAdd[$CNF['FIELD_OFR_CHANGED']] = time();

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }
}

/** @} */
