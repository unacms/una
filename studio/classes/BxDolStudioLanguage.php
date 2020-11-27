<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_LANG_DEFAULT', BX_DOL_LANGUAGE_DEFAULT);
define('BX_DOL_STUDIO_LANG_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_LANG_TYPE_DEFAULT', BX_DOL_STUDIO_LANG_TYPE_SETTINGS);

class BxDolStudioLanguage extends BxTemplStudioModule
{
    public function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->sLangPrefix = 'lang';
        $this->sParamPrefix = 'lang';

        $this->sManageUrl = BX_DOL_URL_STUDIO . 'language.php?name=' . $this->aPage['name'];
    }

    public function init()
    {
        parent::init();

        $this->aPage['header'] = $this->aModule['title'];
        $this->aPage['caption'] = $this->aModule['title'];
    }

    public function checkAction()
    {
    	$sAction = bx_get($this->sParamPrefix . '_action');
    	if($sAction === false)
            return false;

        $sAction = bx_process_input($sAction);

        $aResult = array('code' => 1, 'message' => _t('_adm_pgt_err_cannot_process_action'));
        switch($sAction) {
            default:
                $aResult = parent::checkAction();
        }

        return $aResult;
    }

    function activate($sModule, $iWidgetId = 0)
    {
        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($sModule);
        if(empty($aModule) || !is_array($aModule))
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        $aLanguages = array();
        $iLanguages = BxDolLanguagesQuery::getInstance()->getLanguagesBy(array('type' => 'active'), $aLanguages);
        if($iLanguages == 1 && $aLanguages[0]['name'] == $aModule['uri'])
            return array('code' => 1, 'message' => _t('_adm_pgt_err_last_active'));

        $sLanguageDefault = getParam('lang_default');
        if($aModule['uri'] == $sLanguageDefault)
            return array('code' => 2, 'message' => _t('_adm_pgt_err_deactivate_default'));

        return parent::activate($sModule, $iWidgetId);
    }
}

/** @} */
