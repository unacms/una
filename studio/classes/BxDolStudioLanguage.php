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

class BxDolStudioLanguage extends BxTemplStudioPage
{
    protected $oDb;
    protected $sLanguage;
    protected $aLanguage;

    protected $sManageUrl;
    
    function __construct($sLanguage, $sPage)
    {
        parent::__construct($sLanguage);

        $this->oDb = new BxDolStudioLanguagesQuery();

        $this->sLanguage = BX_DOL_STUDIO_LANG_DEFAULT;
        if(is_string($sLanguage) && !empty($sLanguage))
            $this->sLanguage = $sLanguage;

        $this->sPage = BX_DOL_STUDIO_LANG_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

        $this->sManageUrl = BX_DOL_URL_STUDIO . 'language.php?name=' . $this->sLanguage;
    }

    public static function getObjectInstance($sModule = "", $sPage = "", $bInit = true)
	{
	    $oModuleDb = BxDolModuleQuery::getInstance();

        $sClass = 'BxTemplStudioLanguage';
	    if($sModule != '' && $oModuleDb->isModuleByName($sModule)) {
	        $aModule = $oModuleDb->getModuleByName($sModule);

	        if(file_exists(BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'classes/' . $aModule['class_prefix'] . 'StudioPage.php')) {
	            bx_import('StudioPage', $aModule);
	            $sClass = $aModule['class_prefix'] . 'StudioPage';
	        }
	    }

	    $oObject = new $sClass($sModule, $sPage);
	    if($bInit)
	    	$oObject->init();

	    return $oObject;
	}

    public function init()
    {
    	$this->checkAction();

        $this->aLanguage = BxDolModuleQuery::getInstance()->getModuleByName($this->sLanguage);
        if(empty($this->aLanguage) || !is_array($this->aLanguage))
            BxDolStudioTemplate::getInstance()->displayPageNotFound();

        $this->aPage['header'] = $this->aLanguage['title'];
        $this->aPage['caption'] = $this->aLanguage['title'];

        $this->addAction(array(
            'type' => 'switcher',
            'name' => 'activate',
            'caption' => '_adm_txt_pca_active',
            'checked' => (int)$this->aLanguage['enabled'] == 1,
            'onchange' => "javascript:" . $this->getPageJsObject() . ".activate('" . $this->sLanguage . "', this)"
        ), false);
    }

	public function checkAction()
    {
    	$sAction = bx_get('lang_action');
    	if($sAction === false)
    		return;

		$sAction = bx_process_input($sAction);

		$aResult = array('code' => 1, 'message' => _t('_adm_pgt_err_cannot_process_action'));
		switch($sAction) {
		    case 'activate':
                $sValue = bx_process_input(bx_get('lang_value'));
                if(empty($sValue))
                    break;

                $aResult = $this->activate($sValue);
                break;
		}

        echoJson($aResult);
		exit;
    }

    function activate($sLanguage)
    {
        $aLanguage = BxDolModuleQuery::getInstance()->getModuleByName($sLanguage);
        if(empty($aLanguage) || !is_array($aLanguage))
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        $aLanguages = array();
        $iLanguages = $this->oDb->getLanguagesBy(array('type' => 'active'), $aLanguages);
        if($iLanguages == 1 && $aLanguages[0]['name'] == $sLanguage)
            return array('code' => 1, 'message' => _t('_adm_pgt_err_last_active'));

        $sLanguageDefault = getParam('lang_default');
        if($aLanguage['uri'] == $sLanguageDefault)
            return array('code' => 2, 'message' => _t('_adm_pgt_err_deactivate_default'));

        $oInstallerUtils = BxDolStudioInstallerUtils::getInstance();

        $aResult = (int)$aLanguage['enabled'] == 0 ? $oInstallerUtils->perform($aLanguage['path'], 'enable') : $oInstallerUtils->perform($aLanguage['path'], 'disable');
        if($aResult['code'] != 0)
            return $aResult;

        $oTemplate = BxDolStudioTemplate::getInstance();

        $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
        if((int)$aLanguage['enabled'] == 0) {
            $aResult['content'] = $oTemplate->parseHtmlByName('page_content_2_col.html', array(
                'page_menu_code' => $this->getPageMenu(),
                'page_main_code' => $this->getPageCode()
            ));
        } else
            $aResult['content'] = "";

        return $aResult;
    }
}

/** @} */
