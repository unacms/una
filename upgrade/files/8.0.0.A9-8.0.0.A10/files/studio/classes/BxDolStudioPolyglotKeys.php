<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioGrid');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioPolyglotQuery');

define('BX_DOL_STUDIO_PK_PREVIEW', 100);

class BxDolStudioPolyglotKeys extends BxTemplStudioGrid
{
    protected $iPreviewLength;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioPolyglotQuery();
        $this->iPreviewLength = BX_DOL_STUDIO_PK_PREVIEW;
    }

    protected function add(&$oForm)
    {
        $sKey = $oForm->getCleanValue('key');
        $iCategoryId = (int)$oForm->getCleanValue('category_id');
        $aLanguages = explode(',', $oForm->getCleanValue('languages'));

        if(empty($sKey) || empty($iCategoryId))
            return _t('_adm_pgt_err_create_key_empty_fields');

        $aStrings = array();
        foreach($aLanguages as $iLanguageId)
            $aStrings[$iLanguageId] = $oForm->getCleanValue('language_' . $iLanguageId);

        bx_import('BxDolStudioLanguagesUtils');
        $mixedResult = BxDolStudioLanguagesUtils::getInstance()->addLanguageString($sKey, $aStrings, 0, $iCategoryId);
        if($mixedResult === false)
            return _t('_adm_pgt_err_create_key');

        return $mixedResult;
    }
    protected function edit(&$oForm)
    {
        $iId = $oForm->getCleanValue('id');
        $aLanguages = explode(',', $oForm->getCleanValue('languages'));

        if(empty($iId) || empty($aLanguages))
            return _t('_adm_pgt_err_save');

        $aStrings = array();
        foreach($aLanguages as $iLanguageId)
            $aStrings[$iLanguageId] = $oForm->getCleanValue('language_' . $iLanguageId);

        bx_import('BxDolStudioLanguagesUtils');
        if(!BxDolStudioLanguagesUtils::getInstance()->updateLanguageStringById($iId, $aStrings))
            return _t('_adm_pgt_err_save');

        return $iId;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $iModule = 0;
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($iModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        $aLanguage = array();
        $iLanguage = $this->oDb->getLanguagesBy(array('type' => 'default'), $aLanguage);

        if($iLanguage <= 0 || empty($aLanguage)) {
            $aLanguages = array();
            $iLanguages = $this->oDb->getLanguagesBy(array('type' => 'active'), $aLanguages);
            if($iLanguages <= 0 || empty($aLanguages))
                return array();

            $aLanguage = $aLanguages[0];
        }

        $this->_aOptions['source'] = sprintf($this->_aOptions['source'], $aLanguage['id']);
        if((int)$iModule != 0)
            $this->_aOptions['source'] .= $this->oDb->prepare(" AND `tlk`.`IDCategory`=?", $iModule);

        $aResults = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        foreach($aResults as $iIndex => $aResult) {
            $aKey = $this->oDb->getKeyFullInfo($aResult['id'], $this->iPreviewLength);
            if(empty($aKey))
                continue;

            $aResults[$iIndex]['module'] = $aKey['module'];
            $aResults[$iIndex]['string'] = $aKey['strings'][$aLanguage['name']]['preview'];
            $aResults[$iIndex]['languages'] = implode(', ', array_keys($aKey['strings']));
        }

        return $aResults;
    }
}

/** @} */
