<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_TEMPL_DEFAULT', 'protean');
define('BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_TEMPL_TYPE_LOGO', 'logo');
define('BX_DOL_STUDIO_TEMPL_TYPE_DEFAULT', BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS);


class BxDolStudioDesign extends BxTemplStudioPage
{
    protected $MODULE;

    protected $sTemplate;
    protected $aTemplate;
    protected $sPage;

    protected $sManageUrlPattern;
    protected $sManageUrl;

    public function __construct($sTemplate = "", $sPage = "")
    {
        parent::__construct($sTemplate);

        $this->oDb = new BxDolStudioDesignsQuery();

        $this->sTemplate = BX_DOL_STUDIO_TEMPL_DEFAULT;
        if(is_string($sTemplate) && !empty($sTemplate))
            $this->sTemplate = $sTemplate;

        $this->sPage = BX_DOL_STUDIO_TEMPL_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

		$this->sManageUrl = BX_DOL_URL_STUDIO . 'design.php?name=' . $this->sTemplate;
    }

	public static function getObjectInstance($sModule = "", $sPage = "", $bInit = true)
	{
	    $oModuleDb = BxDolModuleQuery::getInstance();

        $sClass = 'BxTemplStudioDesign';
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

    	$oDesigner = $this->getObjectDesigner();
    	$oDesigner->checkAction();

        $this->aTemplate = BxDolModuleQuery::getInstance()->getModuleByName($this->sTemplate);
        if(empty($this->aTemplate) || !is_array($this->aTemplate))
            BxDolStudioTemplate::getInstance()->displayPageNotFound();

        $this->aPage['header'] = $this->aTemplate['title'];
        $this->aPage['caption'] = $this->aTemplate['title'];

        $this->addAction(array(
            'type' => 'switcher',
            'name' => 'activate',
            'caption' => '_adm_txt_pca_active',
            'checked' => (int)$this->aTemplate['enabled'] == 1,
            'onchange' => "javascript:" . $this->getPageJsObject() . ".activate('" . $this->sTemplate . "', this)"
        ), false);
    }

    public function checkAction()
    {
    	$sAction = bx_get('templ_action');
    	if($sAction === false)
    		return;

		$sAction = bx_process_input($sAction);

		$aResult = array('code' => 1, 'message' => _t('_adm_dsg_err_cannot_process_action'));
		switch($sAction) {
			case 'activate':
				$sValue = bx_process_input(bx_get('templ_value'));
				if(empty($sValue))
					break;

				$aResult = $this->activate($sValue);
				break;

			case 'delete_logo':
				$oPage = $this->getObjectDesigner();

				$aResult = array('code' => 0, 'message' => '');
				if(!$oPage->deleteLogo())
					$aResult = array('code' => 2, 'message' => _t('_adm_dsg_err_remove_old_logo'));
				break;
		}

		echo json_encode($aResult);
		exit;
    }

    public function activate($sTemplate)
    {
        $aTemplate = BxDolModuleQuery::getInstance()->getModuleByName($sTemplate);
        if(empty($aTemplate) || !is_array($aTemplate))
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        $aTemplates = array();
        $iTemplates = $this->oDb->getTemplatesBy(array('type' => 'active'), $aTemplates);
        if($iTemplates == 1 && $aTemplates[0]['name'] == $sTemplate)
            return array('code' => 1, 'message' => _t('_adm_dsg_err_last_active'));

        $sTemplateDefault = getParam('template');
        if($aTemplate['uri'] == $sTemplateDefault)
            return array('code' => 2, 'message' => _t('_adm_dsg_err_deactivate_default'));

        $oInstallerUtils = BxDolStudioInstallerUtils::getInstance();

        $aResult = (int)$aTemplate['enabled'] == 0 ? $oInstallerUtils->perform($aTemplate['path'], 'enable') : $oInstallerUtils->perform($aTemplate['path'], 'disable');
        if($aResult['code'] != 0)
            return $aResult;

        $oTemplate = BxDolStudioTemplate::getInstance();

        $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
        if((int)$aTemplate['enabled'] == 0) {
            $aResult['content'] = $oTemplate->parseHtmlByName('page_content_2_col.html', array(
                'page_menu_code' => $this->getPageMenu(),
                'page_main_code' => $this->getPageCode()
            ));
        } else
            $aResult['content'] = "";

        return $aResult;
    }

    protected function getObjectDesigner()
    {
    	$oPage = new BxTemplStudioDesigner($this->sPage);
    	$oPage->setManageUrl($this->sManageUrl);

    	$oModule = BxDolModule::getInstance($this->MODULE);
    	$oPage->setLogoParams($oModule->_oConfig->getLogoParams());

    	return $oPage;
    }
}

/** @} */
