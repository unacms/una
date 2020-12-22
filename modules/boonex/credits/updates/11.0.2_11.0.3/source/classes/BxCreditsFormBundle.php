<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Add/Edit bundle form
 */
class BxCreditsFormBundle extends BxTemplStudioFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_credits';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_NAME']])) {
            $sJsObject = $this->_oModule->_oConfig->getJsObject('studio');

            $iId = $this->getBundleId();
            $aMask = array('mask' => "javascript:%s.checkBundleName('%s');", $sJsObject, $CNF['FIELD_NAME']);
            if(!empty($iId) && $this->aParams['display'] == $CNF['OBJECT_FORM_BUNDLE_DISPLAY_EDIT']) {
                $aMask['mask'] = "javascript:%s.checkBundleName('%s', %d);";
                $aMask[] = $iId;
            }

            $sOnBlur = call_user_func_array('sprintf', array_values($aMask)); 
            $this->aInputs[$CNF['FIELD_NAME']]['attrs']['onblur'] = $sOnBlur;
        }
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_NAME']])) {
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $aLanguages = $oLanguage->getLanguagesExt();
            $sLanguageCurrent = $oLanguage->getCurrentLangName(false);

            $sTitle = BxDolForm::getSubmittedValue($CNF['FIELD_TITLE'] . '-' . $sLanguageCurrent, $this->aFormAttrs['method']);
            $sName = $this->_oModule->_oConfig->getBundleName($sTitle);

            BxDolForm::setSubmittedValue($CNF['FIELD_TITLE'], '_bx_credits_txt_bundle_title_' . strtolower($sName), $this->aFormAttrs['method']);
        }

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sNameKey = $CNF['FIELD_NAME'];
        if(isset($this->aInputs[$sNameKey])) {
            $sName = $this->getCleanValue($sNameKey);
            $aContentInfo = $this->_oModule->_oDb->getBundle(array('type' => 'id', 'id' => $iContentId));

            if($aContentInfo[$sNameKey] != $sName) {
                $sName = $this->_oModule->_oConfig->getBundleName($sName);
                BxDolForm::setSubmittedValue($sNameKey, $sName, $this->aFormAttrs['method'], $this->_aSpecificValues);
            }
        }

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    public function getBundleId()
    {
        $iResult = 0;

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $sId = bx_get('id');
            if(!empty($sId))
                $iResult = $sId;
        }
        else
            $iResult = array_shift($aIds);

        return (int)$iResult;
    }
}

/** @} */
