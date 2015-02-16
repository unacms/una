<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_METHOD_DEFAULT', 'post');

class BxDolStudioForm extends BxBaseFormView
{
    function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate !== false ? $oTemplate : BxDolStudioTemplate::getInstance());

        $this->_sCheckerHelper = isset($this->aParams['checker_helper']) ? $this->aParams['checker_helper'] : 'BxDolStudioFormCheckerHelper';
    }

    function initChecker($aValues = array (), $aSpecificValues = array())
    {
        parent::initChecker($aValues, $aSpecificValues);

        if($this->isSubmitted() && !$this->_isValid)
            $this->processTranslationsValue();
    }

    function insert($aValsToAdd = array(), $isIgnore = false)
    {
        $sAction = 'insert';
        $this->processTranslationsKey($sAction);

        $mixedResult = parent::insert($aValsToAdd, $isIgnore);
        if($mixedResult !== false)
            $this->processTranslations($sAction);

        return $mixedResult;
    }

    function update($val, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $sAction = 'update';
        $this->processTranslationsKey($sAction);

        $mixedResult = parent::update($val, $aValsToAdd, $aTrackTextFieldsChanges);
        if($mixedResult !== false)
            $this->processTranslations($sAction);

        return $mixedResult;
    }

    function updateWithVisibility($iId)
    {
        $iVisibleFor = BxDolStudioUtils::getVisibilityValue($this->getCleanValue('visible_for'), $this->getCleanValue('visible_for_levels'));
        BxDolForm::setSubmittedValue('visible_for_levels', $iVisibleFor, $this->aFormAttrs['method']);
        unset($this->aInputs['visible_for']);

        return $this->update($iId);
    }

    function processImageUploaderSave($sName, $iId = 0)
    {
        if($this->aInputs[$sName]['type'] != 'image_uploader')
            return $iId;

        $aInput = $this->aInputs[$sName];
        if(!empty($_FILES[$sName]['tmp_name'])) {
            $iProfileId = getLoggedId();

            $sStorage = isset($aInput['storage_object']) && $aInput['storage_object'] != '' ? $aInput['storage_object'] : BX_DOL_STORAGE_OBJ_IMAGES;
            $oStorage = BxDolStorage::getObjectInstance($sStorage);

            if((int)$iId != 0 && !$oStorage->deleteFile($iId))
                return _t('_adm_err_form_view_iu_delete');

            $iId = $oStorage->storeFileFromForm($_FILES[$aInput['name']], false, $iProfileId);
            if($iId === false)
                return _t('_adm_err_form_view_iu_save') . $oStorage->getErrorString();

            $oStorage->afterUploadCleanup($iId, $iProfileId);
        }

        return (int)$iId;
    }

	function getCleanValue ($sName)
    {
		$aResult = parent::getCleanValue($sName);

		$a = isset($this->aInputs[$sName]) ? $this->aInputs[$sName] : false;
		if($a && !empty($a['reverse']) && !empty($a['values']))
			$aResult = array_diff(array_keys($a['values']), (is_array($aResult) ? $aResult : array()));

		return $aResult;
    }

    protected function processTranslations($sType = 'insert')
    {
        $aType2Method = array(
            'insert' => 'addLanguageString',
            'update' => 'updateLanguageString'
        );

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguage->getLanguagesInfo();

        foreach($this->aInputs as $sName => $aInput)
            if(in_array($aInput['type'], array('text_translatable', 'textarea_translatable'))) {
                $sKey = $this->getCleanValue($sName);
                foreach($aLanguages as $aLanguage) {
                    $sString = BxDolForm::getSubmittedValue($sName . '-' . $aLanguage['name'], $this->aFormAttrs['method']);
                    if($sString !== false)
                        $oLanguage->$aType2Method[$sType]($sKey, $sString, $aLanguage['id']);
                }
            }
    }

    protected function processTranslationsKey($sType = 'insert')
    {
        $sLanguage = BxDolStudioLanguagesUtils::getInstance()->getCurrentLangName(false);

        foreach($this->aInputs as $sName => $aInput)
            if(in_array($aInput['type'], array('text_translatable', 'textarea_translatable'))) {
                $sKey = $this->getTranslationsKey($sType, $sName, $this->getCleanValue($sName));
                BxDolForm::setSubmittedValue($sName, $sKey, $this->aFormAttrs['method']);
            }
    }

    protected function processTranslationsValue ()
    {
        $aLanguages = BxDolStudioLanguagesUtils::getInstance()->getLanguages();

        foreach($this->aInputs as $sName => $aInput)
            if(in_array($aInput['type'], array('text_translatable', 'textarea_translatable')))
                foreach($aLanguages as $sLangName => $sLangTitle)
                    $this->aInputs[$sName]['values'][$sLangName] = BxDolForm::getSubmittedValue($sName . '-' . $sLangName, $this->aFormAttrs['method']);
    }

    protected function getTranslationsKey($sType, $sName, $sValue)
    {
        $iRand = time();
        $sPrefixDefault = "_sys_form_input";

        $sName = BxDolStudioUtils::getSystemName(trim($sName));

        $sResult = '';
        switch($sType) {
            case 'insert':
                $sValue = !empty($sValue) ? BxDolStudioUtils::getSystemName($sValue) : $sPrefixDefault;
                $sResult = $sValue . '_' . $sName . '_' . $iRand;
            break;

            case 'update':
                $sResult = !empty($sValue) ? $sValue : $sPrefixDefault . '_' . $sName . '_' . $iRand;
                break;
        }

        return $sResult;
    }
}

class BxDolStudioFormCheckerHelper extends BxDolFormCheckerHelper
{
    function checkAvailTranslatable($sVal, $aName, $sMethod = BX_DOL_STUDIO_METHOD_DEFAULT, $bAll = true)
    {
        if(empty($sMethod) || empty($aName))
            return false;

        $aLanguages = BxDolStudioLanguagesUtils::getInstance()->getLanguages();

        foreach($aLanguages as $sLangName => $sLangTitle) {
            $sValue = BxDolForm::getSubmittedValue($aName . '-' . $sLangName, $sMethod);
            $bValue = parent::checkAvail($sValue);

            if($bAll && !$bValue)
                return false;
            if(!$bAll && $bValue)
                return true;
        }

        return $bAll ? true : false;
    }

    function checkLengthTranslatable($sVal, $iLenMin, $iLenMax, $aName, $sMethod = BX_DOL_STUDIO_METHOD_DEFAULT, $bAll = true)
    {
        if(empty($sMethod) || empty($aName))
            return false;

        $aLanguages = BxDolStudioLanguagesUtils::getInstance()->getLanguages();

        foreach($aLanguages as $sLangName => $sLangTitle) {
            $sValue = BxDolForm::getSubmittedValue($aName . '-' . $sLangName, $sMethod);
            $bValue = parent::checkLength($sValue, $iLenMin, $iLenMax);

            if($bAll && !$bValue)
                return false;
            if(!$bAll && $bValue)
                return true;
        }

        return $bAll ? true : false;
    }

    function checkTemplate($sVal)
    {
        return strlen($sVal) > 0 && BxDolModuleQuery::getInstance()->isEnabled($sVal);
    }
}

/** @} */
