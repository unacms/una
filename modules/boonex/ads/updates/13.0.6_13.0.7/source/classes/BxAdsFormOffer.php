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

    protected $_iContentId;
    protected $_aContentInfo;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_ads';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(($iContentId = bx_get('content_id')) !== false) {
            $this->_iContentId = (int)$iContentId;
            if($this->_iContentId)
                $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        }

        if((int)$this->_aContentInfo[$CNF['FIELD_SINGLE']] == 1) {
            if(isset($this->aInputs[$CNF['FIELD_OFR_QUANTITY']]))
                $this->aInputs[$CNF['FIELD_OFR_QUANTITY']] = array_merge($this->aInputs[$CNF['FIELD_OFR_QUANTITY']], [
                    'type' => 'hidden',
                    'value' => 1
                ]);

            if(isset($this->aInputs[$CNF['FIELD_OFR_TOTAL']]))
                unset($this->aInputs[$CNF['FIELD_OFR_TOTAL']]);
        }

        if(isset($this->aInputs[$CNF['FIELD_OFR_AMOUNT']], $this->aInputs[$CNF['FIELD_OFR_QUANTITY']]) && $this->aInputs[$CNF['FIELD_OFR_QUANTITY']]['type'] != 'hidden') {
            $sJsObject = $this->_oModule->_oConfig->getJsObject('form_offer');
            $aMask = ['mask' => "javascript:%s.updateTotal(this, '%s', '%s', '%s');", $sJsObject, $CNF['FIELD_OFR_AMOUNT'], $CNF['FIELD_OFR_QUANTITY'], $CNF['FIELD_OFR_TOTAL']];

            $sOnBlur = call_user_func_array('sprintf', array_values($aMask)); 
            $this->aInputs[$CNF['FIELD_OFR_AMOUNT']]['attrs']['onblur'] = $sOnBlur;
            $this->aInputs[$CNF['FIELD_OFR_QUANTITY']]['attrs']['onblur'] = $sOnBlur;
        }
    }

    public function getCode($bDynamicMode = false)
    {
        $sJs = $this->_oModule->_oTemplate->addJs(['form_offer.js'], $bDynamicMode);

        $sCode = '';
        if($bDynamicMode)
            $sCode .= $sJs;

        $sCode .= $this->_oModule->_oTemplate->getJsCode('form_offer');
        $sCode .= parent::getCode($bDynamicMode);

        return $sCode;
    }

    public function insert($aValsToAdd = [], $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_AUTHOR']]))
            $aValsToAdd[$CNF['FIELD_OFR_AUTHOR']] = bx_get_logged_profile_id();

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_ADDED']]))
            $aValsToAdd[$CNF['FIELD_OFR_ADDED']] = time();

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    public function update($iContentId, $aValsToAdd = [], &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($aValsToAdd[$CNF['FIELD_OFR_CHANGED']]))
            $aValsToAdd[$CNF['FIELD_OFR_CHANGED']] = time();

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }
}

/** @} */
